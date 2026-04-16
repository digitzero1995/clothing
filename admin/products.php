<?php
session_start();
include '../db.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// ADD or UPDATE product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name           = mysqli_real_escape_string($conn, $_POST['name']);
    $price          = floatval($_POST['price']);
    $category_id    = intval($_POST['category_id']);
    $description    = mysqli_real_escape_string($conn, $_POST['description']);
    $specifications = mysqli_real_escape_string($conn, $_POST['specifications']);
    $warranty       = mysqli_real_escape_string($conn, $_POST['warranty']);
    $product_id     = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    $uploadDir = __DIR__ . "/../uploads/";
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            $image = uniqid('prod_', true) . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
        }
    }

    if ($product_id > 0) {
        // Update
        if ($image) {
            mysqli_query($conn, "UPDATE products SET name='$name', price=$price, category_id=$category_id, image='$image' WHERE id=$product_id");
        } else {
            mysqli_query($conn, "UPDATE products SET name='$name', price=$price, category_id=$category_id WHERE id=$product_id");
        }
        $exists = mysqli_query($conn, "SELECT * FROM product_details WHERE product_id=$product_id");
        if (mysqli_num_rows($exists)) {
            mysqli_query($conn, "UPDATE product_details 
                                 SET description='$description', specifications='$specifications', warranty='$warranty'
                                 WHERE product_id=$product_id");
        } else {
            mysqli_query($conn, "INSERT INTO product_details (product_id, description, specifications, warranty)
                                 VALUES ($product_id, '$description', '$specifications', '$warranty')");
        }
        header("Location: add_product.php?updated=1");
        exit();
    } else {
        // Insert
        mysqli_query($conn, "INSERT INTO products (name, price, category_id, image) 
                             VALUES ('$name', $price, $category_id, '$image')");
        $new_id = mysqli_insert_id($conn);
        mysqli_query($conn, "INSERT INTO product_details (product_id, description, specifications, warranty) 
                             VALUES ($new_id, '$description', '$specifications', '$warranty')");
        header("Location: add_product.php?added=1");
        exit();
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM product_details WHERE product_id=$id");
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    header("Location: add_product.php?deleted=1");
    exit();
}

// DATA
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
$products = mysqli_query($conn, "
    SELECT p.*, c.name AS category_name, d.description, d.specifications, d.warranty
    FROM products p
    LEFT JOIN categories c ON p.category_id=c.id
    LEFT JOIN product_details d ON p.id=d.product_id
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: Arial, sans-serif; background:#1e1e2f; color:#fff; padding:20px; }
        h1 { text-align:center; margin-bottom:20px;
             background:linear-gradient(90deg,#00ffd5,#007bff);
             -webkit-background-clip:text; -webkit-text-fill-color:transparent; }

        form { background:rgba(255,255,255,0.05); padding:20px; border-radius:12px;
               width:60%; margin:0 auto; transition:all .3s ease; }
        .form-group{margin-bottom:15px;}
        input,select,textarea{width:100%;padding:10px;border:1px solid #444;border-radius:6px;
                              background:rgba(255,255,255,0.1);color:#fff;}
        button{background:linear-gradient(90deg,#00ffd5,#007bff);color:#000;font-weight:bold;
               border:none;padding:10px;border-radius:6px;cursor:pointer;width:100%;
               transition:0.3s;}
        button:hover{transform:scale(1.03);}

        table{width:95%;margin:30px auto;border-collapse:collapse;background:rgba(255,255,255,0.05);
              border-radius:10px;overflow:hidden;}
        th,td{padding:12px;text-align:center;border-bottom:1px solid #444;}
        th{background:#007bff;}
        tr:hover{background:rgba(0,255,213,0.05);}
        img{width:50px;border-radius:6px;transition:transform .3s;}
        img:hover{transform:scale(2);}
        .btn{padding:6px 12px;border-radius:6px;font-weight:bold;text-decoration:none;}
        .btn-edit{background:#00bfff;color:#fff;transition:0.3s;}
        .btn-edit:hover{background:#0099cc;}
        .btn-delete{background:#e74c3c;color:#fff;transition:0.3s;}
        .btn-delete:hover{background:#c0392b;}

        /* Smooth modal */
        .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;
               background:rgba(0,0,0,0.6);z-index:9999;justify-content:center;align-items:center;}
        .modal.show{display:flex;animation:fadeIn .3s;}
        .modal-content{background:#2c2c3e;padding:20px;border-radius:12px;
                       width:50%;color:#fff;animation:slideUp .3s;}
        .close{float:right;font-size:24px;cursor:pointer;}

        @keyframes fadeIn{from{opacity:0;}to{opacity:1;}}
        @keyframes slideUp{from{transform:translateY(40px);opacity:0;}to{transform:translateY(0);opacity:1;}}
    </style>
</head>
<body>

<h1>📦 Manage Products</h1>

<!-- Add Form -->
<form method="post" enctype="multipart/form-data">
    <h3>➕ Add Product</h3>
    <div class="form-group"><input type="text" name="name" placeholder="Name" required></div>
    <div class="form-group"><input type="number" step="0.01" name="price" placeholder="Price" required></div>
    <div class="form-group">
        <select name="category_id" required>
            <option value="" disabled selected>Select Category</option>
            <?php while($cat=mysqli_fetch_assoc($categories)) echo "<option value='{$cat['id']}'>".htmlspecialchars($cat['name'])."</option>"; ?>
        </select>
    </div>
    <div class="form-group"><textarea name="description" placeholder="Description"></textarea></div>
    <div class="form-group"><textarea name="specifications" placeholder="Specifications"></textarea></div>
    <div class="form-group"><input type="text" name="warranty" placeholder="Warranty"></div>
    <div class="form-group"><input type="file" name="image"></div>
    <button type="submit">💾 Save Product</button>
</form>

<!-- Products Table -->
<table>
<tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Image</th><th>Action</th></tr>
<?php while($p=mysqli_fetch_assoc($products)): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['name']) ?></td>
  <td><?= htmlspecialchars($p['category_name']) ?></td>
  <td>₹<?= number_format($p['price'],2) ?></td>
  <td><?php if($p['image']) echo "<img src='../uploads/".htmlspecialchars($p['image'])."'>"; ?></td>
  <td>
    <button class="btn btn-edit" onclick='openEditModal(<?= json_encode($p,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>)'>✏️ Edit</button>
    <a href="?delete=<?= $p['id'] ?>" class="btn btn-delete" onclick="return confirm('Delete product?')">🗑️ Delete</a>
  </td>
</tr>
<?php endwhile; ?>
</table>

<!-- EDIT MODAL -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>✏️ Edit Product</h2>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="product_id" id="edit_id">
      <div class="form-group"><input type="text" name="name" id="edit_name" required></div>
      <div class="form-group"><input type="number" step="0.01" name="price" id="edit_price" required></div>
      <div class="form-group">
        <select name="category_id" id="edit_category" required>
          <?php 
          $cats = mysqli_query($conn,"SELECT * FROM categories ORDER BY name");
          while($c=mysqli_fetch_assoc($cats)) echo "<option value='{$c['id']}'>".htmlspecialchars($c['name'])."</option>"; 
          ?>
        </select>
      </div>
      <div class="form-group"><textarea name="description" id="edit_description"></textarea></div>
      <div class="form-group"><textarea name="specifications" id="edit_specifications"></textarea></div>
      <div class="form-group"><input type="text" name="warranty" id="edit_warranty"></div>
      <div class="form-group"><input type="file" name="image"></div>
      <button type="submit">💾 Update</button>
    </form>
  </div>
</div>

<script>
function openEditModal(product){
  document.getElementById('edit_id').value = product.id;
  document.getElementById('edit_name').value = product.name;
  document.getElementById('edit_price').value = product.price;
  document.getElementById('edit_category').value = product.category_id;
  document.getElementById('edit_description').value = product.description || '';
  document.getElementById('edit_specifications').value = product.specifications || '';
  document.getElementById('edit_warranty').value = product.warranty || '';
  document.getElementById('editModal').classList.add('show');
}
function closeModal(){ document.getElementById('editModal').classList.remove('show'); }
window.onclick=function(e){ if(e.target.classList.contains('modal')) closeModal(); }

document.addEventListener('DOMContentLoaded',()=>{
  const params=new URLSearchParams(window.location.search);
  if(params.has('added')) Swal.fire('✅ Added!','Product saved successfully.','success');
  if(params.has('updated')) Swal.fire('✏️ Updated!','Product updated successfully.','success');
  if(params.has('deleted')) Swal.fire('🗑️ Deleted!','Product removed.','success');
});
</script>
<a href="home.php" class="back-link">⬅ Back to home</a>
</body>
</html>