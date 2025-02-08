<?php

namespace App\Http\Controllers;

use App\Enums\TopicTypeEnum;
use App\Http\Requests\MercadolibreAuthRequest;
use App\Jobs\ProcessMercadolibreRequestJob;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\MercadolibreService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MercadolibreController extends Controller
{
    protected $mercadolibreService;

    protected $userRepository;

    public function __construct(
        MercadolibreService $mercadolibreService,
        UserRepositoryInterface $userRepository
    ) {
        $this->mercadolibreService = $mercadolibreService;
        $this->userRepository = $userRepository;
    }

    public function auth(MercadolibreAuthRequest $request)
    {
        $user = Auth::user();
        $code = $request->code;

        try {
            $response = $this->mercadolibreService->getAccessToken($code);

            $this->userRepository->update([
                'external_access_token' => $response['access_token'],
                'external_refresh_token' => $response['refresh_token'],
                'external_expires_at' => now()->addSeconds($response['expires_in']),
                'external_id' => $response['user_id'],
            ], $user->id);

            return response()->json([
                'message' => 'Autenticación with MercadoLibre successful',
                'user' => $user->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to authenticate with MercadoLibre',
                'message' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function hookHandler(Request $request)
    {
        $topic = $request->input('topic');

        $shouldHandle = $topic === TopicTypeEnum::FLEX_HANDSHAKES || $topic === TopicTypeEnum::SHIPMENTS;

        if ($shouldHandle) {
            ProcessMercadolibreRequestJob::dispatch($request->input());
        }

        return response()->json([]);
    }
}
