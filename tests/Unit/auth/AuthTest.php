<?php

use App\Helpers\Auth;

it('should generate a token', function () {
    Auth::init();

    $token = Auth::generateToken(['uuid' => '123', 'first_name' => 'John', 'last_name' => 'Doe']);

    expect($token)->toBeString();
});

it('should decode a token', function () {
    Auth::init();

    $token = Auth::generateToken(['uuid' => '123', 'first_name' => 'John', 'last_name' => 'Doe']);

    $decoded = Auth::decodeToken($token);

    expect($decoded)->toBeObject();
    expect($decoded->data->uuid)->toBe('123');
    expect($decoded->data->first_name)->toBe('John');
    expect($decoded->data->last_name)->toBe('Doe');
});
