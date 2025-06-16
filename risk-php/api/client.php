<?php
// Set headers at the very beginning
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config/database.php';

class Client {
    private $conn;
    private $table_name = "client_profile";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data, $isForm = false) {
        $query = "INSERT INTO client_profile (denomination, judicial, sector, code, config, appetency_active) VALUES (:denomination, :judicial, :sector, :code, :config, :appetency_active)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':denomination', $data->denomination);
        $stmt->bindParam(':judicial', $data->judicial);
        $stmt->bindParam(':sector', $data->sector);
        $stmt->bindParam(':code', $data->code);
        $stmt->bindParam(':config', $data->config);
        $stmt->bindParam(':appetency_active', $data->appetency_active ?? $data->appetencyActive ?? false, PDO::PARAM_BOOL);
        if ($stmt->execute()) {
            $lastInsertId = $this->conn->lastInsertId();
            if ($isForm) {
                header("Location: ../view_client.php?id=" . $lastInsertId . "&success=created");
                exit();
            }
            return json_encode(['message' => 'Client created successfully', 'id' => $lastInsertId]);
        }
        http_response_code(500);
        if ($isForm) {
            header("Location: ../add_client.php?error=create_failed");
            exit();
        }
        return json_encode(['message' => 'Failed to create client']);
    }

    public function findAll() {
        $query = "SELECT * FROM client_profile";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($clients);
    }

    public function findOne($id) {
        $query = "SELECT * FROM client_profile WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($client) {
            return json_encode($client);
        }
        http_response_code(404);
        return json_encode(['message' => 'Client not found']);
    }

    public function update($id, $data, $isForm = false) {
        $query = "UPDATE client_profile SET denomination = :denomination, judicial = :judicial, sector = :sector, code = :code, config = :config, appetency_active = :appetency_active WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':denomination', $data->denomination);
        $stmt->bindParam(':judicial', $data->judicial);
        $stmt->bindParam(':sector', $data->sector);
        $stmt->bindParam(':code', $data->code);
        $stmt->bindParam(':config', $data->config);
        $stmt->bindParam(':appetency_active', $data->appetency_active ?? $data->appetencyActive ?? false, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($isForm) {
                header("Location: ../view_client.php?id=" . $id . "&success=updated");
                exit();
            }
            return json_encode(['message' => 'Client updated successfully']);
        }
        http_response_code(500);
        if ($isForm) {
            header("Location: ../edit_client.php?id=" . $id . "&error=update_failed");
            exit();
        }
        return json_encode(['message' => 'Failed to update client']);
    }

    public function remove($id, $isForm = false) {
        $query = "DELETE FROM client_profile WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($isForm) {
                header("Location: ../clients.php?success=deleted");
                exit();
            }
            return json_encode(['message' => 'Client deleted successfully']);
        }
        http_response_code(500);
        if ($isForm) {
            header("Location: ../clients.php?error=delete_failed");
            exit();
        }
        return json_encode(['message' => 'Failed to delete client']);
    }
}

$request_method = $_SERVER['REQUEST_METHOD'];
$database = new Database();
$db = $database->getConnection();
$client = new Client($db);

if ($request_method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($request_method === 'POST') {
    if (!empty($_POST)) {
        // Form submission
        $data = json_decode(json_encode($_POST));
        $client->create($data, true);
    } else {
        // API JSON POST
        $data = json_decode(file_get_contents("php://input"));
        echo $client->create($data);
    }
} elseif ($request_method === 'GET') {
    if (isset($_GET['id'])) {
        echo $client->findOne(intval($_GET['id']));
    } else {
        echo $client->findAll();
    }
} elseif ($request_method === 'PATCH') {
    if (!empty($_POST)) {
        // Form submission for update
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        if ($id !== null) {
            $data = json_decode(json_encode($_POST));
            $client->update($id, $data, true);
        } else {
            header("Location: ../clients.php?error=missing_id");
            exit();
        }
    } else {
        // API JSON PATCH
        parse_str(file_get_contents("php://input"), $patch_vars);
        $id = isset($patch_vars['id']) ? intval($patch_vars['id']) : null;
        $data = json_decode(file_get_contents("php://input"));
        if ($id !== null) {
            echo $client->update($id, $data);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Client ID is required for update']);
        }
    }
} elseif ($request_method === 'DELETE') {
    if (!empty($_POST)) {
        // Form submission for delete
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        if ($id !== null) {
            $client->remove($id, true);
        } else {
            header("Location: ../clients.php?error=missing_id");
            exit();
        }
    } else {
        // API JSON DELETE
        parse_str(file_get_contents("php://input"), $delete_vars);
        $id = isset($delete_vars['id']) ? intval($delete_vars['id']) : null;
        if ($id !== null) {
            echo $client->remove($id);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Client ID is required for delete']);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
?>
