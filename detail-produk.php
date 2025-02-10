<?php
    session_start();
    error_reporting(0);
    include 'db.php'; 
    
    // Fetch admin contact information
    $kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_addres FROM tb_admin WHERE admin_id = 1");
    $a = mysqli_fetch_object($kontak);

    // Fetch product details
    $produk = mysqli_query($conn, "SELECT * FROM tb_product WHERE product_id = '".$_GET['id']."' ");
    $p = mysqli_fetch_object($produk);

    // Add product to cart
    if (isset($_GET['add_to_cart'])) {
        $product_id = $_GET['add_to_cart'];

        // Initialize cart if not already done
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product is already in the cart and update the quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++; // Increase quantity if the product exists
        } else {
            $_SESSION['cart'][$product_id] = 1; // Add the product for the first time
        }

        // Redirect to avoid reloading the same page with the cart query string
        header("Location: detail-produk.php?id=" . $product_id);
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denillastore</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body>
   <!-- Header -->
   <header>
       <div class="container">
            <h1><a href="index.php">Denillastore</a></h1>
            <ul>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="cart.php">Keranjang Belanja (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a></li>
            </ul>
       </div>
   </header>

   <!-- Search -->
   <div class="search">
       <div class="container">
           <form action="produk.php">
               <input type="text" name="search" placeholder="Cari Produk" value="<?php echo $_GET['search'] ?>">
               <input type="hidden" name="kat" value="<?php echo $_GET['kat']?>">
               <input type="submit" name="cari" value="Cari Produk">
           </form>
       </div>
   </div>

   <!-- Product Detail -->
   <div class="section">
        <div class="container">
            <h3>Detail Produk</h3>
            <div class="box">
                <div class="col-2">
                    <img src="produk/<?php echo $p->product_image ?>" width="80%">
                </div>
                <div class="col-2">
                    <h3><?php echo $p->product_name ?></h3>
                    <h4>Rp. <?php echo number_format( $p->product_price ) ?></h4>
                    <p>Deskripsi : <br>
                        <?php echo $p->product_description ?>
                    </p>
                    
                    <!-- Add to Cart Button -->
                    <p><a href="?add_to_cart=<?php echo $p->product_id; ?>"><button>Tambah ke Keranjang</button></a></p>
                </div>
            </div>
        </div>
   </div>

   <!-- Footer -->
   <div class="footer">
       <div class="container">
           <h4>Alamat</h4>
           <p><?php echo $a->admin_addres ?></p>

           <h4>Email</h4>
           <p><?php echo $a->admin_email ?></p>

           <h4>Telepon</h4>
           <p><?php echo $a->admin_telp ?></p>
           <small>Copyright &copy 2025 - Denillastore.</small>
       </div>
   </div>
</body>
</html>
