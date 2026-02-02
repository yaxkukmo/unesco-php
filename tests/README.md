# Testy UNESCO Heritage API

## Struktura testów

```
tests/
├── Unit/                           # Testy jednostkowe (z mockami)
│   ├── Controllers/               # Testy kontrolerów
│   ├── Services/                  # Testy serwisów
│   ├── Filters/                   # Testy filtrów
│   └── Middleware/                # Testy middleware
├── Integration/                    # Testy integracyjne (z bazą danych)
│   └── Repositories/              # Testy repozytoriów
├── Feature/                        # Testy funkcjonalne (end-to-end)
│   └── AuthenticationTest.php     # Testy autentykacji
├── bootstrap.php                   # Bootstrap dla testów
├── TestCase.php                    # Bazowa klasa dla testów jednostkowych
├── DatabaseTestCase.php            # Bazowa klasa dla testów z bazą
└── ApiTestCase.php                 # Bazowa klasa dla testów API
```

## Instalacja zależności

```bash
composer install
```

## Uruchamianie testów

### Wszystkie testy:
```bash
composer test
# lub
./vendor/bin/phpunit
```

### Tylko testy jednostkowe:
```bash
./vendor/bin/phpunit --testsuite Unit
```

### Tylko testy integracyjne:
```bash
./vendor/bin/phpunit --testsuite Integration
```

### Tylko testy funkcjonalne:
```bash
./vendor/bin/phpunit --testsuite Feature
```

### Konkretny plik testowy:
```bash
./vendor/bin/phpunit tests/Unit/Services/JwtGenerateServiceTest.php
```

### Z pokryciem kodu (coverage):
```bash
composer test:coverage
# Raport zostanie wygenerowany w folderze coverage/
```

## Pokrycie testami

Projekt zawiera testy dla:

### ✅ Services (Unit Tests)
- `ListSiteService` - pobieranie listy zabytków
- `DetailsSiteService` - pobieranie szczegółów zabytku
- `JwtGenerateService` - generowanie tokenów JWT
- `JwtValidateService` - walidacja tokenów JWT

### ✅ Repositories (Integration Tests)
- `SiteRepository` - operacje na bazie danych dla zabytków
  - findById()
  - findByFilter()
  - count()
  - Filtrowanie po kraju/kategorii

### ✅ Controllers (Unit Tests)
- `ListSiteController` - endpoint listy zabytków
- `DetailsSiteController` - endpoint szczegółów zabytku

### ✅ Middleware (Unit Tests)
- `AuthMiddleware` - weryfikacja JWT przy każdym requeście

### ✅ Filters (Unit Tests)
- `ListSiteFilter` - walidacja parametrów zapytania
  - Limity min/max dla page/perPage
  - Obcięcie długich stringów
  - Domyślne wartości

### ✅ Feature Tests
- Autentykacja JWT (generowanie i walidacja tokenów)

## Konfiguracja testowa

Testy używają:
- **SQLite in-memory** jako bazy danych (szybkie, czyste środowisko)
- **Mockery** do mockowania zależności
- **PHPUnit 10** jako framework testowy

Konfiguracja w `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<env name="JWT_SECRET" value="test-secret-key-for-testing-only-min-32-chars"/>
```

## Pisanie nowych testów

### Test jednostkowy (z mockami):
```php
<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery;

class MyServiceTest extends TestCase
{
    public function test_example()
    {
        $mock = Mockery::mock(SomeDependency::class);
        $mock->shouldReceive('method')->andReturn('value');

        // Your test logic
        $this->assertTrue(true);
    }
}
```

### Test integracyjny (z bazą):
```php
<?php
namespace Tests\Integration;

use Tests\DatabaseTestCase;

class MyRepositoryTest extends DatabaseTestCase
{
    public function test_example()
    {
        $this->seed('table', ['data' => 'value']);

        // Your test logic
        $this->assertTrue(true);
    }
}
```

## Best Practices

1. **Jeden test = jedna asercja (ideały)**
2. **Arrange-Act-Assert pattern**
3. **Czyść środowisko po testach** (DatabaseTestCase robi to automatycznie)
4. **Używaj mocków dla testów jednostkowych**
5. **Używaj prawdziwej bazy dla testów integracyjnych**
6. **Nazwy testów powinny opisywać co testują**: `test_method_returns_expected_result_when_condition()`

## Debugowanie testów

### Wyświetl output podczas testów:
```bash
./vendor/bin/phpunit --debug
```

### Zatrzymaj na pierwszym błędzie:
```bash
./vendor/bin/phpunit --stop-on-failure
```

### Verbose output:
```bash
./vendor/bin/phpunit --verbose
```

## CI/CD

Testy można łatwo zintegrować z CI/CD:

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: composer test
```
