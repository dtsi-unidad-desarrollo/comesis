<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;

class LogJobFailed
{
    public function handle(JobFailed $event)
    {
        try {
            $payload = method_exists($event->job, 'payload') ? $event->job->payload() : null;
        } catch (\Throwable $e) {
            $payload = null;
        }

        $display = null;
        if (is_array($payload)) {
            $display = $payload['displayName'] ?? ($payload['job'] ?? null);
        }

        Log::error('Job failed.', [
            'job' => $display ?? get_class($event->job),
            'connection' => $event->connectionName ?? null,
            'exception' => (string) $event->exception,
            'payload' => $payload,
        ]);
    }
}
