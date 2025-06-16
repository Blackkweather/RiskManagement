<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class Risk {
    private $conn;
    private $table_name = "risk";

    public function __construct($db) {
        $this->conn = $db;
    }

    private function impact_converter($impact) {
        $map = [5, 15, 35, 70, 125, 200];
        return $map[$impact] ?? 0;
    }

    private function frequency_converter($frequency) {
        $map = [1, 2, 3, 4, 5, 6];
        return $map[$frequency] ?? 0;
    }

    private function calcEval($dto) {
        $value = $this->impact_converter($dto->peopleImpact) +
                 $this->impact_converter($dto->reputationImpact) +
                 $this->impact_converter($dto->activityImpact) +
                 $this->impact_converter($dto->legalImpact) +
                 $this->impact_converter($dto->financialImpact);
        $finalValue = $value * $this->frequency_converter($dto->frequency);
        return $finalValue;
    }

    // Create a new risk
    public function create($data) {
        $evaluation = $this->calcEval($data);
        $query = "INSERT INTO risk (name, description, details, cause, frequency, financialImpact, legalImpact, activityImpact, peopleImpact, reputationImpact, existantDb, active, brutCriticality, evaluation, netCriticality, activityId, entityId, strategicObjectiveId, operationalObjectiveId, riskFamilyId) 
                  VALUES (:name, :description, :details, :cause, :frequency, :financialImpact, :legalImpact, :activityImpact, :peopleImpact, :reputationImpact, :existantDb, :active, 6000, :evaluation, (6000 - :evaluation), :activityId, :entityId, :strategicObjectiveId, :operationalObjectiveId, :riskFamilyId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':details', $data->details);
        $stmt->bindParam(':cause', $data->cause);
        $stmt->bindParam(':frequency', $data->frequency, PDO::PARAM_INT);
        $stmt->bindParam(':financialImpact', $data->financialImpact, PDO::PARAM_INT);
        $stmt->bindParam(':legalImpact', $data->legalImpact, PDO::PARAM_INT);
        $stmt->bindParam(':activityImpact', $data->activityImpact, PDO::PARAM_INT);
        $stmt->bindParam(':peopleImpact', $data->peopleImpact, PDO::PARAM_INT);
        $stmt->bindParam(':reputationImpact', $data->reputationImpact, PDO::PARAM_INT);
        $stmt->bindParam(':existantDb', $data->existantDb, PDO::PARAM_BOOL);
        $stmt->bindParam(':active', $data->active, PDO::PARAM_BOOL);
        $stmt->bindParam(':evaluation', $evaluation, PDO::PARAM_INT);
        $stmt->bindParam(':activityId', $data->activityId, PDO::PARAM_INT);
        $stmt->bindParam(':entityId', $data->entityId, PDO::PARAM_INT);
        $stmt->bindParam(':strategicObjectiveId', $data->strategicObjectiveId ?? null, PDO::PARAM_INT);
        $stmt->bindParam(':operationalObjectiveId', $data->operationalObjectiveId ?? null, PDO::PARAM_INT);
        $stmt->bindParam(':riskFamilyId', $data->riskFamilyId ?? null, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->findOne($this->conn->lastInsertId());
        }
        return null;
    }

    // Get all risks with related data
    public function findAll() {
        $query = "SELECT r.*, a.name as activityName, o.name as operationalObjectiveName, s.name as strategicObjectiveName, rf.name as riskFamilyName, e.name as entityName
                  FROM risk r
                  LEFT JOIN activity a ON r.activityId = a.id
                  LEFT JOIN operational_objective o ON r.operationalObjectiveId = o.id
                  LEFT JOIN strategic_objective s ON r.strategicObjectiveId = s.id
                  LEFT JOIN risk_family rf ON r.riskFamilyId = rf.id
                  LEFT JOIN entity e ON r.entityId = e.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get risks by project id (via entity.projectId)
    public function getByProject($projectId) {
        $query = "SELECT r.*, a.name as activityName, o.name as operationalObjectiveName, s.name as strategicObjectiveName, rf.name as riskFamilyName, e.name as entityName
                  FROM risk r
                  LEFT JOIN activity a ON r.activityId = a.id
                  LEFT JOIN operational_objective o ON r.operationalObjectiveId = o.id
                  LEFT JOIN strategic_objective s ON r.strategicObjectiveId = s.id
                  LEFT JOIN risk_family rf ON r.riskFamilyId = rf.id
                  LEFT JOIN entity e ON r.entityId = e.id
                  WHERE e.projectId = :projectId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get risks by entity id
    public function getByEntity($entityId) {
        $query = "SELECT r.*, a.name as activityName, o.name as operationalObjectiveName, s.name as strategicObjectiveName, rf.name as riskFamilyName, e.name as entityName
                  FROM risk r
                  LEFT JOIN activity a ON r.activityId = a.id
                  LEFT JOIN operational_objective o ON r.operationalObjectiveId = o.id
                  LEFT JOIN strategic_objective s ON r.strategicObjectiveId = s.id
                  LEFT JOIN risk_family rf ON r.riskFamilyId = rf.id
                  LEFT JOIN entity e ON r.entityId = e.id
                  WHERE r.entityId = :entityId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':entityId', $entityId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get risks by process id (via activity.processId)
    public function getByProcess($processId) {
        $query = "SELECT r.*, a.name as activityName, o.name as operationalObjectiveName, s.name as strategicObjectiveName, rf.name as riskFamilyName, e.name as entityName
                  FROM risk r
                  LEFT JOIN activity a ON r.activityId = a.id
                  LEFT JOIN operational_objective o ON r.operationalObjectiveId = o.id
                  LEFT JOIN strategic_objective s ON r.strategicObjectiveId = s.id
                  LEFT JOIN risk_family rf ON r.riskFamilyId = rf.id
                  LEFT JOIN entity e ON r.entityId = e.id
                  WHERE a.processId = :processId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':processId', $processId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get risks by activity id
    public function getByActivity($activityId) {
        $query = "SELECT r.*, a.name as activityName, o.name as operationalObjectiveName, s.name as strategicObjectiveName, rf.name as riskFamilyName, e.name as entityName
                  FROM risk r
                  LEFT JOIN activity a ON r.activityId = a.id
                  LEFT JOIN operational_objective o ON r.operationalObjectiveId = o.id
                  LEFT JOIN strategic_objective s ON r.strategicObjectiveId = s.id
                  LEFT JOIN risk_family rf ON r.riskFamilyId = rf.id
                  LEFT JOIN entity e ON r.entityId = e.id
                  WHERE r.activityId = :activityId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':activityId', $activityId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get risk by id
    public function findOne($id) {
        $query = "SELECT r.*, a.name as activityName, o.name as operationalObjectiveName, s.name as strategicObjectiveName, rf.name as riskFamilyName, e.name as entityName
                  FROM risk r
                  LEFT JOIN activity a ON r.activityId = a.id
                  LEFT JOIN operational_objective o ON r.operationalObjectiveId = o.id
                  LEFT JOIN strategic_objective s ON r.strategicObjectiveId = s.id
                  LEFT JOIN risk_family rf ON r.riskFamilyId = rf.id
                  LEFT JOIN entity e ON r.entityId = e.id
                  WHERE r.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $risk = $stmt->fetch(PDO::FETCH_ASSOC);
        return $risk ? $risk : null;
    }

    // Update risk by id
    public function update($id, $data) {
        $evaluation = $this->calcEval($data);
        $query = "UPDATE risk SET name = :name, description = :description, details = :details, cause = :cause, frequency = :frequency, existantDb = :existantDb, financialImpact = :financialImpact, legalImpact = :legalImpact, reputationImpact = :reputationImpact, activityImpact = :activityImpact, peopleImpact = :peopleImpact, active = :active, evaluation = :evaluation, netCriticality = :netCriticality, riskFamilyId = :riskFamilyId WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data->name);
        $stmt->bindParam(':description', $data->description);
        $stmt->bindParam(':details', $data->details);
        $stmt->bindParam(':cause', $data->cause);
        $stmt->bindParam(':frequency', $data->frequency, PDO::PARAM_INT);
        $stmt->bindParam(':existantDb', $data->existantDb, PDO::PARAM_BOOL);
        $stmt->bindParam(':financialImpact', $data->financialImpact, PDO::PARAM_INT);
        $stmt->bindParam(':legalImpact', $data->legalImpact, PDO::PARAM_INT);
        $stmt->bindParam(':reputationImpact', $data->reputationImpact, PDO::PARAM_INT);
        $stmt->bindParam(':activityImpact', $data->activityImpact, PDO::PARAM_INT);
        $stmt->bindParam(':peopleImpact', $data->peopleImpact, PDO::PARAM_INT);
        $stmt->bindParam(':active', $data->active, PDO::PARAM_BOOL);
        $stmt->bindParam(':evaluation', $evaluation, PDO::PARAM_INT);
        $netCriticality = 6000 - $evaluation;
        $stmt->bindParam(':netCriticality', $netCriticality, PDO::PARAM_INT);
        $stmt->bindParam(':riskFamilyId', $data->riskFamilyId ?? null, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $this->findOne($id);
        }
        return null;
    }

    // Delete risk by id
    public function remove($id) {
        $query = "DELETE FROM risk WHERE id = :id";
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
$risk = new Risk($db);

if ($segments[0] !== 'risk' && $segments[0] !== 'risks') {
    http_response_code(404);
    echo json_encode(['message' => 'Not Found']);
    exit;
}

switch ($method) {
    case 'POST':
        if (count($segments) == 1) {
            $data = json_decode(file_get_contents("php://input"));
            $result = $risk->create($data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to create risk']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'GET':
        if (count($segments) == 1) {
            $result = $risk->findAll();
            echo json_encode($result);
        } elseif (count($segments) == 3) {
            $id = intval($segments[2]);
            if ($segments[1] === 'project') {
                $result = $risk->getByProject($id);
                echo json_encode($result);
            } elseif ($segments[1] === 'entity') {
                $result = $risk->getByEntity($id);
                echo json_encode($result);
            } elseif ($segments[1] === 'process') {
                $result = $risk->getByProcess($id);
                echo json_encode($result);
            } elseif ($segments[1] === 'activity') {
                $result = $risk->getByActivity($id);
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Not Found']);
            }
        } elseif (count($segments) == 2) {
            $id = intval($segments[1]);
            $result = $risk->findOne($id);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Risk not found']);
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
            $result = $risk->update($id, $data);
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to update risk']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not Found']);
        }
        break;
    case 'DELETE':
        if (count($segments) == 2) {
            $id = intval($segments[1]);
            if ($risk->remove($id)) {
                echo json_encode(['message' => 'Risk deleted']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Failed to delete risk']);
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
