## Installation

```
$ composer install
$ php bin/loadUsers.php
```

loadUsers adds a librarian user to the database (librarian@example.com)

## Application template

### Actions

authentication and authorization middleware is added to all actions in [app.php](https://github.com/ClearcodeHQ/eh-library-template/blob/master/src/app.php)
that need to be protected

### Placeholders

placeholders can be found in:
* [app.php](https://github.com/ClearcodeHQ/eh-library-template/blob/master/src/app.php)
* [AuthenticationMiddleware.php](https://github.com/ClearcodeHQ/eh-library-template/blob/master/src/Middleware/AuthenticationMiddleware.php)
* [AuthorizationMiddleware.php](https://github.com/ClearcodeHQ/eh-library-template/blob/master/src/Middleware/AuthorizationMiddleware.php)

#### log in example

this placeholder is for implementation of user log in. In this current example **password is not used, user is logged in only by email** for the sake of simplicity.

student is required to design:
* how does the url look
* how the request arguments are passed to the action
* what HTTP method is to be used here
* how user email is acquired (needed for getUser)
* what happens when the user does not exist (how should the response look like)
* how to generate and pass a JWT to the response in case user logs in successfully


```php
//Login user (login by email only - no password)
$app->map(['<method>'], '<url>', function(ServerRequestInterface $request, ResponseInterface $response, $args = []) {

    /* your code here */

    $user = $this->auth->getUser(/* arguments */);

    if (!$user instanceof User) {
        /* your code here */
    }

    $token = $this->auth->generateToken(/* arguments */);

    /* your code here */

    return $response;
});
```

#### authentication with JWT

this placeholder is from [AuthenticationMiddleware.php](https://github.com/ClearcodeHQ/eh-library-template/blob/master/src/Middleware/AuthenticationMiddleware.php) which is used to authenticate the user using JWT

here the student designs:
* how to pass JWT in a request and use it to authenticate the user ($token is an argument in LibraryAuth::authenticate)
* how to handle exceptions thrown by authenticate method
* how the response should look like when user does not exist

```php
public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
{
    /* your code here */

    $this->auth->authenticate(/* arguments */);

    /* your code here */

    $token = (new Parser())->parse(/* arguments */);

    $user = $this->auth->getUser($token->getClaim('email'));

    if (!$user instanceof User) {
        /* your code here */
    }

    $request = $request->withAttribute('user', $user);
    $response = $next($request, $response);

    return $response;
}
```