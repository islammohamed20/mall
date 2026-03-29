<?php

namespace App\Services;

use App\Models\FacebookPostOutgoing;
use App\Models\Shop;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FacebookService
{
    /**
     * Fetch posts from a Facebook Page (READ)
     */
    public function fetchPagePosts(string $pageId, string $pageAccessToken, int $limit = 25, ?string $after = null): array
    {
        $query = [
            'access_token' => $pageAccessToken,
            'fields' => 'id,message,created_time,permalink_url,full_picture',
            'limit' => $limit,
        ];

        if ($after) {
            $query['after'] = $after;
        }

        $response = Http::timeout(20)
            ->retry(2, 250)
            ->get($this->baseUrl()."/{$pageId}/posts", $query);

        $response->throw();

        return (array) $response->json();
    }

    /**
     * Publish a text post to a Facebook Page
     * 
     * @param string $pageId The Facebook Page ID
     * @param string $pageAccessToken The Page Access Token
     * @param string $message The text message to post
     * @param string|null $link Optional link URL to include
     * @return array Contains 'id' (post_id) on success
     * @throws RequestException
     */
    public function publishTextPost(string $pageId, string $pageAccessToken, string $message, ?string $link = null): array
    {
        $data = [
            'access_token' => $pageAccessToken,
            'message' => $message,
        ];

        if ($link) {
            $data['link'] = $link;
        }

        $response = Http::timeout(30)
            ->retry(2, 500)
            ->post($this->baseUrl()."/{$pageId}/feed", $data);

        $response->throw();

        $result = $response->json();

        Log::info('Facebook text post published', [
            'page_id' => $pageId,
            'post_id' => $result['id'] ?? null,
        ]);

        return $result;
    }

    /**
     * Publish a photo post to a Facebook Page
     * 
     * @param string $pageId The Facebook Page ID
     * @param string $pageAccessToken The Page Access Token
     * @param string $imagePath Local storage path to the image
     * @param string|null $caption Optional caption for the photo
     * @return array Contains 'id' (photo_id) and 'post_id' on success
     * @throws RequestException
     */
    public function publishPhotoPost(string $pageId, string $pageAccessToken, string $imagePath, ?string $caption = null): array
    {
        // Get the full path to the image
        $fullPath = Storage::disk('public')->path($imagePath);
        
        if (!file_exists($fullPath)) {
            throw new \RuntimeException("Image file not found: {$imagePath}");
        }

        $data = [
            'access_token' => $pageAccessToken,
        ];

        if ($caption) {
            $data['caption'] = $caption;
        }

        // For photo uploads, we need to send as multipart
        $response = Http::timeout(60)
            ->retry(2, 1000)
            ->attach('source', file_get_contents($fullPath), basename($fullPath))
            ->post($this->baseUrl()."/{$pageId}/photos", $data);

        $response->throw();

        $result = $response->json();

        Log::info('Facebook photo post published', [
            'page_id' => $pageId,
            'photo_id' => $result['id'] ?? null,
            'post_id' => $result['post_id'] ?? null,
        ]);

        return $result;
    }

    /**
     * Publish a photo post using image URL (alternative method)
     */
    public function publishPhotoFromUrl(string $pageId, string $pageAccessToken, string $imageUrl, ?string $caption = null): array
    {
        $data = [
            'access_token' => $pageAccessToken,
            'url' => $imageUrl,
        ];

        if ($caption) {
            $data['caption'] = $caption;
        }

        $response = Http::timeout(60)
            ->retry(2, 1000)
            ->post($this->baseUrl()."/{$pageId}/photos", $data);

        $response->throw();

        return $response->json();
    }

    /**
     * Get the permalink for a published post
     */
    public function getPostPermalink(string $postId, string $pageAccessToken): ?string
    {
        try {
            $response = Http::timeout(15)
                ->get($this->baseUrl()."/{$postId}", [
                    'access_token' => $pageAccessToken,
                    'fields' => 'permalink_url',
                ]);

            $response->throw();
            
            return $response->json('permalink_url');
        } catch (\Exception $e) {
            Log::warning('Failed to get post permalink', [
                'post_id' => $postId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Verify a Page Access Token is valid and has required permissions
     */
    public function verifyPageToken(string $pageAccessToken): array
    {
        try {
            $response = Http::timeout(15)
                ->get($this->baseUrl()."/me", [
                    'access_token' => $pageAccessToken,
                    'fields' => 'id,name,access_token,tasks',
                ]);

            $response->throw();
            
            $data = $response->json();
            
            return [
                'valid' => true,
                'page_id' => $data['id'] ?? null,
                'page_name' => $data['name'] ?? null,
                'tasks' => $data['tasks'] ?? [],
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a post from a Facebook Page
     */
    public function deletePost(string $postId, string $pageAccessToken): bool
    {
        try {
            $response = Http::timeout(20)
                ->delete($this->baseUrl()."/{$postId}", [
                    'access_token' => $pageAccessToken,
                ]);

            $response->throw();

            Log::info('Facebook post deleted', ['post_id' => $postId]);

            return $response->json('success', false);
        } catch (\Exception $e) {
            Log::error('Failed to delete Facebook post', [
                'post_id' => $postId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Process and publish a FacebookPostOutgoing record
     */
    public function publishOutgoingPost(FacebookPostOutgoing $post): array
    {
        $shop = $post->shop;

        if (!$shop->facebook_page_id || !$shop->facebook_page_access_token) {
            throw new \RuntimeException('Shop does not have Facebook credentials configured');
        }

        $pageId = $shop->facebook_page_id;
        $accessToken = $shop->facebook_page_access_token; // Auto-decrypted by model

        $post->markAsPublishing();

        try {
            $result = match($post->post_type) {
                FacebookPostOutgoing::TYPE_PHOTO => $this->publishPhotoPost(
                    $pageId,
                    $accessToken,
                    $post->image,
                    $post->message
                ),
                FacebookPostOutgoing::TYPE_LINK => $this->publishTextPost(
                    $pageId,
                    $accessToken,
                    $post->message,
                    $post->link_url
                ),
                default => $this->publishTextPost(
                    $pageId,
                    $accessToken,
                    $post->message
                ),
            };

            // Get the post ID (different response formats for photos vs text posts)
            $facebookPostId = $result['post_id'] ?? $result['id'] ?? null;

            // Try to get permalink
            $permalink = null;
            if ($facebookPostId) {
                $permalink = $this->getPostPermalink($facebookPostId, $accessToken);
            }

            $post->markAsPublished($facebookPostId, $permalink);

            return [
                'success' => true,
                'facebook_post_id' => $facebookPostId,
                'permalink' => $permalink,
            ];

        } catch (\Exception $e) {
            $post->markAsFailed($e->getMessage());

            Log::error('Failed to publish Facebook post', [
                'post_id' => $post->id,
                'shop_id' => $shop->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    private function baseUrl(): string
    {
        $version = config('services.facebook.version', 'v19.0');

        return "https://graph.facebook.com/{$version}";
    }
}
