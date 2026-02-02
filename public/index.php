<?php

require_once __DIR__ . '/../bootstrap.php';

use DI\ContainerBuilder;
use DI\create;
use Di\get;
use App\Middleware\AuthMiddleware;
use App\Services\JwtValidateService;
use App\Services\JwtGenerateService;
use \Bramus\Router\Router;
use App\Controllers\ListSiteController;
use App\Controllers\DetailsSiteController;
use App\Controllers\ListCountryController;
use App\Controllers\DetailsCountryController;
use App\Controllers\ListCategoryController;
use App\Controllers\DetailsCategoryController;
use App\Controllers\JwtController;
use App\Services\ListSiteService;
use App\Services\DetailsSiteService;
use App\Services\ListCountryService;
use App\Services\DetailsCountryService;
use App\Services\ListCategoryService;
use App\Services\DetailsCategoryService;
use App\Repositories\SiteRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SiteRepositoryInterface;
use App\Repositories\CountryRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;

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

$builder = new ContainerBuilder();

$builder->addDefinitions([
  SiteRepositoryInterface::class => DI\autowire(SiteRepository::class),
  CountryRepositoryInterface::class => DI\autowire(CountryRepository::class),
  CategoryRepositoryInterface::class => DI\autowire(CategoryRepository::class),
  ListSiteController::class => DI\autowire(),
  DetailsSiteController::class => DI\autowire(),
  ListCountryController::class => DI\autowire(),
  DetailsCountryController::class => DI\autowire(),
  ListCategoryController::class => DI\autowire(),
  DetailsCategoryController::class => DI\autowire(),
  ListSiteService::class => DI\autowire(),
  DetailsSiteService::class => DI\autowire(),
  ListCountryService::class => DI\autowire(),
  DetailsCountryService::class => DI\autowire(),
  ListCategoryService::class => DI\autowire(),
]);
$container = $builder->build();

$router = new Router();
$router->setNamespace('App\Controllers');

$router->get('/', function () {
  \App\HttpResponse::json([
    'name' => 'UNESCO Heritage API',
    'version' => '1.0.0',
    'endpoints' => [
      'GET ' . API_AUTH_LOGIN => 'Login to API',
      'GET ' . API_SITES => 'List of all sites (pagination, filters: ?country=, ?category=)',
      'GET ' . API_SITES . '/{id}' => 'Site details',
      'GET ' . API_COUNTRIES => 'Country list',
      'GET ' . API_COUNTRIES . '/{id}' => 'Country details',
      'GET ' . API_CATEGORIES => 'Category list',
      'GET ' . API_CATEGORIES . '/{id}' => 'Category details',
    ],
  ]);
});
$router->post(API_AUTH_LOGIN, function() {
  $controller = new App\Controllers\JwtController(new JwtGenerateService());
  $controller();
});

$authMiddleware = new AuthMiddleware(new JwtValidateService());

$router->before('GET', '/api/(sites|countries|categories).*', function()
  use ($authMiddleware) {
  $authMiddleware->handle();
});

$router->mount(API_SITES, function() use ($router, $container) {
  $router->get('/', function() use ($container) {
    $controller = $container->get(ListSiteController::class);
    $controller();
  });
  $router->get('/(\d+)', function($id) use ($container) {
    $controller = $container->get(DetailsSiteController::class);
    $controller((int)$id);
  });
});


$router->mount(API_COUNTRIES, function() use ($router, $container) {
  $router->get('/', function() use ($container) {
    $controller = $container->get(ListCountryController::class);
    $controller();
  });
  $router->get('/(\d+)', function($id) use ($container) {
    $controller = $container->get(DetailsCountryController::class);
    $controller((int)$id);
  });
});


$router->mount(API_CATEGORIES, function() use ($router, $container) {
  $router->get('/', function() use ($container) {
    $controller = $container->get(ListCategoryController::class);
    $controller();
  });
  $router->get('/(\d+)', function($id) use ($container) {
    $controller = $container->get(DetailsCategoryController::class);
    $controller((int)$id);
  });
});


$router->set404(function () {
  \App\HttpResponse::json([
    'error' => 'Endpoint not found',
    'status' => 404,
  ]);
});

$router->run();
