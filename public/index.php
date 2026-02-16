<?php

require_once __DIR__ . '/../bootstrap.php';

use DI\ContainerBuilder;
use Bramus\Router\Router;

// Infrastructure
use App\Infrastructure\Middleware\AuthMiddleware;
use App\Services\JwtValidateService;
use App\Services\JwtGenerateService;

// Bus
use App\Application\Bus\CommandBusInterface;
use App\Application\Bus\QueryBusInterface;
use App\Application\Bus\SimpleCommandBus;
use App\Application\Bus\SimpleQueryBus;

// Domain Repository Interfaces
use App\Domain\Site\Repository\SiteCommandRepositoryInterface;
use App\Domain\Site\Repository\SiteQueryRepositoryInterface;
use App\Domain\Country\Repository\CountryCommandRepositoryInterface;
use App\Domain\Country\Repository\CountryQueryRepositoryInterface;
use App\Domain\Category\Repository\CategoryCommandRepositoryInterface;
use App\Domain\Category\Repository\CategoryQueryRepositoryInterface;

// Infrastructure Repositories
use App\Infrastructure\Persistence\Eloquent\SiteCommandRepository;
use App\Infrastructure\Persistence\Eloquent\SiteQueryRepository;
use App\Infrastructure\Persistence\Eloquent\CountryCommandRepository;
use App\Infrastructure\Persistence\Eloquent\CountryQueryRepository;
use App\Infrastructure\Persistence\Eloquent\CategoryCommandRepository;
use App\Infrastructure\Persistence\Eloquent\CategoryQueryRepository;

// Commands
use App\Application\Command\CreateSite\CreateSiteCommand;
use App\Application\Command\CreateSite\CreateSiteHandler;

// Queries
use App\Application\Query\ListSites\ListSitesQuery;
use App\Application\Query\ListSites\ListSitesHandler;
use App\Application\Query\GetSiteDetails\GetSiteDetailsQuery;
use App\Application\Query\GetSiteDetails\GetSiteDetailsHandler;
use App\Application\Query\ListCountries\ListCountriesQuery;
use App\Application\Query\ListCountries\ListCountriesHandler;
use App\Application\Query\GetCountryDetails\GetCountryDetailsQuery;
use App\Application\Query\GetCountryDetails\GetCountryDetailsHandler;
use App\Application\Query\ListCategories\ListCategoriesQuery;
use App\Application\Query\ListCategories\ListCategoriesHandler;
use App\Application\Query\GetCategoryDetails\GetCategoryDetailsQuery;
use App\Application\Query\GetCategoryDetails\GetCategoryDetailsHandler;

// Controllers
use App\UI\Http\Controller\Site\ListSiteController;
use App\UI\Http\Controller\Site\DetailsSiteController;
use App\UI\Http\Controller\Site\CreateSiteController;
use App\UI\Http\Controller\Country\ListCountryController;
use App\UI\Http\Controller\Country\DetailsCountryController;
use App\UI\Http\Controller\Category\ListCategoryController;
use App\UI\Http\Controller\Category\DetailsCategoryController;
use App\UI\Http\Controller\Auth\JwtController;
use App\UI\Http\Response\HttpResponse;

define('API_AUTH_LOGIN', '/api/auth/login');
define('API_SITES', '/api/sites');
define('API_COUNTRIES', '/api/countries');
define('API_CATEGORIES', '/api/categories');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

// DI Container
$builder = new ContainerBuilder();

