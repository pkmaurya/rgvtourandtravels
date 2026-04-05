<?php

class Router {
    private $routes = [];

    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }


    public function get($path, $controller, $action) {
        $this->add('GET', $path, $controller, $action);
    }

    public function post($path, $controller, $action) {
        $this->add('POST', $path, $controller, $action);
    }

    public function put($path, $controller, $action) {
        $this->add('PUT', $path, $controller, $action);
    }

    public function delete($path, $controller, $action) {
        $this->add('DELETE', $path, $controller, $action);
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Strip the script name (e.g., /public/index.php) from the URI if present
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if (strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        // If index.php is in the URL, strip it too
        if (strpos($uri, '/index.php') === 0) {
             $uri = substr($uri, strlen('/index.php'));
        }

        // Remove trailing slash if present (except for root)
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                // Convert route path to regex
                $pattern = "@^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route['path']) . "$@D";
                
                if (preg_match($pattern, $uri, $matches)) {
                    // Load Controller
                    require_once __DIR__ . "/../controllers/" . $route['controller'] . ".php";
                    $controllerName = $route['controller'];
                    $controller = new $controllerName();
                    
                    // Filter out numeric keys from matches
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    
                    // Call Action
                    call_user_func_array([$controller, $route['action']], $params);
                    return;
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint Not Found']);
    }
}
