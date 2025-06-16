<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class RiskControl {
    private $conn;
    private $table_name = "risk_control";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new risk control
    public function create($data) {
        $query = "INSERT INTO risk_control (name, meanIndicator, meanIntegrated, meanManualPost, meanManualPre, meanOrganization, meanReference, meanProgrammed, evaluation, proposedControl, proposedControlDescription, riskId) 
                  VALUES (:name, :meanIndicator, :meanIntegrated, :meanManualPost, :meanManualPre, :meanOrganization, :meanReference, :meanProgrammed, :evaluation, :proposedControl, :proposedControlDescription, :riskId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':meanIndicator', $data->meanIndicator);
        $stmt->bindParam(':meanIntegrated', $data->meanIntegrated);
        $stmt->bindParam(':meanManualPost', $data->meanManualPost);
        $stmt->bindParam(':meanManualPre', $data->meanManualPre);
        $stmt->bindParam(':meanOrganization', $data->meanOrganization);
        $stmt->bindParam(':meanReference', $data->meanReference);
        $stmt->bindParam(':meanProgrammed', $data->meanProgrammed);
        $stmt->bindParam(':evaluation', $data->evaluation);
        $stmt->bindParam(':proposedControl', $data->proposedControl);
        $stmt->bindParam(':proposedControlDescription', $data->proposedControlDescription);
        $stmt->bindParam(':riskId', $data->riskId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all risk controls with related risk
    public function findAll() {
        $query = "SELECT rc.*, r.name as riskName FROM risk_control rc LEFT JOIN risk r ON rc.riskId = r.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get risk control by id
    public function findOne($id) {
        $query = "SELECT rc.*, r.name as riskName FROM risk_control rc LEFT JOIN risk r ON rc.riskId = r.id WHERE rc.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $rc = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rc ? $rc : null;
    }

    // Update risk control by id
    public function update($id, $data) {
        $query = "UPDATE risk_control SET name = :name, meanIndicator = :meanIndicator, meanIntegrated = :meanIntegrated, meanManualPost = :meanManualPost, meanManualPre = :meanManualPre, meanOrganization = :meanOrganization, meanReference = :meanReference, meanProgrammed = :meanProgrammed, evaluation = :evaluation, proposedControl = :proposedControl, proposedControlDescription = :proposedControlDescription WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':meanIndicator', $data->meanIndicator);
        $stmt->bindParam(':meanIntegrated', $data->meanIntegrated);
        $stmt->bindParam(':meanManualPost', $data->meanManualPost);
        $stmt->bindParam(':meanManualPre', $data->meanManualPre);
        $stmt->bindParam(':meanOrganization', $data->meanOrganization);
        $stmt->bindParam(':meanReference', $data->meanReference);
        $stmt->bindParam(':meanProgrammed', $data->meanProgrammed);
        $stmt->bindParam(':evaluation', $data->evaluation);
        $stmt->bindParam(':proposedControl', $data->proposedControl);
        $stmt->bindParam(':proposedControlDescription', $data->proposedControlDescription);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete risk control by id
    public function remove($id) {
        $query = "DELETE FROM risk_control WHERE id = :id";
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
$riskControl = new RiskControl($db);

if ($segments[0] !== 'risk-control' && $segments[0] !== 'risk-controls') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $riskControl->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create risk control']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $riskControl->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $riskControl->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Risk control not found']);
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
            $result = $riskControl->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update risk control']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($riskControl->remove($id)) {
                echo json_encode(['message' => 'Risk control deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete risk control']);
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
