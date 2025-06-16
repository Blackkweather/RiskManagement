<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "user";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Helper to remove sensitive data
    private function sanitizeUser($user) {
        if (isset($user['hash'])) {
            unset($user['hash']);
        }
        return $user;
    }

    // Create a new user
    public function create($data) {
        // Password hashing should be done here, but for simplicity, store as is or implement hashing
        $query = "INSERT INTO user (email, phone, lastName, firstName, roleId, statusId, clientProfileId, hash) VALUES (:email, :phone, :lastName, :firstName, :roleId, :statusId, :clientProfileId, :hash)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':phone', $data->phone);
        $stmt->bindParam(':lastName', $data->lastName);
        $stmt->bindParam(':firstName', $data->firstName);
        $stmt->bindParam(':roleId', $data->roleId, PDO::PARAM_INT);
        $stmt->bindParam(':statusId', $data->statusId, PDO::PARAM_INT);
        $clientProfileId = $data->clientProfileId ?? null;
        $stmt->bindParam(':clientProfileId', $clientProfileId, PDO::PARAM_INT);
        $hash = $data->hash ?? null; // Should hash password before storing
        $stmt->bindParam(':hash', $hash);
        if ($stmt->execute()) {
            $id = $this->conn->lastInsertId();
            return $this->findOne($id);
        }
        return null;
    }

    // Get all users
    public function findAll() {
        $query = "SELECT * FROM user";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map([$this, 'sanitizeUser'], $users);
    }

    // Get user by id
    public function findOne($id) {
        $query = "SELECT * FROM user WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $this->sanitizeUser($user) : null;
    }

    // Update user by id
    public function update($id, $data) {
        $query = "UPDATE user SET email = :email, phone = :phone, lastName = :lastName, firstName = :firstName, roleId = :roleId, statusId = :statusId, clientProfileId = :clientProfileId WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':phone', $data->phone);
        $stmt->bindParam(':lastName', $data->lastName);
        $stmt->bindParam(':firstName', $data->firstName);
        $stmt->bindParam(':roleId', $data->roleId, PDO::PARAM_INT);
        $stmt->bindParam(':statusId', $data->statusId, PDO::PARAM_INT);
        $clientProfileId = $data->clientProfileId ?? null;
        $stmt->bindParam(':clientProfileId', $clientProfileId, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete user by id
    public function remove($id) {
        $query = "DELETE FROM user WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($uri, '/'));

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($segments[0] !== 'user' && $segments[0] !== 'users') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $user->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create user']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $user->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $user->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'User not found']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'PATCH':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            $data = json_decode(file_get_contents("php://input"));
            $result = $user->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update user']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($user->remove($id)) {
                echo json_encode(['message' => 'User deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete user']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        break;
}
?>
