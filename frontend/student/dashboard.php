<?php

require_once __DIR__ . '/../../backend/auth/session-check.php';

$full_name = $_SESSION['full_name'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Study Planner Dashboard</title>

<link rel="stylesheet" href="../assets/style.css">

</head>

<body class="dashboard-body">

<div class="sidebar">

    <div>

        <div class="logo">
            Study <span>Planner</span>
        </div>

        <ul class="menu">

            <li class="active">
                Dashboard
            </li>

            <li>
                Tasks
            </li>

            <li>
                Schedule
            </li>

            <li>
                Categories
            </li>

            <li>
                Notifications
            </li>

            <li onclick="logout()">
                Logout
            </li>

        </ul>

    </div>

    <div class="bottom-box">

        "The secret of getting ahead is getting started."

        <br><br>

        — Mark Twain

    </div>

</div>

<div class="main">

    <div class="topbar">

        <div class="welcome">

            <h1>
                Welcome,
                <?php echo htmlspecialchars($full_name); ?>!
            </h1>

            <p>
                Let's make today productive.
            </p>

        </div>

        <div class="profile-box" onclick="openProfile()">

            <div class="profile-icon">
                👤
            </div>

            <div class="profile-text">
                <p>User</p>
                <p>Settings</p>
            </div>

        </div>

    </div>

    <div class="cards">

        <div class="card">
            <p>Tasks Due Today</p>
            <h2>0</h2>
        </div>

        <div class="card">
            <p>Completed This Week</p>
            <h2>0</h2>
        </div>

        <div class="card">
            <p>Overdue Tasks</p>
            <h2>0</h2>
        </div>

        <div class="card">
            <p>Completion Rate</p>
            <h2>0%</h2>
        </div>

    </div>

</div>

<script>

function openProfile(){

    window.location.href = "profile.php";

}

function logout(){

    window.location.href =
    "../../backend/auth/logout.php";

}

</script>

</body>
</html>