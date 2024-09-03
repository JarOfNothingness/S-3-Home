<?php 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include('headeradmin.php');
// Include the database connection
include_once("../LoginRegisterAuthentication/connection.php");

// Check if the connection is successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}



// Query for users waiting for approval
$pendingUsersQuery = "SELECT userid, name, username FROM user WHERE status = 'pending'";
$pendingUsersResult = mysqli_query($connection, $pendingUsersQuery);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
        }

        header {
            background-color: #0047ab;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 28px;
        }

        .sidebar {
            width: 60px;
            background-color: #333;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            transition: width 0.3s;
            overflow-x: hidden;
            white-space: nowrap;
        }

        .sidebar:hover {
            width: 250px;
        }

        .sidebar a {
            color: #fff;
            padding: 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .sidebar a span {
            display: none;
            margin-left: 10px;
        }

        .sidebar:hover a span {
            display: inline;
        }

        .main-content {
            margin-left: 70px;
            padding: 20px;
            transition: margin-left 0.3s, width 0.3s;
        }

        .sidebar:hover ~ .main-content {
            margin-left: 250px;
        }

    
        .pending-users {
            margin-top: 10px;
        }

        .pending-users h3 {
            margin-bottom: 15px;
            color: #0047ab;
        }

        .pending-users table {
            width: 90%;
            border-collapse: collapse;
        }

        .pending-users table, .pending-users th, .pending-users td {
            border: 2px solid #ddd;
        }

        .pending-users th, .pending-users td {
            padding: 10px;
            text-align: left;
        }

        .pending-users th {
            background-color: #f4f4f4;
        }

        .btn-approve, .btn-disapprove {
            padding: 5px 10px;
            color: #fff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            margin: 0 5px;
        }

        .btn-approve {
            background-color: #28a745;
        }

        .btn-approve:hover {
            background-color: #218838;
        }

        .btn-disapprove {
            background-color: #dc3545;
        }

        .btn-disapprove:hover {
            background-color: #c82333;
        }

        

   border-radius: 8px;
            bo
        .alert {
            margin-top: 20px;
        }
    </style>

<div class="pending-users">
            <h3>Pending User Approvals</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($pendingUsersResult)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td>
                            <a href="admin_approval.php?action=approve&userid=<?php echo htmlspecialchars($row['userid']); ?>" class="btn-approve">Approve</a>
                            <a href="admin_approval.php?action=disapprove&userid=<?php echo htmlspecialchars($row['userid']); ?>" class="btn-disapprove">Disapprove</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>