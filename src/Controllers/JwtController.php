<?php

namespace App\Controllers;

use App\Services\JwtGenerateService;
use App\HttpResponse;

class JwtController {

  public function __construct(private JwtGenerateService $service) {}

  public function __invoke(): void {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if ($email !== $_ENV['API_USER_EMAIL'] || !password_verify($password,
  $_ENV['API_USER_PASSWORD'])) {
      HttpResponse::json(['success' => false, 'error' => 'Invalid credentials'], 401);
      return;
    }

    $token = $this->service->handle($email);

    HttpResponse::json([
      'success' => true,
      'data' => [
        'access_token' => $token,
        'token_type' => 'Bearer',
        'expires_in' => (int)$_ENV['JWT_EXPIRATION']
      ]
    ]);
  }
}
