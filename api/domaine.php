<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class Domaine {
    private $conn;
    private $table_name = "domaine";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new domaine
    public function create($data) {
        $query = "INSERT INTO domaine (name, projectId) VALUES (:name, :projectId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':projectId', $data->projectId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all domaines with related data
    public function findAll() {
        $query = "SELECT d.*, p.name as projectName FROM domaine d LEFT JOIN project p ON d.projectId = p.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get domaines by project id
    public function getByProject($projectId) {
        $query = "SELECT d.*, p.name as projectName FROM domaine d LEFT JOIN project p ON d.projectId = p.id WHERE d.projectId = :projectId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get domaine by id
    public function findOne($id) {
        $query = "SELECT d.*, p.name as projectName FROM domaine d LEFT JOIN project p ON d.projectId = p.id WHERE d.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $domaine = $stmt->fetch(PDO::FETCH_ASSOC);
        return $domaine ? $domaine : null;
    }

    // Update domaine by id
    public function update($id, $data) {
        $query = "UPDATE domaine SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete domaine by id
    public function remove($id) {
        $query = "DELETE FROM domaine WHERE id = :id";
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
$domaine = new Domaine($db);

if ($segments[0] !== 'domaine' && $segments[0] !== 'domaines') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $domaine->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create domaine']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $domaine->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 3 && $segments[1] === 'project') {
            $projectId = intval($segments[2]);
            $result = $domaine->getByProject($projectId);
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $domaine->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Domaine not found']);
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
            $result = $domaine->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update domaine']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($domaine->remove($id)) {
                echo json_encode(['message' => 'Domaine deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete domaine']);
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
