<?php

namespace App\Services;

use App\Helpers\Auth;

final class AuthService
{
    /**
     * This function are not totally finished because in a real context we need
     * to implement the logic to validate the data's structure and the
     * data's values. This is just a example of a good practice.
     *
     * @param array $data
     * @return string
     */
    public function generateToken(array $data): string
    {
        return Auth::generateToken($data);
    }
}
