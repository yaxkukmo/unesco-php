<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtValidateService {
  public function handle(string $token): ?object {
    try {
      return JWT::decode($token, new Key($_ENV['JWT_SECRET'],
  'HS256'));
    } catch (\Exception $e) {
      return null;
    }
  }
}
