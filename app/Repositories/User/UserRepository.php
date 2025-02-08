<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * User constructor.
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function filter($filters)
    {
        return $this->model->filter($filters);
    }
}
