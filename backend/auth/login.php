<?php

session_start();

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid Request");
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

if (
    empty($email) ||
    empty($password)
) {
    die("Email and password are required.");
}

$sql = "
SELECT *
FROM users
WHERE email = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

if (!password_verify(
    $password,
    $user['password']
)) {
    die("Invalid password.");
}

$_SESSION['user_id'] = $user['id'];

$_SESSION['full_name'] = $user['full_name'];

$_SESSION['email'] = $user['email'];

$_SESSION['role'] = $user['role'];

header(
    "Location: ../../frontend/student/dashboard.html"
);

exit();

?>