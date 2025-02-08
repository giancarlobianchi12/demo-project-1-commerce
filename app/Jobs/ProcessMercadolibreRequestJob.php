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

class ProcessMercadolibreRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public array $request)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mercadolibreService = new MercadolibreService();

        $topic = $this->request['topic'];
        $resource = $this->request['resource'];
        $externalClientId = $this->request['user_id'];

        $user = User::where('external_id', $externalClientId)->first();

        if (! $user) {
            throw new Exception('External client id not found.');
        }

        $response = $mercadolibreService->getFromResource($user, $resource)->json();

        switch ($topic) {
            case TopicTypeEnum::FLEX_HANDSHAKES:
                preg_match('/\/shipments\/(\d+)\/assignment/', $this->request['resource'], $matches);
                $shipmentId = $matches[1];
                $order = Order::where('external_shipment_id', $shipmentId)->first();

                if (! $order) {
                    return;
                }

                if (! isset($response['driver_id'])) {
                    return;
                }

                $driverId = $response['driver_id'];
                $newDriver = User::where('external_id', $driverId)->where('type', 'driver')->first();

                if (! $newDriver) {
                    return;
                }

                $order->update(['driver_user_id' => $newDriver->id]);

                break;

            case TopicTypeEnum::SHIPMENTS:
                $shipmentId = $response['id'] ?? null;

                $order = Order::where('external_shipment_id', $shipmentId)->first();

                if (! $order) {
                    return;
                }

                $order->status = $response['status'];

                if ($order->status === OrderStatusEnum::DELIVERED) {
                    $order->delivered_at = $response['status_history']['date_delivered'] ?? null;
                }

                $order->save();

                break;

            case TopicTypeEnum::ORDERS:
                $status = $response['status'] ?? null;

                $order = Order::where('external_id', $response['id'])->firstOrFail();

                if ($status === OrderStatusEnum::CANCELLED) {
                    $order->update(['status' => OrderStatusEnum::CANCELLED]);
                }

                break;
        }
    }
}
