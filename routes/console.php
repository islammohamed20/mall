<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('facebook:sync-posts {--shop=} {--limit=25} {--max-pages=2}', function () {
    $shopId = $this->option('shop');
    $limit = (int) $this->option('limit');
    $maxPages = (int) $this->option('max-pages');

    $shopsQuery = \App\Models\Shop::query()
        ->whereNotNull('facebook_page_id')
        ->whereNotNull('facebook_page_access_token');

    if ($shopId) {
        $shopsQuery->whereKey((int) $shopId);
    }

    $shops = $shopsQuery->pluck('id');

    foreach ($shops as $id) {
        \App\Jobs\SyncFacebookPostsForShop::dispatch((int) $id, $limit, $maxPages);
    }

    $this->info('Queued.');
})->purpose('Sync Facebook posts for shops');
