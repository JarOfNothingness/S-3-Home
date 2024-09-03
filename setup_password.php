<?php
session_start();
include("../LoginRegisterAuthentication/connection.php");

if (!isset($_GET['userid'])) {
    header("Location: login.php");
    exit();
}
$success_message = '';
$error_message = '';

$userid = $_GET['userid'];

if (isset($_POST['set_password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        // Hash the new password
   

        // Update the user password and set status to 'active'
        $update_query = "UPDATE user SET password = ?, status = 'active' WHERE userid = ?";
        $stmt = $connection->prepare($update_query);
        $stmt->bind_param("si", $password, $userid);

        if ($stmt->execute()) {
            $success_message = "Password successfully created. Redirecting...";
            // Redirect to homepage
            header("refresh:1;url=../Home/homepage.php");
            exit();
        } else {
            echo "<p>Error updating password.</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Passwords do not match.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #343a40;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
            color: #495057;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        .alert {
            text-align: center;
        }
    </style>
    <script>
        function validatePasswordLength() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const minLength = 8;

            if (password.value.length < minLength) {
                alert('Password must be at least ' + minLength + ' characters long.');
                return false;
            }

            if (password.value !== confirmPassword.value) {
                alert('Passwords do not match.');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Setup Your Password</h2>

        <?php if (isset($success_message) && $success_message): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message) && $error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validatePasswordLength()">
            <div class="mb-3">
                <label for="password" class="form-label">Create Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Create Password" minlength="8" maxlength="20" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" minlength="8" maxlength="20"required>
            </div>
            <button type="submit" name="set_password" class="btn btn-primary">Set Password</button>
        </form>
    </div>
</body>
</html>
