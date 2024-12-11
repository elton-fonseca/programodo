<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\WebhookProcess;
use App\Http\Requests\WebhookProcessRequest;
use Illuminate\Http\JsonResponse;

class Webhook extends Controller
{
    public function __construct(
        private WebhookProcessRequest $request,
        private WebhookProcess $webhookProcess
    ) {}

    /**
     * Process webhook events sended by Paybylink
     */
    public function __invoke(): JsonResponse
    {
        $this->webhookProcess->execute($this->request->all());

        return response()->json(['status' => 'success']);
    }
}
