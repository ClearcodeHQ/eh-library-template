<?php

require __DIR__.'/../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Clearcode\EHLibrarySandbox\Slim\Middleware\AuthenticationMiddleware;
use Clearcode\EHLibrarySandbox\Slim\Middleware\AuthorizationMiddleware;
use Clearcode\EHLibraryAuth\Model\User;

$auth = new \Clearcode\EHLibraryAuth\Application();

$container = new \Slim\Container([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

$container['cache'] = function () {
    return new \Slim\HttpCache\CacheProvider();
};

$container['auth'] = function () use ($auth) {
    return $auth;
};

$container['library'] = function () {
    return new \Clearcode\EHLibrary\Application();
};

$app = new \Slim\App($container);
$app->add(new \Slim\HttpCache\Cache('public', 86400));

$authenticationMiddleware = new AuthenticationMiddleware($auth);

/**
 * example working route
 */
$app->map(['GET'], '/test', function(ServerRequestInterface $request, ResponseInterface $response, $args = []) {

    $response->getBody()->write(
        get_class($this->auth) . '</br>' .
        get_class($this->library) . '</br>'
    );

    return $response;
});

////Login user (login by email only - no password)
//$app->map(['<method>'], '<url>', function(ServerRequestInterface $request, ResponseInterface $response, $args = []) {
//
//    /* your code here */
//
//    $user = $this->auth->getUser(/* arguments */);
//
//    if (!$user instanceof User) {
//        /* your code here */
//    }
//
//    $token = $this->auth->generateToken(/* arguments */);
//
//    /* your code here */
//
//    return $response;
//});
//
////Register new reader
//$app->map(['<method>'], '<url>', function(ServerRequestInterface $request, ResponseInterface $response, $args = []) {
//
//    /* your code here */
//
//    $this->auth->registerUser(/* arguments */ ['reader']);
//
//    /* your code here */
//
//    return $response;
//});
//
////Add book to library
//$app->map(['<method>'], '<url>', function (ServerRequestInterface $request, ResponseInterface $response, $args = []) {
//
//    /* your code here */
//
//    $this->library->addBook(/* arguments */);
//
//    /* your code here */
//
//    return $response;
//})
//    ->add(new AuthorizationMiddleware(['librarian']))
//    ->add($authenticationMiddleware);
//
////List books in library
//$app->map(['<method>'], '<url>', function (ServerRequestInterface $request, ResponseInterface $response, $args = []) {
//
//    /* your code here */
//
//    $books = $this->library->listOfBooks(/* arguments */);
//
//    /* your code here */
//
//    return $response;
//})
//    ->add(new AuthorizationMiddleware(['reader', 'librarian']))
//    ->add($authenticationMiddleware);
//
////Create reservation for book
//$app->map(['<method>'], '<url>', function (ServerRequestInterface $request, ResponseInterface $response, $args = []) {
//
//    /* your code here */
//
//    $this->library->createReservation(/* arguments */);
//
//    /* your code here */
//
//    return $response;
//})
//    ->add(new AuthorizationMiddleware(['reader', 'librarian']))
//    ->add($authenticationMiddleware);;
//
////Give away reservation for book
//$app->map(['<method>'], '<url>', function (ServerRequestInterface $request, ResponseInterface $response, $args = []) {
//
//    /* your code here */
//
//    $this->library->giveAwayBookInReservation(/* arguments */);
//
//    /* your code here */
//
//    return $response;
//})
//    ->add(new AuthorizationMiddleware(['librarian']))
//    ->add($authenticationMiddleware);;
//
////Give back book from reservation
//$app->map(['<method>'], '<url>', function (ServerRequestInterface $request, ResponseInterface $response, $args = []) {
//
//    /* your code here */
//
//    $this->library->giveBackBookFromReservation(/* arguments */);
//
//    /* your code here */
//
//    return $response;
//})
//    ->add(new AuthorizationMiddleware(['librarian']))
//    ->add($authenticationMiddleware);
//
////List reservations for book
//$app->map(['<method>'], '<url>', function (ServerRequestInterface $request, ResponseInterface $response, $args = []) {
//
//    /* your code here */
//
//    $reservations = $this->library->listReservationsForBook(/* arguments */);
//
//    /* your code here */
//
//    return $response;
//})
//    ->add(new AuthorizationMiddleware(['reader', 'librarian']))
//    ->add($authenticationMiddleware);

return $app;
