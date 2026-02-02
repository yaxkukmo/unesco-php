<?php

namespace App\Services;

use Firebase\JWT\JWT;

class JwtGenerateService {
  public function handle(string $email): string {
    $payload = [
      'iss' => 'unesco-api',
      'email' => $email,
      'iat' => time(),
      'exp' => time() + (int)$_ENV['JWT_EXPIRATION']
    ];

    return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
  }
}
