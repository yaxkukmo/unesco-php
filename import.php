<?php

require_once __DIR__ . '/bootstrap.php';

use App\Models\Country;
use App\Models\Category;
use App\Models\UnescoSite;
use App\Models\Site;
use Illuminate\Database\Capsule\Manager as Capsule;

echo "UNESCO Heritage Sites - Import danych\n";
echo "=====================================\n\n";

$jsonFile = $argv[1] ?? '/home/old/Downloads/lista_zabytkow_unesco.json';

if (!file_exists($jsonFile)) {
    echo "Błąd: Plik {$jsonFile} nie istnieje!\n";
    echo "Użycie: php import.php [ścieżka-do-pliku.json]\n";
    exit(1);
}

echo "Wczytywanie danych z: {$jsonFile}\n";
$jsonData = file_get_contents($jsonFile);
$records = json_decode($jsonData, true);

if (!$records) {
    echo "Błąd: Nie można zdekodować pliku JSON!\n";
    exit(1);
}

echo "Znaleziono " . count($records) . " rekordów\n\n";

// Cache dla krajów i kategorii
$countriesCache = [];
$categoriesCache = [];
$unescoSitesCache = [];

echo "Przetwarzanie danych...\n";
$progressInterval = 500;
$processed = 0;
$skipped = 0;

Capsule::connection()->transaction(function () use ($records, &$countriesCache, &$categoriesCache, &$unescoSitesCache, &$processed, &$skipped, $progressInterval) {
    foreach ($records as $record) {
        $processed++;

        if ($processed % $progressInterval === 0) {
            echo "Przetworzono: {$processed}/" . count($records) . "\n";
        }

        // Pobierz lub utwórz kraj
        $countryId = null;
        if (!empty($record['countryLabel'])) {
            $countryName = trim($record['countryLabel']);
            if (!isset($countriesCache[$countryName])) {
                $country = Country::firstOrCreate(['name' => $countryName]);
                $countriesCache[$countryName] = $country->id;
            }
            $countryId = $countriesCache[$countryName];
        }

        // Pobierz lub utwórz kategorię
        $categoryId = null;
        if (!empty($record['categoryLabel'])) {
            $categoryName = trim($record['categoryLabel']);
            if (!isset($categoriesCache[$categoryName])) {
                $category = Category::firstOrCreate(['name' => $categoryName]);
                $categoriesCache[$categoryName] = $category->id;
            }
            $categoryId = $categoriesCache[$categoryName];
        }

        // Pobierz lub utwórz UNESCO Site
        $unescoId = trim($record['unescoID']);
        if (!isset($unescoSitesCache[$unescoId])) {
            UnescoSite::firstOrCreate(['unesco_id' => $unescoId]);
            $unescoSitesCache[$unescoId] = true;
        }

        // Parsuj współrzędne
        $latitude = null;
        $longitude = null;
        if (!empty($record['coord'])) {
            // Format: "Point(longitude latitude)"
            if (preg_match('/Point\(([-\d.]+)\s+([-\d.]+)\)/', $record['coord'], $matches)) {
                $longitude = (float)$matches[1];
                $latitude = (float)$matches[2];
            }
        }

        // Pobierz wikidata_id z item URL
        $wikidataId = $record['item'];

        // Sprawdź czy site już istnieje
        $site = Site::where('wikidata_id', $wikidataId)->first();

        if ($site) {
            // Jeśli site istnieje, tylko dodaj powiązania
            if ($categoryId && !$site->categories()->wherePivot('category_id', $categoryId)->exists()) {
                $site->categories()->attach($categoryId);
            }
            if (!$site->unescoSites()->wherePivot('unesco_id', $unescoId)->exists()) {
                $site->unescoSites()->attach($unescoId);
            }
            $skipped++;
            continue;
        }

        // Utwórz nowy site
        $site = Site::create([
            'wikidata_id' => $wikidataId,
            'name' => trim($record['itemLabel']),
            'description' => !empty($record['description']) ? trim($record['description']) : null,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'image_url' => !empty($record['image']) ? trim($record['image']) : null,
            'wikipedia_url' => !empty($record['wikipediaURL']) ? trim($record['wikipediaURL']) : null,
            'country_id' => $countryId,
        ]);

        // Dodaj powiązania
        if ($categoryId) {
            $site->categories()->attach($categoryId);
        }
        $site->unescoSites()->attach($unescoId);
    }
});

echo "\n=====================================\n";
echo "Import zakończony!\n";
echo "Przetworzono: {$processed} rekordów\n";
echo "Pominięto (duplikaty): {$skipped}\n";
echo "Utworzono nowych: " . ($processed - $skipped) . "\n";
echo "\nStatystyki:\n";
echo "- Kraje: " . Country::count() . "\n";
echo "- Kategorie: " . Category::count() . "\n";
echo "- UNESCO Sites: " . UnescoSite::count() . "\n";
echo "- Sites: " . Site::count() . "\n";
echo "=====================================\n";
