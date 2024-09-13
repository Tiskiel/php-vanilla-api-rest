<?php

namespace App\Helpers;

final class Json
{
    /**
     * This method is used to send a JSON response.
     *
     * @param array<string, mixed> $data
     */
    public static function response(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}
