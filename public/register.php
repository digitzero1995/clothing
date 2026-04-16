<?php
session_start();
include '../config/db_sqlite.php';

$errorMsg = '';
$successMsg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $errorMsg = "☝️ All fields are required!";
    } elseif ($password !== $confirm_password) {
        $errorMsg = "❌ Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $errorMsg = "❌ Password must be at least 6 characters!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "❌ Invalid email address!";
    } else {
        try {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $email_exists = $stmt->fetch();
            
            if ($email_exists) {
                $errorMsg = "❌ Email already registered! Try <a href='login.php' style='color:#721c24;text-decoration:underline;'>logging in</a>";
            } else {
                // Check if username already exists
                $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
                $stmt->execute([$username]);
                $user_exists = $stmt->fetch();
                
                if ($user_exists) {
                    $errorMsg = "❌ Username already taken! Choose another one.";
                } else {
                    // Hash password with bcrypt
                    $password_hash = password_hash($password, PASSWORD_BCRYPT);
                    
                    // Insert new user
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, is_admin) VALUES (?, ?, ?, 0)");
                    $stmt->execute([$username, $email, $password_hash]);
                    
                    $successMsg = "✅ Registration successful! <a href='login.php' style='color:#155724;text-decoration:underline;'>Click here to login</a> or redirecting in 2 seconds...";
                    $_POST = []; // Clear form
                    // Redirect after 2 seconds
                    header("refresh:2;url=login.php");
                }
            }
        } catch (Exception $e) {
            // Handle duplicate key error
            if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                if (strpos($e->getMessage(), 'email') !== false) {
                    $errorMsg = "❌ Email already registered! Try <a href='login.php' style='color:#721c24;text-decoration:underline;'>logging in</a>";
                } else {
                    $errorMsg = "❌ Username already taken! Choose another one.";
                }
            } else {
                $errorMsg = "❌ Registration failed: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TrendAura</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f5f5f5;
            padding: 20px;
        }
        .register-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        .register-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        .register-container p {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #1abc9c;
            box-shadow: 0 0 5px rgba(26, 188, 156, 0.3);
        }
        .btn-register {
            width: 100%;
            padding: 12px;
            background: #1abc9c;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-register:hover {
            background: #0a9478;
        }
        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .success-msg {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #1abc9c;
            text-decoration: none;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>✍️ Sign Up</h1>
        <p>Create your TrendAura account</p>

        <?php if ($errorMsg): ?>
            <div class="error-msg"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <?php if ($successMsg): ?>
            <div class="success-msg"><?php echo htmlspecialchars($successMsg); ?></div>
        <?php endif; ?>

        <?php if (!$successMsg): ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter password (min 6 chars)">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="Confirm password">
            </div>

            <button type="submit" class="btn-register">Sign Up</button>
        </form>
        <?php endif; ?>

        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
