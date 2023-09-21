<?php
/////////////////////////////////////////////////////////////
#region - - - INIT - - -
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

// CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=UTF-8');
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Model\Utilities\Log;
use Model\Services\DataAccess;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/config.php';
#endregion

/////////////////////////////////////////////////////////////
#region - - - SERVER - - -
// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();
#endregion

/////////////////////////////////////////////////////////////
#region - - - TEST ROUTES - - -
$app->get('/test', function (Request $request, Response $response) { //return plain text
    $data = 'Server funcionando.';
    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'text/plain');
});
/////////////////////////////////////////////////////////////
#region - - - ROUTES - - -

$app->get('/getItemTypes', function (Request $request, Response $response) {            
    $data = json_encode(DataAccess::GetColumn('item_types', 'type', DB));        
    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/getCharacters', function (Request $request, Response $response) {            
    $data = json_encode(DataAccess::GetTable('characters', DB));        
    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/getCharItems', function (Request $request, Response $response)
{
    $queryParams = $request->getQueryParams();    
    $data = json_encode(DataAccess::GetEntries('items', DB, ['inventory'], [$queryParams['charId']]));
    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/itemDrop', function (Request $request, Response $response) {
    $data = json_encode(DataAccess::GetValues('item_types', 'attributes', DB, 'type', $_SERVER['HTTP_TYPE']));
    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/getType', function (Request $request, Response $response) {
    $data = json_encode(DataAccess::GetValues('item_types', 'attributes', DB, 'type', $_SERVER['HTTP_TYPE']));
    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->run();