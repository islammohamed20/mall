<?php

namespace App\Jobs;

use App\Models\FacebookPost;
use App\Models\Shop;
use App\Services\FacebookService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncFacebookPostsForShop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $shopId, public int $limit = 25, public int $maxPages = 2) {}

    public function handle(FacebookService $facebook): void
    {
        $shop = Shop::query()->find($this->shopId);

        if (! $shop || ! $shop->facebook_page_id || ! $shop->facebook_page_access_token) {
            return;
        }

        $after = null;
        $page = 1;

        while ($page <= $this->maxPages) {
            $payload = $facebook->fetchPagePosts(
                pageId: (string) $shop->facebook_page_id,
                pageAccessToken: (string) $shop->facebook_page_access_token,
                limit: $this->limit,
                after: $after,
            );

            $posts = $payload['data'] ?? [];

            foreach ($posts as $post) {
                $postedAt = isset($post['created_time']) ? Carbon::parse($post['created_time']) : null;

                FacebookPost::query()->updateOrCreate(
                    [
                        'shop_id' => $shop->id,
                        'fb_post_id' => (string) ($post['id'] ?? ''),
                    ],
                    [
                        'message' => $post['message'] ?? null,
                        'permalink_url' => $post['permalink_url'] ?? null,
                        'image_url' => $post['full_picture'] ?? null,
                        'posted_at' => $postedAt,
                        'raw_payload' => $post,
                    ]
                );
            }

            $after = $payload['paging']['cursors']['after'] ?? null;
            if (! $after) {
                break;
            }

            $page++;
        }
    }
}
