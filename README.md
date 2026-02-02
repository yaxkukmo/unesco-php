# UNESCO World Heritage Sites API

API zwracające listę zabytków UNESCO z całego świata.

## Wymagania

- PHP >= 8.0
- MySQL >= 5.7 lub MariaDB >= 10.2
- Composer

## Instalacja

1. Sklonuj repozytorium
2. Zainstaluj zależności:
```bash
composer install
```

3. Skopiuj plik konfiguracyjny:
```bash
cp .env.example .env
```

4. Skonfiguruj połączenie z bazą danych w pliku `.env`

5. Uruchom migracje:
```bash
php migrate.php
```

6. Zaimportuj dane:
```bash
php import.php
```

## Użycie

Uruchom serwer deweloperski:
```bash
php -S localhost:8000 -t public
```

## Struktura bazy danych

- `countries` - kraje
- `categories` - kategorie zabytków
- `unesco_sites` - wpisy UNESCO
- `sites` - pojedyncze obiekty/miejsca
- `site_unesco` - relacja sites ↔ unesco_sites
- `site_categories` - relacja sites ↔ categories

## API Endpoints

- `GET /api/sites` - lista wszystkich zabytków
- `GET /api/sites/{id}` - szczegóły zabytku
- `GET /api/countries` - lista krajów
- `GET /api/categories` - lista kategorii
