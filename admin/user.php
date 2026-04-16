<?php
session_start();
include '../db.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle Add User
if (isset($_POST['add_user'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO users (name, email, phone, password, role) 
                         VALUES ('$name', '$email', '$phone', '$password', 'user')");

    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.onload = function() {
            Swal.fire({
                title: '✅ User Added!',
                icon: 'success',
                confirmButtonColor: '#007bff'
            }).then(() => {
                window.location.href = 'users.php';
            });
        };
    </script>
    <?php
    exit();
}

// Handle Delete User
if (isset($_GET['delete'])) {
    $userId = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$userId");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.onload = function() {
            Swal.fire({
                title: '❌ User Removed!',
                icon: 'success',
                confirmButtonColor: '#007bff'
            }).then(() => {
                window.location.href = 'users.php';
            });
        };
    </script>
    <?php
    exit();
}

// Fetch users
$users = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Animated Gradient Background */
        body {
            font-family: Arial, sans-serif;
            margin: 0; 
            padding: 20px;
            background: linear-gradient(-45deg, #143438ff, #3a6cb7ff, #283847ff, #0b201bff);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1 {
            text-align: center;
            color: #151515ff;
            margin-bottom: 25px;
            text-shadow: 0 0 12px rgba(0,123,255,0.4);
            animation: fadeInTitle 1s ease-out;
        }
        @keyframes fadeInTitle {
            from { opacity: 0; transform: translateY(-20px);}
            to { opacity: 1; transform: translateY(0);}
        }

        /* Form Container */
        .form-container {
            max-width: 500px;
            margin: 0 auto 40px auto;
            background: rgba(255,255,255,0.9);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,123,255,0.2), 0 8px 20px rgba(0,0,0,0.08);
            backdrop-filter: blur(6px);
        }
        .form-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
        }
        .form-container input:focus {
            border-color: #007bff;
            box-shadow: 0 0 15px rgba(0,123,255,0.5);
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 12px rgba(0,123,255,0.4);
        }
        .form-container button:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 0 18px rgba(0,123,255,0.6);
        }

        /* Table Styling */
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: rgba(255,255,255,0.9);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,123,255,0.15), 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        table th {
            background: #007bff;
            color: white;
            text-shadow: 0 0 10px rgba(255,255,255,0.6);
        }
        table tr:hover {
            background: #f1faff;
            transform: scale(1.02);
            transition: all 0.25s ease-in-out;
            box-shadow: 0 0 12px rgba(0,123,255,0.2);
        }
        .delete-btn {
            background: #e74c3c;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(231,76,60,0.3);
        }
        .delete-btn:hover {
            background: #c0392b;
            transform: scale(1.05);
            box-shadow: 0 0 14px rgba(231,76,60,0.5);
        }

        /* Back Link */
        a.back {
            display: block;
            width: 180px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background: #007bff;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s ease;
            box-shadow: 0 0 12px rgba(0,123,255,0.4);
        }
        a.back:hover {
            background: #0056b3;
            transform: scale(1.05);
            box-shadow: 0 0 18px rgba(0,123,255,0.6);
        }
    </style>
</head>
<body>

<h1>👥 Manage Users</h1>

<div class="form-container">
    <form method="post">
        <input type="text" name="name" placeholder="User Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="add_user">Add User</button>
    </form>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Action</th>
    </tr>
    <?php while ($user = mysqli_fetch_assoc($users)) : ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
            <td>
                <a class="delete-btn" href="?delete=<?php echo $user['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a class="back" href="home.php">⬅ Back to Home</a>

</body>
</html>