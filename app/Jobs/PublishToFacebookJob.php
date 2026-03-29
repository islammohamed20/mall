<?php

namespace App\Jobs;

use App\Models\FacebookPostOutgoing;
use App\Services\FacebookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishToFacebookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    /**
     * Delete the job if its models no longer exist.
     */
    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public FacebookPostOutgoing $post
    ) {}

    /**
     * Execute the job.
     */
    public function handle(FacebookService $facebookService): void
    {
        Log::info('PublishToFacebookJob started', [
            'post_id' => $this->post->id,
            'shop_id' => $this->post->shop_id,
            'post_type' => $this->post->post_type,
            'attempt' => $this->attempts(),
        ]);

        // Skip if already published or not pending
        if ($this->post->status === FacebookPostOutgoing::STATUS_PUBLISHED) {
            Log::info('Post already published, skipping', ['post_id' => $this->post->id]);
            return;
        }

        // Verify shop has Facebook credentials
        $shop = $this->post->shop;
        if (!$shop || !$shop->facebook_page_id || !$shop->facebook_page_access_token) {
            Log::error('Shop missing Facebook credentials', [
                'post_id' => $this->post->id,
                'shop_id' => $this->post->shop_id,
            ]);
            
            $this->post->markAsFailed('المتجر غير مربوط بصفحة فيسبوك');
            return;
        }

        // Attempt to publish
        $result = $facebookService->publishOutgoingPost($this->post);

        if ($result['success']) {
            Log::info('Facebook post published successfully', [
                'post_id' => $this->post->id,
                'facebook_post_id' => $result['facebook_post_id'],
            ]);
        } else {
            Log::warning('Facebook post failed', [
                'post_id' => $this->post->id,
                'error' => $result['error'],
                'attempt' => $this->attempts(),
            ]);

            // If we still have attempts left and can retry, throw exception to trigger retry
            if ($this->attempts() < $this->tries && $this->post->canRetry()) {
                throw new \RuntimeException($result['error']);
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('PublishToFacebookJob failed permanently', [
            'post_id' => $this->post->id,
            'shop_id' => $this->post->shop_id,
            'error' => $exception->getMessage(),
        ]);

        // Mark as failed if not already
        if ($this->post->status !== FacebookPostOutgoing::STATUS_FAILED) {
            $this->post->markAsFailed('فشل النشر بعد عدة محاولات: ' . $exception->getMessage());
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'facebook-publish',
            'shop:' . $this->post->shop_id,
            'post:' . $this->post->id,
        ];
    }
}
