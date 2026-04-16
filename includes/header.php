<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendAura Header</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-box: border-box; font-family: 'Arial', sans-serif; }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 5%;
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #1abc9c; /* Image mujab Teal color */
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 25px;
        }

        .nav-links li a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .nav-links li a:hover {
            color: #1abc9c;
        }

        .icons-group {
            display: flex;
            gap: 20px;
            font-size: 18px;
            color: #333;
        }

        .icons-group i {
            cursor: pointer;
            transition: 0.3s;
        }

        .icons-group i:hover {
            color: #1abc9c;
        }
    </style>
</head>
<body>

<header class="header-container">
    <a href="index.php" class="logo">TrendAura</a>

    <ul class="nav-links">
    <li><a href="category.php?cat=all">SHOP ALL</a></li>
    <li><a href="category.php?cat=women">WOMEN</a></li>
    <li><a href="category.php?cat=men">MEN</a></li>
    <li><a href="category.php?cat=kids">KIDS</a></li>
    <li><a href="category.php?cat=home">HOME & LIVING</a></li>
    <li><a href="category.php?cat=brands">BRANDS</a></li>
    <li><a href="category.php?cat=sale">SALE</a></li>
    </ul>

    <div class="icons-group">
        <i class="fa-solid fa-magnifying-glass"></i> 
        <a href="wishlist.php" style="color: #333; text-decoration: none;"><i class="fa-regular fa-heart"></i></a>          
        <a href="../auth/login.php" style="color: #333; text-decoration: none;"><i class="fa-regular fa-user"></i></a>           
        <a href="cart.php" style="color: #333; text-decoration: none;"><i class="fa-solid fa-cart-shopping"></i></a>    
    </div>
</header>

</body>
</html>