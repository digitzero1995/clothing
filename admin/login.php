<?php
session_start();
include __DIR__ . '/../db.php'; // Safe include path

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check for admin user
    $sql = "SELECT * FROM users WHERE email='$email' AND role='admin' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];

        // Redirect to home page
        header("Location: /shop/index.php");
        exit();
    } else {
        $errorMsg = "Invalid admin email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="/shop/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4f9ab1, #0c0c0c);
            animation: fadeIn 1.2s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .form-container {
            background: rgba(255,255,255,0.95);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            width: 360px;
            text-align: center;
            animation: slideUp 0.8s ease-out;
            position: relative;
            z-index: 1;
        }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .form-container h1 {
            margin-bottom: 30px;
            color: #333;
            animation: fadeInTitle 1s ease;
        }
        @keyframes fadeInTitle { from { opacity: 0; transform: scale(0.9);} to { opacity: 1; transform: scale(1);} }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 14px 15px;
            border-radius: 10px;
            border: 2px solid #ccc;
            outline: none;
            font-size: 16px;
            margin-bottom: 20px;
            transition: all 0.4s ease;
        }
        input:focus {
            border-color: #2575fc;
            box-shadow: 0 0 15px rgba(37,117,252,0.4);
            transform: scale(1.02);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #2575fc, #b28fd6);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        .form-error {
            color: #e74c3c;
            margin-bottom: 15px;
            font-weight: bold;
            animation: shake 0.3s ease;
        }
        @keyframes shake {
            0% { transform: translateX(0);}
            25% { transform: translateX(-5px);}
            50% { transform: translateX(5px);}
            75% { transform: translateX(-5px);}
            100% { transform: translateX(0);}
        }

        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBG 20s linear infinite;
            z-index: 0;
            top: 0;
            left: -50%;
        }
        @keyframes moveBG {
            from { transform: translateY(0) translateX(0); }
            to { transform: translateY(-500px) translateX(500px); }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Admin Login</h1>
        <?php if ($errorMsg): ?>
            <p class="form-error"><?php echo htmlspecialchars($errorMsg); ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Admin Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>