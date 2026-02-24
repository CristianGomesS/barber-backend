<?php

namespace App\Repositories\Core;

use App\Models\ResetPassword;
use App\Repositories\Core\BaseRepository;
use Carbon\Carbon;

class PasswordResetRepository extends BaseRepository
{
    public function __construct(ResetPassword $entity)
    {
        parent::__construct($entity);
    }
    public function createToken(string $email, string $token)
    {
       $this->entity->where('email', $email)->delete();

        return  $this->entity->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    public function findToken(string $email, string $token)
    {
        return  $this->entity
            ->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>', Carbon::now()->subMinutes(60))
            ->first();
    }

    public function deleteToken(string $email)
    {
        return  $this->entity->where('email', $email)->delete();
    }
}
