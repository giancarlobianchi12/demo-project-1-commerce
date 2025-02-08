<?php

namespace Tests\Traits;

trait MercadolibreBodyTraitTest
{
    protected $shipmentResponse = [
        'substatus_history' => [],
        'snapshot_packing' => [
            'snapshot_id' => 'ece1ae2a-44d2-48c6-afe2-e23597871ba6',
            'pack_hash' => '1',
        ],
        'receiver_id' => 1652276660,
        'base_cost' => 2589.99,
        'status_history' => [
            'date_shipped' => '2024-01-28T23:57:35.169-04:00',
            'date_returned' => null,
            'date_delivered' => '2024-01-29T00:02:19.543-04:00',
            'date_first_visit' => '2024-01-29T00:02:19.543-04:00',
            'date_not_delivered' => null,
            'date_cancelled' => null,
            'date_handling' => '2024-01-28T23:50:26.000-04:00',
            'date_ready_to_ship' => '2024-01-28T23:50:27.086-04:00',
        ],
        'type' => 'forward',
        'return_details' => null,
        'sender_id' => 1652273320,
        'mode' => 'me2',
        'order_cost' => 75000,
        'priority_class' => [
            'id' => null,
        ],
        'service_id' => 906184,
        'shipping_items' => [
            [
                'domain_id' => null,
                'quantity' => 1,
                'dimensions_source' => [
                    'origin' => 'categories',
                    'id' => 'MLA1405372503__1',
                ],
                'description' => 'Botella Fox Botella De Agua Base - 22 Oz Transparente Fox Color Agua',
                'id' => 'MLA1405372503',
                'user_product_id' => 'MLAU139561832',
                'sender_id' => 1652273320,
                'dimensions' => '8.0x8.0x25.0,150.0',
            ],
        ],
        'tracking_number' => '44462102666',
        'cost_components' => [
            'loyal_discount' => 0,
            'special_discount' => 0,
            'compensation' => 0,
            'gap_discount' => 0,
            'ratio' => 4661.98,
        ],
        'id' => 44462102666,
        'tracking_method' => null,
        'last_updated' => '2024-01-29T00:02:23.199-04:00',
        'items_types' => [
            'new',
        ],
        'comments' => null,
        'substatus' => null,
        'date_created' => '2024-01-28T23:50:14.073-04:00',
        'date_first_printed' => '2024-01-28T23:51:09.641-04:00',
        'created_by' => 'receiver',
        'application_id' => null,
        'shipping_option' => [
            'processing_time' => null,
            'cost' => 0,
            'estimated_schedule_limit' => [
                'date' => null,
            ],
            'shipping_method_id' => 512747,
            'estimated_delivery_final' => [
                'date' => '2024-01-29T00:00:00.000-03:00',
                'offset' => 480,
            ],
            'buffering' => [
                'date' => null,
            ],
            'pickup_promise' => [
                'from' => null,
                'to' => null,
            ],
            'list_cost' => 2071.99,
            'estimated_delivery_limit' => [
                'date' => '2024-01-29T00:00:00.000-03:00',
                'offset' => 72,
            ],
            'priority_class' => [
                'id' => null,
            ],
            'delivery_promise' => 'estimated',
            'delivery_type' => 'estimated',
            'estimated_handling_limit' => [
                'date' => '2024-01-29T00:00:00.000-03:00',
            ],
            'estimated_delivery_time' => [
                'date' => '2024-01-29T00:00:00.000-03:00',
                'pay_before' => '2024-01-29T18:00:00.000-03:00',
                'schedule' => null,
                'unit' => 'hour',
                'offset' => [
                    'date' => null,
                    'shipping' => null,
                ],
                'shipping' => 0,
                'time_frame' => [
                    'from' => null,
                    'to' => null,
                ],
                'handling' => 0,
                'type' => 'known',
            ],
            'name' => 'Prioritario a domicilio',
            'id' => 271385617,
            'estimated_delivery_extended' => [
                'date' => '2024-01-29T00:00:00.000-03:00',
                'offset' => 72,
            ],
            'currency_id' => 'ARS',
        ],
        'tags' => [
            'test_shipment',
        ],
        'sender_address' => [
            'country' => [
                'id' => 'AR',
                'name' => 'Argentina',
            ],
            'address_line' => 'XXXXXXX',
            'types' => [
                'logistic_center_ARP16522733201',
                'self_service_partner',
            ],
            'scoring' => null,
            'agency' => null,
            'city' => [
                'id' => null,
                'name' => 'Barrio 123',
            ],
            'geolocation_type' => 'APPROXIMATE',
            'latitude' => 0,
            'municipality' => [
                'id' => null,
                'name' => null,
            ],
            'location_id' => null,
            'street_name' => 'XXXXXXX',
            'zip_code' => 'XXXXXXX',
            'geolocation_source' => null,
            'intersection' => null,
            'street_number' => 'XXXXXXX',
            'comment' => 'XXXXXXX',
            'id' => null,
            'state' => [
                'id' => 'AR-C',
                'name' => 'Capital Federal',
            ],
            'neighborhood' => [
                'id' => null,
                'name' => null,
            ],
            'geolocation_last_updated' => null,
            'longitude' => 0,
        ],
        'sibling' => [
            'reason' => null,
            'sibling_id' => null,
            'description' => null,
            'source' => null,
            'date_created' => null,
            'last_updated' => null,
        ],
        'return_tracking_number' => null,
        'site_id' => 'MLA',
        'carrier_info' => null,
        'market_place' => 'MELI',
        'receiver_address' => [
            'country' => [
                'id' => 'AR',
                'name' => 'Argentina',
            ],
            'address_line' => 'Calle Paunero SN',
            'types' => [],
            'scoring' => null,
            'agency' => null,
            'city' => [
                'id' => 'TUxBQlBBTDI1MTVa',
                'name' => 'Palermo',
            ],
            'geolocation_type' => 'APPROXIMATE',
            'latitude' => -34.581971,
            'municipality' => [
                'id' => null,
                'name' => null,
            ],
            'location_id' => null,
            'street_name' => 'Calle Paunero',
            'zip_code' => '1425',
            'geolocation_source' => 'map-verified',
            'delivery_preference' => 'residential',
            'intersection' => null,
            'street_number' => 'SN',
            'receiver_name' => 'TEST USER BUYER',
            'comment' => '2B Referencia: Entre test y test',
            'id' => 1354735172,
            'state' => [
                'id' => 'AR-C',
                'name' => 'Capital Federal',
            ],
            'neighborhood' => [
                'id' => null,
                'name' => null,
            ],
            'geolocation_last_updated' => '2024-01-23T23:24:55.157Z',
            'receiver_phone' => 'XXXXXXX',
            'longitude' => -58.408932,
        ],
        'customer_id' => null,
        'order_id' => 2000007482586526,
        'quotation' => null,
        'status' => 'delivered',
        'logistic_type' => 'self_service',
    ];

    protected $shipmentBodyRequest = [
        '_id' => '57d46e5c-8e4f-4174-a6fb-d8b969cce274',
        'topic' => 'shipments',
        'resource' => '/shipments/44462102666',
        'user_id' => 1652273320,
        'application_id' => 4170687618702922,
        'sent' => '2024-01-29T03:57:35.321Z',
        'attempts' => 1,
        'received' => '2024-01-29T03:57:35.271Z',
        'actions' => [],
    ];

    protected $refreshTokenResponse = [
        'access_token' => 'APP_USR-4170687618702922-020816-aa235c4213f0c3a04e832b8829aa2042-1652273320',
        'token_type' => 'Bearer',
        'expires_in' => 21600,
        'scope' => 'offline_access read',
        'user_id' => 1652273320,
        'refresh_token' => 'TG-67a7c5769bed3d00018b8ee5-1652273320',
    ];
}
