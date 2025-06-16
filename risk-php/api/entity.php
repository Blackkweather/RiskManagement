<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class Entity {
    private $conn;
    private $table_name = "entity";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new entity
    public function create($data) {
        $query = "INSERT INTO entity (name, projectId, parentId) VALUES (:name, :projectId, :parentId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':projectId', $data->projectId, PDO::PARAM_INT);
        if (isset($data->parentId)) {
            $stmt->bindParam(':parentId', $data->parentId, PDO::PARAM_INT);
        } else {
            $parentId = null;
            $stmt->bindParam(':parentId', $parentId, PDO::PARAM_NULL);
        }
        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all entities with related data
    public function findAll() {
        $query = "SELECT e.*, p.name as projectName, parent.name as parentName FROM entity e 
                  LEFT JOIN project p ON e.projectId = p.id 
                  LEFT JOIN entity parent ON e.parentId = parent.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get entities by project id
    public function getByProject($projectId) {
        $query = "SELECT e.*, p.name as projectName, parent.name as parentName FROM entity e 
                  LEFT JOIN project p ON e.projectId = p.id 
                  LEFT JOIN entity parent ON e.parentId = parent.id 
                  WHERE e.projectId = :projectId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get entity by id
    public function findOne($id) {
        $query = "SELECT e.*, p.name as projectName, parent.name as parentName FROM entity e 
                  LEFT JOIN project p ON e.projectId = p.id 
                  LEFT JOIN entity parent ON e.parentId = parent.id 
                  WHERE e.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $entity = $stmt->fetch(PDO::FETCH_ASSOC);
        return $entity ? $entity : null;
    }

    // Update entity by id
    public function update($id, $data) {
        $query = "UPDATE entity SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete entity by id
    public function remove($id) {
        $query = "DELETE FROM entity WHERE id = :id";
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
$entity = new Entity($db);

if ($segments[0] !== 'entity' && $segments[0] !== 'entities') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $entity->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create entity']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $entity->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 3 && $segments[1] === 'project') {
            $projectId = intval($segments[2]);
            $result = $entity->getByProject($projectId);
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $entity->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Entity not found']);
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
            $result = $entity->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update entity']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($entity->remove($id)) {
                echo json_encode(['message' => 'Entity deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete entity']);
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
