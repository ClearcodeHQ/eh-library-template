<?php


namespace Clearcode\EHLibrarySandbox\Slim\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthorizationMiddleware
{
    private $allowedRoles;

    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $user = $request->getAttribute('user');
        $isAuthorized = count(array_intersect($user->roles(), $this->allowedRoles)) > 0;

        if ($isAuthorized) {
            $response = $next($request, $response);
        } else {
            /* your code here */
            /* handle case where user is not authorized to access the resource */
        }

        return $response;
    }
}
