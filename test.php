<?php
// router.php

class Router {
    private $routes = [];

    // Define a route with a URL pattern and a callback function
    public function addRoute($pattern, $callback) {
        $this->routes[$pattern] = $callback;
    }

    // Match the current URL to a defined route and execute the callback
    public function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];

        foreach ($this->routes as $pattern => $callback) {
            // Convert route pattern to a regular expression
            $pattern = preg_replace('/\//', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $uri, $matches)) {
                // Call the callback function and pass matched parameters
                array_shift($matches); // Remove the full match
                call_user_func_array($callback, $matches);
                return;
            }
        }

        // No route matched; handle 404 Not Found
        $this->notFound();
    }

    // Handle 404 Not Found
    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
}

// Usage:

$router = new Router();

// Define routes
$router->addRoute('/', function() {
    echo "Home Page";
});

$router->addRoute('/about', function() {
    echo "About Page";
});

$router->addRoute('/contact', function() {
    echo "Contact Page";
});

$router->dispatch();
?>
