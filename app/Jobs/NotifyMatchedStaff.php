<?php

namespace App\Jobs;

use App\Models\ServiceRequest;
use App\Services\StaffMatchingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Fan out shortlist notifications for a service request.
 *
 * This was previously run inline: for up to ten matched staff it wrote a
 * database notification, sent an email, made a Termii SMS call and made a web
 * push call - roughly 30 blocking operations, most of them third-party HTTP,
 * inside the client's booking submission. One slow Termii response stalled the
 * booking, and a mid-loop failure left some staff notified and others not with
 * no retry.
 */
class NotifyMatchedStaff implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Third-party gateways fail transiently; back off rather than give up.
     */
    public int $tries = 3;

    public array $backoff = [30, 120];

    public int $timeout = 120;

    public function __construct(public ServiceRequest $serviceRequest)
    {
    }

    public function handle(StaffMatchingService $matchingService): void
    {
        $matchingService->notifyMatchedStaff($this->serviceRequest);
    }

    public function failed(\Throwable $e): void
    {
        Log::error('NotifyMatchedStaff failed', [
            'service_request_id' => $this->serviceRequest->id,
            'error' => $e->getMessage(),
        ]);
    }
}
