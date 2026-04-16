<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Manage Categories</h1>

<a href="add_category.php">+ Add New Category</a>
<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
</tr>
<?php while ($row = mysqli_fetch_assoc($result)) : ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
</tr>
<?php endwhile; ?>
</table>

<a href="home.php" class="back-link">⬅ Back to Dashboard</a>
</body>
</html>

<?php
session_start();
include '../db.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle adding new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        // Check duplicate
        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    Swal.fire({
                        title: '⚠️ Already Exists!',
                        text: 'This category name is already in use.',
                        icon: 'warning',
                        confirmButtonColor: '#007bff'
                    }).then(() => { window.location.href='add_category.php'; });
                  </script>";
            exit();
        }
        $stmt->close();

        // Insert safely
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                    Swal.fire({
                        title: '📂 Category Added!',
                        text: 'The new category has been saved successfully.',
                        icon: 'success',
                        confirmButtonColor: '#007bff'
                    }).then(() => { window.location.href='add_category.php'; });
                  </script>";
            exit();
        }
        $stmt->close();
    }
}

// Handle deleting category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    if ($stmt->execute()) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              <script>
                Swal.fire({
                    title: '🗑 Category Deleted!',
                    text: 'The category was removed successfully.',
                    icon: 'success',
                    confirmButtonColor: '#007bff'
                }).then(() => { window.location.href='add_category.php'; });
              </script>";
        exit();
    }
    $stmt->close();
}

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a192f, #112240, #1d3557);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            color: #fff;
            animation: fadeBg 1s ease-in-out;
        }
        @keyframes fadeBg { from {opacity:0;} to {opacity:1;} }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            animation: fadeInDown 1s ease;
            text-shadow: 0 0 15px #0e233aff, 0 0 25px #0056b3;
        }
        h1 i { margin-right: 10px; color: #0e233aff; }

        .card {
            background: rgba(255, 255, 255, 0.08);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            text-align: center;
            width: 100%;
            max-width: 500px;
            backdrop-filter: blur(10px);
            margin-bottom: 30px;
            animation: slideUp 0.8s ease;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: none;
            border-bottom: 2px solid #00bfff;
            background: transparent;
            color: #fff;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }
        .form-group input:focus {
            border-color: #1e90ff;
            box-shadow: 0 0 10px #1e90ff;
        }

        .form-group label {
            position: absolute;
            left: 12px;
            top: 12px;
            color: #aaa;
            transition: 0.3s;
            pointer-events: none;
        }
        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            top: -8px;
            left: 8px;
            font-size: 12px;
            color: #00bfff;
        }

        button {
            padding: 12px 20px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,123,255,0.5);
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,123,255,0.7);
        }

        h2 {
            margin: 20px 0;
            font-size: 26px;
            text-shadow: 0 0 10px #007bff;
        }

        table {
            width: 100%;
            max-width: 700px;
            border-collapse: collapse;
            margin-bottom: 25px;
            animation: fadeInUp 1s ease;
        }
        table th, table td {
            border: 1px solid #2c3e50;
            padding: 12px;
            text-align: center;
        }
        table th {
            background: #1d3557;
            color: #00bfff;
        }
        table tr:hover {
            background: rgba(0,123,255,0.15);
            transition: 0.3s;
        }

        .delete-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 3px 10px rgba(231,76,60,0.4);
        }
        .delete-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(231,76,60,0.6);
        }

        .back-link {
            color: #00bfff;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .back-link:hover {
            color: #1e90ff;
            text-shadow: 0 0 10px #00bfff;
        }

        @keyframes fadeInDown { from {opacity:0; transform:translateY(-20px);} to {opacity:1; transform:translateY(0);} }
        @keyframes slideUp { from {opacity:0; transform:translateY(40px);} to {opacity:1; transform:translateY(0);} }
        @keyframes fadeInUp { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>

<h1><i class="fas fa-folder-plus"></i> Add New Category</h1>

<div class="card">
    <form method="post">
        <div class="form-group">
            <input type="text" name="name" placeholder=" " required>
            <label>Category Name</label>
        </div>
        <button type="submit">Save Category</button>
    </form>
</div>

<h2>📂 Existing Categories</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Category Name</th>
        <th>Action</th>
    </tr>
    <?php while($cat = $categories->fetch_assoc()) : ?>
    <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= htmlspecialchars($cat['name']) ?></td>
        <td>
            <form method="post" style="display:inline;">
                <input type="hidden" name="delete_id" value="<?= $cat['id'] ?>">
                <button type="submit" class="delete-btn">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="home.php" class="back-link">⬅ Back to home</a>

</body>
</html>