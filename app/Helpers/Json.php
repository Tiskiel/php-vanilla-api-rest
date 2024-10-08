<?php

namespace App\Helpers;

final class Json
{
    /**
     * This method is used to send a JSON response.
     *
     * @param array<string, mixed> $data
     */
    public static function response(array $data, int $status = 200): string
    {
        http_response_code($status);
        return json_encode($data);
    }
}
