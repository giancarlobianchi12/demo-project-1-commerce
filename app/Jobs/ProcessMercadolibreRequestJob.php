<?php

namespace App\Jobs;

use App\Enums\OrderStatusEnum;
use App\Enums\TopicTypeEnum;
use App\Models\Order;
use App\Models\User;
use App\Services\MercadolibreService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessMercadolibreRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $request
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $mercadolibreService = new MercadolibreService();

            $user = $this->getUser();

            if (! $user) {
                Log::warning('User not found for external ID: '.($this->request['user_id']));

                return;
            }

            $response = $mercadolibreService->getFromResource($user, $this->request['resource']);

            if (! $response) {
                Log::warning("Empty response from MercadoLibre for resource: {$this->request['resource']}");

                return;
            }

            match ($this->request['topic']) {
                TopicTypeEnum::SHIPMENTS => $this->processShipment($response),
                default => Log::info("Unhandled topic: {$this->request['topic']}"),
            };
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit;

            Log::error("Error processing MercadoLibre request: {$e->getMessage()}", [
                'request' => $this->request,
                'exception' => $e,
            ]);
        }
    }

    /**
     * Get user by external ID.
     */
    private function getUser(): ?User
    {
        return User::where('external_id', $this->request['user_id'])->first();
    }

    /**
     * Process a shipment update.
     */
    private function processShipment(array $response): void
    {
        $shipmentId = $response['id'] ?? null;
        if (! $shipmentId) {
            Log::warning('Missing shipment ID in response', ['response' => $response]);

            return;
        }

        $order = Order::where('external_shipment_id', $shipmentId)->first();

        if (! $order) {
            Log::info("Order not found for shipment ID: $shipmentId");

            return;
        }

        $updateData = ['status' => $response['status']];

        if ($response['status'] === OrderStatusEnum::DELIVERED) {
            $updateData['delivered_at'] = $response['status_history']['date_delivered'] ?? null;
        }

        $order->update($updateData);
        Log::info("Shipment updated for order ID: {$order->id}");
    }
}
