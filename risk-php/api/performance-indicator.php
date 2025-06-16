<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class PerformanceIndicator {
    private $conn;
    private $table_name = "performance_indicator";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new performance indicator
    public function create($data) {
        $query = "INSERT INTO performance_indicator (name, description, currentValue, targetValue, projectId) VALUES (:name, :description, :currentValue, :targetValue, :projectId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':currentValue', $data->currentValue);
        $stmt->bindParam(':targetValue', $data->targetValue);
        $stmt->bindParam(':projectId', $data->projectId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all performance indicators with related project
    public function findAll() {
        $query = "SELECT pi.*, p.name as projectName FROM performance_indicator pi LEFT JOIN project p ON pi.projectId = p.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get performance indicators by project id
    public function getByProject($projectId) {
        $query = "SELECT pi.*, p.name as projectName FROM performance_indicator pi LEFT JOIN project p ON pi.projectId = p.id WHERE pi.projectId = :projectId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get performance indicator by id
    public function findOne($id) {
        $query = "SELECT pi.*, p.name as projectName FROM performance_indicator pi LEFT JOIN project p ON pi.projectId = p.id WHERE pi.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $pi = $stmt->fetch(PDO::FETCH_ASSOC);
        return $pi ? $pi : null;
    }

    // Update performance indicator by id
    public function update($id, $data) {
        $query = "UPDATE performance_indicator SET name = :name, description = :description, currentValue = :currentValue, targetValue = :targetValue WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':currentValue', $data->currentValue);
        $stmt->bindParam(':targetValue', $data->targetValue);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete performance indicator by id
    public function remove($id) {
        $query = "DELETE FROM performance_indicator WHERE id = :id";
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
$pi = new PerformanceIndicator($db);

if ($segments[0] !== 'performance-indicator' && $segments[0] !== 'performance-indicators') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $pi->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create performance indicator']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $pi->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 3 && $segments[1] === 'project') {
            $projectId = intval($segments[2]);
            $result = $pi->getByProject($projectId);
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $pi->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Performance indicator not found']);
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
            $result = $pi->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update performance indicator']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($pi->remove($id)) {
                echo json_encode(['message' => 'Performance indicator deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete performance indicator']);
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
