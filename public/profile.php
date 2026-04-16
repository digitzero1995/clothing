<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$successMsg = $errorMsg = "";

// 🔍 Check if profile exists
$stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

// 📥 Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);

    // Handle image upload
    $image = $profile['image'] ?? null;
    if (!empty($_FILES['image']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            $filename = uniqid() . "_" . basename($_FILES['image']['name']);
            $target_path = "uploads/" . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image = $filename;
            } else {
                $errorMsg = "Failed to upload image.";
            }
        } else {
            $errorMsg = "Only JPG, JPEG, PNG files are allowed.";
        }
    }

    // Insert or update profile
    if ($profile) {
        $stmt = $conn->prepare("UPDATE user_profiles SET name=?, contact=?, address=?, image=? WHERE user_id=?");
        $stmt->bind_param("ssssi", $name, $contact, $address, $image, $user_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, name, contact, address, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $name, $contact, $address, $image);
    }

    if ($stmt->execute()) {
        $successMsg = "Profile updated successfully.";
        // Reload profile
        $stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $profile = $stmt->get_result()->fetch_assoc();
    } else {
        $errorMsg = "Failed to update profile.";
    }
}
?>

<?php include 'header.php'; ?>

<style>
.profile-container {
    max-width: 600px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.profile-container h2 {
    margin-bottom: 20px;
}
.profile-container input, .profile-container textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
}
.profile-pic {
    text-align: center;
    margin-bottom: 20px;
}
.profile-pic img {
    max-width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #1a676c;
}
.success { color: green; }
.error { color: red; }
</style>

<div class="profile-container">
    <h2>My Profile</h2>

    <?php if ($successMsg): ?><p class="success"><?php echo $successMsg; ?></p><?php endif; ?>
    <?php if ($errorMsg): ?><p class="error"><?php echo $errorMsg; ?></p><?php endif; ?>

    <div class="profile-pic">
        <?php if (!empty($profile['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($profile['image']); ?>" alt="Profile Picture">
        <?php else: ?>
            <img src="https://via.placeholder.com/150?text=No+Image" alt="No Profile">
        <?php endif; ?>
    </div>

    <form method="post" enctype="multipart/form-data">
        <label>Full Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required>

        <label>Contact:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($profile['contact'] ?? ''); ?>">

        <label>Address:</label>
        <textarea name="address"><?php echo htmlspecialchars($profile['address'] ?? ''); ?></textarea>

        <label>Profile Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php include '../includes/foooter.php'; ?>