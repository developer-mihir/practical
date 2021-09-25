<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function getUserById($id)
    {
        return $this->user
            ->where('id', $id)->first();
    }

    public function getUserByEmail($email)
    {
        return $this->user
            ->where('email', $email)
            ->first();
    }

    public function store($data)
    {
        return $this->user->create($data);
    }
}
