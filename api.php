<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Database Connection Settings
$host = 'localhost';
$db_name = 'jaffna_tourism';
$username = 'root'; // Default XAMPP username
$password = '';     // Default XAMPP password is empty

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Get raw JSON payload
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- SIGNUP LOGIC ---
    if ($action === 'signup') {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = trim($input['password'] ?? '');

        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(["status" => "error", "message" => "All fields are required."]);
            exit;
        }

        // Check if email already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $checkStmt->execute(['email' => $email]);
        
        if ($checkStmt->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "Email is already registered."]);
            exit;
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user
        $insertStmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $success = $insertStmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        if ($success) {
            echo json_encode(["status" => "success", "message" => "Registration successful! Please login."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to register user."]);
        }
    }

    // --- LOGIN LOGIC ---
    elseif ($action === 'login') {
        $email = trim($input['email'] ?? '');
        $password = trim($input['password'] ?? '');

        if (empty($email) || empty($password)) {
            echo json_encode(["status" => "error", "message" => "Please fill in all fields."]);
            exit;
        }

        // Fetch user records by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password hash match
        if ($user && password_verify($password, $user['password'])) {
            echo json_encode([
                "status" => "success",
                "message" => "Welcome back to Jaffna Tourism!",
                "user" => [
                    "name" => $user['name'],
                    "email" => $user['email']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid action specified."]);
    }
}
?>