$builder->addDefinitions([
  // Repositories
  SiteCommandRepositoryInterface::class => DI\autowire(SiteCommandRepository::class),
  SiteQueryRepositoryInterface::class => DI\autowire(SiteQueryRepository::class),
  CountryCommandRepositoryInterface::class => DI\autowire(CountryCommandRepository::class),
  CountryQueryRepositoryInterface::class => DI\autowire(CountryQueryRepository::class),
  CategoryCommandRepositoryInterface::class => DI\autowire(CategoryCommandRepository::class),
  CategoryQueryRepositoryInterface::class => DI\autowire(CategoryQueryRepository::class),

  // Handlers
  CreateSiteHandler::class => DI\autowire(),
  ListSitesHandler::class => DI\autowire(),
  GetSiteDetailsHandler::class => DI\autowire(),
  ListCountriesHandler::class => DI\autowire(),
  GetCountryDetailsHandler::class => DI\autowire(),
  ListCategoriesHandler::class => DI\autowire(),
  GetCategoryDetailsHandler::class => DI\autowire(),

  // Controllers
  ListSiteController::class => DI\autowire(),
  DetailsSiteController::class => DI\autowire(),
  CreateSiteController::class => DI\autowire(),
  ListCountryController::class => DI\autowire(),
  DetailsCountryController::class => DI\autowire(),
  ListCategoryController::class => DI\autowire(),
  DetailsCategoryController::class => DI\autowire(),
]);

$container = $builder->build();

// Buses
$commandBus = new SimpleCommandBus($container);
$commandBus->register(CreateSiteCommand::class, CreateSiteHandler::class);

$queryBus = new SimpleQueryBus($container);
$queryBus->register(ListSitesQuery::class, ListSitesHandler::class);
$queryBus->register(GetSiteDetailsQuery::class, GetSiteDetailsHandler::class);
$queryBus->register(ListCountriesQuery::class, ListCountriesHandler::class);
$queryBus->register(GetCountryDetailsQuery::class, GetCountryDetailsHandler::class);
$queryBus->register(ListCategoriesQuery::class, ListCategoriesHandler::class);
$queryBus->register(GetCategoryDetailsQuery::class, GetCategoryDetailsHandler::class);

// Register buses in container
$container->set(CommandBusInterface::class, $commandBus);
$container->set(QueryBusInterface::class, $queryBus);

// Router
$router = new Router();

$router->get('/', function () {
  HttpResponse::json([
    'name' => 'UNESCO Heritage API',
    'version' => '1.0.0',
    'endpoints' => [
      'POST ' . API_AUTH_LOGIN => 'Login to API',
      'GET ' . API_SITES => 'List of all sites (pagination, filters: ?country=, ?category=)',
      'GET ' . API_SITES . '/{id}' => 'Site details',
      'POST ' . API_SITES => 'Create a new site',
      'GET ' . API_COUNTRIES => 'Country list',
      'GET ' . API_COUNTRIES . '/{id}' => 'Country details',
      'GET ' . API_CATEGORIES => 'Category list',
      'GET ' . API_CATEGORIES . '/{id}' => 'Category details',
    ],
  ]);
});

$router->post(API_AUTH_LOGIN, function () {
  $controller = new JwtController(new JwtGenerateService());
  $controller();
});

$authMiddleware = new AuthMiddleware(new JwtValidateService());

$router->before('GET|POST|PUT|DELETE', '/api/(sites|countries|categories).*', function ()
  use ($authMiddleware) {
  $authMiddleware->handle();
});

$router->mount(API_SITES, function () use ($router, $container) {
  $router->get('/', function () use ($container) {
    $controller = $container->get(ListSiteController::class);
    $controller();
  });
  $router->get('/([a-f0-9-]{36})', function ($id) use ($container) {
    $controller = $container->get(DetailsSiteController::class);
    $controller($id);
  });
  $router->post('/', function () use ($container) {
    $controller = $container->get(CreateSiteController::class);
    $controller();
  });
});

$router->mount(API_COUNTRIES, function () use ($router, $container) {
  $router->get('/', function () use ($container) {
    $controller = $container->get(ListCountryController::class);
    $controller();
  });
  $router->get('/(\d+)', function ($id) use ($container) {
    $controller = $container->get(DetailsCountryController::class);
    $controller((int) $id);
  });
});

$router->mount(API_CATEGORIES, function () use ($router, $container) {
  $router->get('/', function () use ($container) {
    $controller = $container->get(ListCategoryController::class);
    $controller();
  });
  $router->get('/(\d+)', function ($id) use ($container) {
    $controller = $container->get(DetailsCategoryController::class);
    $controller((int) $id);
  });
});

$router->set404(function () {
  HttpResponse::json([
    'error' => 'Endpoint not found',
    'status' => 404,
  ], 404);
});

$router->run();
