<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Order $order)
    {
        return $user->id === $order->client_user_id || $user->id === $order->driver_user_id;
    }

    public function update(User $user, Order $order)
    {
        return $user->id === $order->client_user_id || $user->id === $order->driver_user_id;
    }
}
