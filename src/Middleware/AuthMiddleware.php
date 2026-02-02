<?php

namespace App\Middleware;

use App\Services\JwtValidateService;

class AuthMiddleware {
  public function __construct(private JwtValidateService $jwtService) {}

  public function handle(): void {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
      \App\HttpResponse::json(['error' => 'Missing authorization header'], 401);
      exit;
    }

    $token = $matches[1];
    $payload = $this->jwtService->handle($token);

    if (!$payload) {
      \App\HttpResponse::json(['error' => 'Invalid or expired token'], 401);
      exit;
    }
  }
}
