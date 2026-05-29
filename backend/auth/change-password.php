<?php

session_start();

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {

    header(
    "Location: ../../frontend/auth/login.html"
    );

    exit();

}

$user_id = $_SESSION['user_id'];

$current_password =
trim($_POST['current_password']);

$new_password =
trim($_POST['new_password']);

$confirm_password =
trim($_POST['confirm_password']);

if (

    empty($current_password) ||
    empty($new_password) ||
    empty($confirm_password)

) {

    die("All fields are required.");

}

if ($new_password !== $confirm_password) {

    die("Passwords do not match.");

}

$pattern =
"/^(?=.*[A-Z])(?=.*\d).{8,}$/";

if (!preg_match($pattern, $new_password)) {

    die(
    "Password must contain 8 characters, 1 uppercase letter, and 1 number."
    );

}

$sql =
"SELECT password
FROM users
WHERE id = ?
LIMIT 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (
!password_verify(
$current_password,
$user['password']
)
) {

    die("Current password is incorrect.");

}

$new_hashed_password =
password_hash(
$new_password,
PASSWORD_DEFAULT
);

$update_sql =
"UPDATE users
SET password = ?
WHERE id = ?";

$stmt = $conn->prepare($update_sql);

$stmt->bind_param(
"si",
$new_hashed_password,
$user_id
);

if ($stmt->execute()) {

    echo "
    <script>

    alert('Password changed successfully');

    window.location.href =
    '../../frontend/student/profile.php';

    </script>
    ";

} else {

    die("Password update failed.");

}

?>