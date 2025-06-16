<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class StrategicObjectif {
    private $conn;
    private $table_name = "strategic_objectif";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new strategic objectif
    public function create($data) {
        $query = "INSERT INTO strategic_objectif (name, description, projectId) VALUES (:name, :description, :projectId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':projectId', $data->projectId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all strategic objectifs
    public function findAll() {
        $query = "SELECT * FROM strategic_objectif";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get strategic objectifs by project id
    public function getByProject($projectId) {
        $query = "SELECT * FROM strategic_objectif WHERE projectId = :projectId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get strategic objectif by id
    public function findOne($id) {
        $query = "SELECT * FROM strategic_objectif WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $so = $stmt->fetch(PDO::FETCH_ASSOC);
        return $so ? $so : null;
    }

    // Update strategic objectif by id
    public function update($id, $data) {
        $query = "UPDATE strategic_objectif SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete strategic objectif by id
    public function remove($id) {
        $query = "DELETE FROM strategic_objectif WHERE id = :id";
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
$strategicObjectif = new StrategicObjectif($db);

if ($segments[0] !== 'strategic-objectif' && $segments[0] !== 'strategic-objectives') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $strategicObjectif->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create strategic objectif']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $strategicObjectif->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 3 && $segments[1] === 'project') {
            $projectId = intval($segments[2]);
            $result = $strategicObjectif->getByProject($projectId);
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $strategicObjectif->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Strategic objectif not found']);
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
            $result = $strategicObjectif->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update strategic objectif']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($strategicObjectif->remove($id)) {
                echo json_encode(['message' => 'Strategic objectif deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete strategic objectif']);
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
