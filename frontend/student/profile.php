<?php

require_once __DIR__ . '/../../backend/auth/session-check.php';

$full_name = $_SESSION['full_name'];
$email = $_SESSION['email'];

function maskEmail($email){

    $parts = explode("@", $email);

    $name = $parts[0];

    return substr($name, 0, 4) .
    "**@" .
    $parts[1];

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Profile Settings
</title>

<link rel="stylesheet"
href="../assets/style.css">

</head>

<body class="auth-body">

<div class="profile-container">

    <div class="top">

        <h1>
            Profile Settings
        </h1>

        <div class="profile-icon">
            👤
        </div>

    </div>

    <p>
        Manage your account settings
    </p>

    <form
    action="../../backend/auth/change-password.php"
    method="POST">

        <label>Email</label>

        <input
        type="text"
        value="<?php echo htmlspecialchars(maskEmail($email)); ?>"
        readonly>

        <label>
            Current Password
        </label>

        <input
        type="password"
        name="current_password"
        placeholder="Current Password"
        required>

        <label>
            New Password
        </label>

        <input
        type="password"
        name="new_password"
        placeholder="New Password"
        required>

        <label>
            Confirm Password
        </label>

        <input
        type="password"
        name="confirm_password"
        placeholder="Confirm Password"
        required>

        <div class="guide">

            Password must contain:
            <br>
            • 8+ characters
            <br>
            • 1 uppercase letter
            <br>
            • 1 number

        </div>

    <div class="profile-actions">

        <button type="submit">
            Change Password
        </button>

        <button
        type="button"
        onclick="goBack()">

            Back to Dashboard

        </button>

    </div>

</div>

<script>

function goBack(){

    window.location.href =
    "dashboard.php";

}

</script>

</body>
</html>