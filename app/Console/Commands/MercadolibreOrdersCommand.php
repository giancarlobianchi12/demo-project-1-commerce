<?php

namespace App\Console\Commands;

use App\Enums\UserTypeEnum;
use App\Models\Order;
use App\Models\User;
use App\Services\MercadolibreService;
use Illuminate\Console\Command;

class MercadolibreOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercadolibre:sync-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $mercadolibreService;

    public function __construct()
    {
        parent::__construct();
        $this->mercadolibreService = new MercadolibreService();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clients = User::where('type', UserTypeEnum::CLIENT)
            ->whereNotNull('external_id')
            ->get();

        $from = now()->subDays(30)->timezone('-04:00')->format('Y-m-d\TH:i:s').'-04:00'; // mercadolibre timezone for default
        $to = now()->timezone('-04:00')->format('Y-m-d\TH:i:s').'-04:00';

        foreach ($clients as $client) {
            try {
                $response = $this->mercadolibreService->getOrders($client, $from, $to);
                $orders = $response['results'];

                array_map(function ($orders) use ($client) {
                    $this->processOrder($orders, $client);
                }, $orders);
            } catch (\Exception $e) {
                $this->error("Can not get orders for user $client->id : ".$e->getMessage());
            }
        }
    }

    public function processOrder($orderResponse, $client)
    {
        $shipmentId = $orderResponse['shipping']['id'] ?? null;

        $existOrder = Order::where('external_id', $orderResponse['id'])->exists();

        if ($existOrder) {
            return;
        }

        $existOrder = Order::where('external_shipment_id', $shipmentId)->exists();

        if ($existOrder) {
            return;
        }

        $shipmentResponse = $this->mercadolibreService->getShipment($client, $shipmentId);

        Order::create([
            'external_id' => $orderResponse['id'],
            'external_shipment_id' => $shipmentResponse['id'],
            'title' => isset($shipmentResponse['shipping_items'][0]['description']) ?? '',
            'status' => $shipmentResponse['status'],
            'price' => $shipmentResponse['order_cost'],
            'user_id' => $client->id,
        ]);
    }
}
