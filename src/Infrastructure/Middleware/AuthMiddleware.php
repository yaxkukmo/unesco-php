<?php

declare(strict_types=1);

namespace App\Infrastructure\Middleware;

use App\Services\JwtValidateService;
use App\UI\Http\Response\HttpResponse;

class AuthMiddleware {
  public function __construct(private JwtValidateService $jwtService) {}

  public function handle(): void {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

    if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
      HttpResponse::json(['error' => 'Missing authorization header'], 401);
      exit;
    }

    $token = $matches[1];
    $payload = $this->jwtService->handle($token);

    if (!$payload) {
      HttpResponse::json(['error' => 'Invalid or expired token'], 401);
      exit;
    }
  }
}
