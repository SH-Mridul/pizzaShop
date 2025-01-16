<?php 
    session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h3 {
            margin-bottom: 20px;
        }
        .login-container .btn {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h3 class="text-center">Admin Login</h3>
    <form action="admin_login_action.php" method="POST">

        <div class="mx-auto text-center">
            <span class="text-danger text-center">
                <?php 
                    if(isset($_SESSION['error'])){ 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']); 
                    }
                ?>
            </span>

            <span class="text-success text-center">
                <?php 
                    if(isset($_SESSION['success'])){ 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']); 
                    }
                ?>
            </span>
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control form-control-sm" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control form-control-sm" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Login</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
