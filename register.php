<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'db_connection.php';

$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $data->first_name;
    $last_name = $data->last_name;
    $phone_number = $data->phone_number;
    $email = $data->email;
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    
    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = '$email'";
    $email_result = $conn->query($check_email);
    
    if ($email_result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email already exists"]);
        exit;
    }
    
    // Check if phone number already exists
    $check_phone = "SELECT id FROM users WHERE phone_number = '$phone_number'";
    $phone_result = $conn->query($check_phone);
    
    if ($phone_result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Phone number already exists"]);
        exit;
    }
    
    // Insert new user
    $sql = "INSERT INTO users (first_name, last_name, phone_number, email, password) 
            VALUES ('$first_name', '$last_name', '$phone_number', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Registration successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Registration failed: " . $conn->error]);
    }
}
?>