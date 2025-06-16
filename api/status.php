<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class Status {
    private $conn;
    private $table_name = "status";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new status
    public function create($data) {
        $query = "INSERT INTO status (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all statuses
    public function findAll() {
        $query = "SELECT * FROM status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get status by id
    public function findOne($id) {
        $query = "SELECT * FROM status WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $status = $stmt->fetch(PDO::FETCH_ASSOC);
        return $status ? $status : null;
    }

    // Update status by id
    public function update($id, $data) {
        $query = "UPDATE status SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete status by id
    public function remove($id) {
        $query = "DELETE FROM status WHERE id = :id";
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
$status = new Status($db);

if ($segments[0] !== 'status' && $segments[0] !== 'statuses') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $status->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create status']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $status->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $status->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Status not found']);
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
            $result = $status->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update status']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($status->remove($id)) {
                echo json_encode(['message' => 'Status deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete status']);
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
