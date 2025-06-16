<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../config/database.php';

class Auth {
    private $conn;
    private $table_name = "user";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Simple login function: verify email and password (hashed)
    public function login($email, $password) {
        $query = "SELECT * FROM user WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
            exit;
        }
        // Verify password hash - assuming password stored hashed with password_hash
        if (!password_verify($password, $user['hash'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid credentials']);
            exit;
        }
        // Generate a simple token (for demo, in real app use JWT or session)
        $token = bin2hex(random_bytes(16));
        // Here you would store the token in DB or session for validation
        // For demo, just return token and user info without hash
        unset($user['hash']);
        echo json_encode(['token' => $token, 'user' => $user]);
    }
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"));
if (!isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(['message' => 'Email and password required']);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);
$auth->login($data->email, $data->password);
?>
