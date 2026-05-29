<?php

session_start();

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid Request");
}

$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);

if (
    empty($full_name) ||
    empty($email) ||
    empty($password)
) {
    die("All fields are required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

$check_sql = "SELECT id FROM users WHERE email = ? LIMIT 1";

$stmt = $conn->prepare($check_sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("Email already exists.");
}

$hashed_password = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$verification_token = bin2hex(random_bytes(32));

$sql = "
INSERT INTO users (
    full_name,
    email,
    password,
    verification_token
)
VALUES (?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssss",
    $full_name,
    $email,
    $hashed_password,
    $verification_token
);

if ($stmt->execute()) {

    header(
        "Location: ../../frontend/auth/login.html"
    );

    exit();

} else {

    die("Registration Failed");

}

?>