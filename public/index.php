<?php 

ini_set('display_errors',1);
ini_set('display_starup_errors',1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Psr\Http\Message\src\RequestInterface;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

session_start();
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER'),
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('curriculum','/cv',[
    'controller' => 'App\Controllers\CurriculumController',
    'action'=>'curriculumAction',
    'auth' => true
]);
$map->get('addJobs','/add/job',[
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction',
    'auth' => true
]);
$map->post('saveJobs','/add/job',[
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction',
    'auth' => true
]);
$map->get('addProject','/add/project',[
    'controller' => 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction',
    'auth' => true
]);
$map->post('saveProject','/add/project',[
    'controller' => 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction',
    'auth' => true
]);
$map->get('signUp','/signup',[
    'controller' => 'App\Controllers\SignupController',
    'action' => 'getAddUserAction'
]);
$map->post('SaveSignUp','/signup',[
    'controller' => 'App\Controllers\SignupController',
    'action' => 'getAddUserAction'
]);
$map->get('login','/',[
    'controller' => 'App\Controllers\LoginController',
    'action' => 'getloginAction'
]);
$map->post('redirect','/',[
    'controller' => 'App\Controllers\LoginController',
    'action' => 'postAuthAction'
]);
$map->get('admin','/admin',[
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getIndexAction',
    'auth' => true
]);
$map->get('logout','/logout',[
    'controller' => 'App\Controllers\LoginController',
    'action' => 'getLogoutAction',
    'auth' => true
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if (!$route){
    echo 'not found';
} else{
    $handlerData= $route->handler;
    $controllerName= $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth= $handlerData['auth'] ?? false;

    $controller = new $controllerName;
    $response=$controller->$actionName($request);
    
    $sessionUserId = $_SESSION['userId'] ?? null;
    if($needsAuth &&  !$sessionUserId){
        header('location: /');
        exit;
    }


    //foreach($response->getHeaders() as $name => $value){
    //    foreach($value as $value){
    //        header(sprintf('%s %s', $name,$value), false);
    //        echo "\r\n\r\n";
    //    }    
    //}
    //http_response_code($response->getStatusCode());
    echo $response->getBody();
}
