<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class Role {
    private $conn;
    private $table_name = "role";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new role
    public function create($data) {
        $query = "INSERT INTO role (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all roles except 'SAdmin'
    public function findAll() {
        $query = "SELECT * FROM role WHERE name != 'SAdmin'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get role by id
    public function findOne($id) {
        $query = "SELECT * FROM role WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role ? $role : null;
    }

    // Update role by id
    public function update($id, $data) {
        $query = "UPDATE role SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete role by id
    public function remove($id) {
        $query = "DELETE FROM role WHERE id = :id";
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
$role = new Role($db);

if ($segments[0] !== 'role' && $segments[0] !== 'roles') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $role->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create role']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $role->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $role->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Role not found']);
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
            $result = $role->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update role']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($role->remove($id)) {
                echo json_encode(['message' => 'Role deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete role']);
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
