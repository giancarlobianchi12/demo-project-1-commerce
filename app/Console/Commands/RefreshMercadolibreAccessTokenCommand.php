<?php

namespace App\Console\Commands;

use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Services\MercadolibreService;
use Illuminate\Console\Command;

class RefreshMercadolibreAccessTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercadolibre:refresh-access-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will get new mercadolibre access token';

    /**
     * MercadolibreService
     */
    protected $mercadolibreService;

    public function __construct(MercadolibreService $mercadolibreService)
    {
        parent::__construct();
        $this->mercadolibreService = $mercadolibreService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $to = now()->addHours();

        $users = User::where('external_expires_at', '<=', $to)
            ->where('type', UserTypeEnum::CLIENT)
            ->get();

        foreach ($users as $user) {
            try {
                $response = $this->mercadolibreService->refreshToken($user->external_refresh_token);

                $user->update([
                    'external_access_token' => $response['access_token'],
                    'external_refresh_token' => $response['refresh_token'],
                    'external_expires_at' => now()->addSeconds($response['expires_in']),
                ]);

                $this->info("Access token for user $user->id was updated successfully");
            } catch (\Exception $e) {
                $this->error("Access token for user $user->id was not updated successfully");
            }
        }
    }
}
