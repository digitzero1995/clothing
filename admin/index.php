<?php
session_start();
include(__DIR__ . '/../config/db.php');

$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND role='admin' LIMIT 1");
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: /shop/admin/home.php");
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
        /* Body Background & Fade-in */
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
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Form Container */
        .form-container {
            background: rgba(255,255,255,0.95);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
            width: 360px;
            text-align: center;
            animation: slideUp 0.8s ease-out;
            position: relative;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .form-container h1 {
            margin-bottom: 30px;
            color: #333;
            animation: fadeInTitle 1s ease;
        }
        @keyframes fadeInTitle {
            from { opacity: 0; transform: scale(0.9);}
            to { opacity: 1; transform: scale(1);}
        }

        /* Input Field Effects */
        .form-container .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 14px 15px;
            border-radius: 10px;
            border: 2px solid #ccc;
            outline: none;
            font-size: 16px;
            transition: all 0.4s ease;
            background: none;
        }
        .form-container input:focus {
            border-color: #2575fc;
            box-shadow: 0 0 15px rgba(37,117,252,0.4);
        }

        /* Floating Labels */
        .form-container label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s ease;
            background: white;
            padding: 0 5px;
        }
        .form-container input:focus + label,
        .form-container input:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 13px;
            color: #2575fc;
        }

        /* Button */
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
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }
        .btn::before {
            content: '';
            position: absolute;
            width: 0;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(255,255,255,0.2);
            transition: 0.4s;
        }
        .btn:hover::before {
            width: 100%;
        }

        /* Error Message */
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

        /* Optional: Floating background bubbles for more animation */
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
            <div class="input-group">
                <input type="email" name="email" placeholder=" " required>
                <label>Admin Email</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder=" " required>
                <label>Password</label>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>