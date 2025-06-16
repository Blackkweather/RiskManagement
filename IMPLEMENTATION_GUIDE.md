# RiskManagement Platform - Quick Implementation Guide

## 🚨 Critical Security Fixes (Implement Immediately)

### 1. Authentication System Implementation

Create a new file: `/app/Auth/AuthController.php`
```php
<?php
namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
    private $secretKey = 'your-secret-key-here'; // Move to env
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['hash'])) {
            $payload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'role_id' => $user['roleId'],
                'exp' => time() + 3600 // 1 hour
            ];
            
            $token = JWT::encode($payload, $this->secretKey, 'HS256');
            return ['success' => true, 'token' => $token];
        }
        
        return ['success' => false, 'message' => 'Invalid credentials'];
    }
    
    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}
```

### 2. Middleware for API Protection

Create: `/app/Middleware/AuthMiddleware.php`
```php
<?php
namespace App\Middleware;

class AuthMiddleware {
    private $authController;
    
    public function __construct($authController) {
        $this->authController = $authController;
    }
    
    public function handle() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';
        $token = str_replace('Bearer ', '', $token);
        
        if (!$token || !$this->authController->validateToken($token)) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        return true;
    }
}
```

### 3. CSRF Protection

Create: `/app/Security/CsrfProtection.php`
```php
<?php
namespace App\Security;

class CsrfProtection {
    public static function generateToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateToken($token) {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function getTokenField() {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
```

### 4. Input Validation Helper

Create: `/app/Helpers/Validator.php`
```php
<?php
namespace App\Helpers;

class Validator {
    private $errors = [];
    
    public function validate($data, $rules) {
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            if (strpos($rule, 'required') !== false && empty($value)) {
                $this->errors[$field] = "$field is required";
            }
            
            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = "$field must be a valid email";
            }
            
            if (preg_match('/min:(\d+)/', $rule, $matches) && strlen($value) < $matches[1]) {
                $this->errors[$field] = "$field must be at least {$matches[1]} characters";
            }
        }
        
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
}
```

## 🏗️ Architecture Improvements

### 1. Router Implementation

Create: `/app/Core/Router.php`
```php
<?php
namespace App\Core;

class Router {
    private $routes = [];
    private $middleware = [];
    
    public function get($path, $handler, $middleware = []) {
        $this->addRoute('GET', $path, $handler, $middleware);
    }
    
    public function post($path, $handler, $middleware = []) {
        $this->addRoute('POST', $path, $handler, $middleware);
    }
    
    private function addRoute($method, $path, $handler, $middleware) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }
    
    public function dispatch($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri)) {
                // Run middleware
                foreach ($route['middleware'] as $middleware) {
                    if (!$middleware->handle()) {
                        return;
                    }
                }
                
                // Execute handler
                if (is_callable($route['handler'])) {
                    return call_user_func($route['handler']);
                }
                
                list($controller, $method) = explode('@', $route['handler']);
                $controllerInstance = new $controller();
                return $controllerInstance->$method();
            }
        }
        
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
    
    private function matchPath($routePath, $uri) {
        // Simple path matching (can be enhanced)
        return $routePath === $uri;
    }
}
```

### 2. Base Controller

Create: `/app/Core/Controller.php`
```php
<?php
namespace App\Core;

abstract class Controller {
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . "/../../resources/views/$view.php";
    }
}
```

### 3. Repository Pattern Example

Create: `/app/Repositories/RiskRepository.php`
```php
<?php
namespace App\Repositories;

class RiskRepository {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function findAll($filters = []) {
        $query = "SELECT r.*, a.name as activityName, e.name as entityName 
                  FROM Risk r
                  LEFT JOIN Activity a ON r.activityId = a.id
                  LEFT JOIN Entity e ON r.entityId = e.id
                  WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['projectId'])) {
            $query .= " AND e.projectId = :projectId";
            $params['projectId'] = $filters['projectId'];
        }
        
        if (!empty($filters['active'])) {
            $query .= " AND r.active = :active";
            $params['active'] = $filters['active'];
        }
        
        $query .= " ORDER BY r.brutCriticality DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO Risk (name, description, cause, frequency, financialImpact, 
                            legalImpact, reputationImpact, activityImpact, peopleImpact, 
                            brutCriticality, evaluation, netCriticality, active, 
                            activityId, entityId)
            VALUES (:name, :description, :cause, :frequency, :financialImpact,
                    :legalImpact, :reputationImpact, :activityImpact, :peopleImpact,
                    :brutCriticality, :evaluation, :netCriticality, :active,
                    :activityId, :entityId)
        ");
        
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
}
```

## 🚀 Performance Optimizations

### 1. Database Query Optimization

```sql
-- Add indexes for frequently queried columns
ALTER TABLE Risk ADD INDEX idx_entityId (entityId);
ALTER TABLE Risk ADD INDEX idx_activityId (activityId);
ALTER TABLE Risk ADD INDEX idx_active_criticality (active, brutCriticality);
ALTER TABLE Entity ADD INDEX idx_projectId (projectId);
ALTER TABLE Activity ADD INDEX idx_processId (processId);
ALTER TABLE Process ADD INDEX idx_domaineId (domaineId);
```

### 2. Caching Implementation

Create: `/app/Services/CacheService.php`
```php
<?php
namespace App\Services;

class CacheService {
    private $cacheDir = '/tmp/cache/';
    
    public function get($key) {
        $filename = $this->cacheDir . md5($key) . '.cache';
        
        if (file_exists($filename)) {
            $data = unserialize(file_get_contents($filename));
            if ($data['expires'] > time()) {
                return $data['value'];
            }
            unlink($filename);
        }
        
        return null;
    }
    
    public function set($key, $value, $ttl = 3600) {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
        
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        file_put_contents(
            $this->cacheDir . md5($key) . '.cache',
            serialize($data)
        );
    }
    
    public function delete($key) {
        $filename = $this->cacheDir . md5($key) . '.cache';
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
```

## 📝 Configuration Management

### 1. Environment Configuration

Create: `/.env`
```env
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=risk_php_db
DB_USERNAME=root
DB_PASSWORD=root

JWT_SECRET=your-secret-key-here
SESSION_LIFETIME=120
```

### 2. Config Loader

Create: `/app/Core/Config.php`
```php
<?php
namespace App\Core;

class Config {
    private static $config = [];
    
    public static function load() {
        $envFile = __DIR__ . '/../../.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    self::$config[trim($key)] = trim($value);
                }
            }
        }
    }
    
    public static function get($key, $default = null) {
        return self::$config[$key] ?? $default;
    }
}
```

## 🎯 Implementation Priority

1. **Week 1**: Security fixes (Authentication, CSRF, Input validation)
2. **Week 2**: Database fixes and basic caching
3. **Week 3**: Router and MVC structure
4. **Week 4**: API standardization and documentation

## 📚 Additional Resources

- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [PSR Standards](https://www.php-fig.org/psr/)
- [OWASP PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)

---
*This guide provides immediate actionable improvements. Implement these changes incrementally and test thoroughly.*
