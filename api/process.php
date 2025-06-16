<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class Process {
    private $conn;
    private $table_name = "process";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new process
    public function create($data) {
        $query = "INSERT INTO process (name, description, domaineId, parentId) VALUES (:name, :description, :domaineId, :parentId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':domaineId', $data->domaineId, PDO::PARAM_INT);
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

    // Get all processes with related data
    public function findAll() {
        $query = "SELECT pr.*, d.name as domaineName, parent.name as parentName FROM process pr 
                  LEFT JOIN domaine d ON pr.domaineId = d.id 
                  LEFT JOIN process parent ON pr.parentId = parent.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get processes by project id (via domaine.projectId)
    public function getByProject($projectId) {
        $query = "SELECT pr.*, d.name as domaineName, parent.name as parentName FROM process pr 
                  LEFT JOIN domaine d ON pr.domaineId = d.id 
                  LEFT JOIN process parent ON pr.parentId = parent.id 
                  WHERE d.projectId = :projectId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get process by id
    public function findOne($id) {
        $query = "SELECT pr.*, d.name as domaineName, parent.name as parentName FROM process pr 
                  LEFT JOIN domaine d ON pr.domaineId = d.id 
                  LEFT JOIN process parent ON pr.parentId = parent.id 
                  WHERE pr.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $process = $stmt->fetch(PDO::FETCH_ASSOC);
        return $process ? $process : null;
    }

    // Update process by id
    public function update($id, $data) {
        $query = "UPDATE process SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete process by id
    public function remove($id) {
        $query = "DELETE FROM process WHERE id = :id";
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
$process = new Process($db);

if ($segments[0] !== 'process' && $segments[0] !== 'processes') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $process->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create process']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $process->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 3 && $segments[1] === 'project') {
            $projectId = intval($segments[2]);
            $result = $process->getByProject($projectId);
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $process->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Process not found']);
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
            $result = $process->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update process']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($process->remove($id)) {
                echo json_encode(['message' => 'Process deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete process']);
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
