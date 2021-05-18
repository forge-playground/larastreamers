<?php

namespace App\Jobs;

use App\Models\Stream;
use App\Services\Twitter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TweetStreamIsLiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Stream $stream,
    ) {}

    public function handle()
    {
        $twitter = app(Twitter::class);
        if ($this->stream->hasBeenTweeted()) {
            return;
        }

        $status = "🔴 A new stream just started: {$this->stream->title}" . PHP_EOL . $this->stream->url();

        $twitter->tweet($status);

        $this->stream->markAsTweeted();
    }
}
