<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class Project {
    private $conn;
    private $table_name = "project";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new project
    public function create($data) {
        $query = "INSERT INTO project (name, active, clientId) VALUES (:name, :active, :clientId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':active', $data->active, PDO::PARAM_BOOL);
        $stmt->bindParam(':clientId', $data->clientId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all projects with related data
    public function findAll() {
        $query = "SELECT p.*, c.denomination as clientDenomination FROM project p LEFT JOIN client_profile c ON p.clientId = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // For simplicity, related domaines, entities, users can be fetched separately if needed
        return $projects;
    }

    // Get projects by client id
    public function getByClient($clientId) {
        $query = "SELECT p.*, c.denomination as clientDenomination FROM project p LEFT JOIN client_profile c ON p.clientId = c.id WHERE p.clientId = :clientId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get project by id
    public function findOne($id) {
        $query = "SELECT p.*, c.denomination as clientDenomination FROM project p LEFT JOIN client_profile c ON p.clientId = c.id WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        return $project ? $project : null;
    }

    // Update project by id
    public function update($id, $data) {
        $query = "UPDATE project SET name = :name, active = :active WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':active', $data->active, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Assign users to project (externalUsers and internalUsers)
    public function assignUsers($id, $data) {
        // Assuming join tables: project_external_users (projectId, userId), project_internal_users (projectId, userId)
        try {
            $this->conn->beginTransaction();

            // Clear existing external users
            $stmt = $this->conn->prepare("DELETE FROM project_external_users WHERE projectId = :projectId");
            $stmt->bindParam(':projectId', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Clear existing internal users
            $stmt = $this->conn->prepare("DELETE FROM project_internal_users WHERE projectId = :projectId");
            $stmt->bindParam(':projectId', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Insert new external users
            if (!empty($data->externalUserIds)) {
                $stmt = $this->conn->prepare("INSERT INTO project_external_users (projectId, userId) VALUES (:projectId, :userId)");
                foreach ($data->externalUserIds as $userId) {
                    $stmt->bindParam(':projectId', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            // Insert new internal users
            if (!empty($data->internalUserIds)) {
                $stmt = $this->conn->prepare("INSERT INTO project_internal_users (projectId, userId) VALUES (:projectId, :userId)");
                foreach ($data->internalUserIds as $userId) {
                    $stmt->bindParam(':projectId', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            $this->conn->commit();
            return $this->findOne($id);
        } catch (Exception $e) {
            $this->conn->rollBack();
            http_response_code(500);
            return json_encode(['message' => 'Failed to assign users: ' . $e->getMessage()]);
        }
    }

    // Delete project by id
    public function remove($id) {
        $query = "DELETE FROM project WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

$database = new Database();
$db = $database->getConnection();
$project = new Project($db);

// Simple routing based on method and path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', trim($uri, '/'));

if ($segments[0] !== 'project' && $segments[0] !== 'projects') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            // Create project
            $data = json_decode(file_get_contents("php://input"));
            $result = $project->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create project']);
            }
        } elseif (count($segments) == 3 && $segments[1] === 'assign-users') {
            // Assign users
            $id = intval($segments[2]);
            $data = json_decode(file_get_contents("php://input"));
            $result = $project->assignUsers($id, $data);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            // Get all projects
            $result = $project->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 2 && $segments[1] === 'client') {
            // Get projects by client id
            $clientId = intval($segments[2]);
            $result = $project->getByClient($clientId);
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            // Get project by id
            $id = intval($segments[1]);
            $result = $project->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Project not found']);
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
            $result = $project->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update project']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($project->remove($id)) {
                echo json_encode(['message' => 'Project deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete project']);
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
