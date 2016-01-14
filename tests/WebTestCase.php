<?php

namespace tests\Clearcode\EHLibrarySandbox\Slim;

use Clearcode\EHLibrary\Infrastructure\Persistence\LocalBookRepository;
use Clearcode\EHLibrary\Infrastructure\Persistence\LocalReservationRepository;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Uri;

abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    protected $jsonResponseData;
    /** @var Response */
    private $response;
    /** @var LocalBookRepository */
    private $books;
    /** @var LocalReservationRepository */
    private $reservations;
    /** @var App */
    private $app;

    protected function request($method, $url, array $requestParameters = [], array $headers = [])
    {
        $request = $this->prepareRequest($method, $url, $requestParameters, $headers);
        $response = new Response();

        $app = $this->app;
        $this->response = $app->callMiddlewareStack($request, $response);
        $this->jsonResponseData = json_decode((string) $this->response->getBody(), true);
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->books = new LocalBookRepository();
        $this->reservations = new LocalReservationRepository();

        $this->clearDatabase();

        $this->app =  $this->getApp();
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->books = null;
        $this->reservations = null;
        $this->app = null;
        $this->response = null;
        $this->jsonResponseData = null;
    }

    private function getApp()
    {
        return require __DIR__.'/../src/app.php';
    }

    private function prepareRequest($method, $url, array $requestParameters, array $requestHeaders)
    {
        $env = Environment::mock([
            'SCRIPT_NAME' => '/index.php',
            'REQUEST_URI' => $url,
            'REQUEST_METHOD' => $method,
        ]);

        $parts = explode('?', $url);

        if (isset($parts[1])) {
            $env['QUERY_STRING'] = $parts[1];
        }

        $uri = Uri::createFromEnvironment($env);
        $headers = Headers::createFromEnvironment($env);

        foreach ($requestHeaders as $headerName => $headerValue) {
            $headers->add($headerName, $headerValue);
        }

        $cookies = [];

        $serverParams = $env->all();

        $body = new RequestBody();
        $body->write(json_encode($requestParameters));

        $request = new Request($method, $uri, $headers, $cookies, $serverParams, $body);

        return $request->withHeader('Content-Type', 'application/json');
    }

    private function clearDatabase()
    {
        $this->books->clear();
        $this->reservations->clear();
    }
}
