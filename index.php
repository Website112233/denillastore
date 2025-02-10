<?php
error_reporting (0);
    include 'db.php'; 
    session_start();  // Mulai session untuk keranjang belanja
    
    // Ambil data kontak dari database
    $kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_addres FROM tb_admin WHERE admin_id = 1");
    $a = mysqli_fetch_object($kontak);

    // Menambahkan produk ke keranjang
    if (isset($_GET['add_to_cart'])) {
        $product_id = $_GET['add_to_cart'];

        // Mengecek apakah keranjang sudah ada atau belum
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Menambahkan produk ke dalam session
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++; // Menambah kuantitas jika produk sudah ada
        } else {
            $_SESSION['cart'][$product_id] = 1; // Menambahkan produk pertama kali
        }
    }

    // Menghitung total harga keranjang
    function calculate_total($conn) {
        $total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $result = mysqli_query($conn, "SELECT product_price FROM tb_product WHERE product_id = '$product_id'");
                $product = mysqli_fetch_assoc($result);
                $total += $product['product_price'] * $quantity;
            }
        }
        return $total;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denillastore</title>

    <!-- My Style -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>
<body class="page-index">
   <!--header-->
   <header>
       <div class="container">
            <h1><a href="index.php">Denillastore</a></h1>
            <ul>
                <li><a href="produk.php" id="search">Produk</a></li>
                <li><a href="cart.php">Keranjang Belanja (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a></li>
       </div>

   </header>

   <!-- search -->
   <div class="search">
       <div class="container">
           <form action="produk.php">
               <input type="text" name="search" placeholder="Cari Produk" value="<?php echo $_GET['search'] ?>">
               <input type="hidden" name="kat" value="<?php echo $_GET['kat']?>">
               <input type="submit" name="cari" value="Cari Produk">
           </form>
       </div>
   </div>

   <!-- category -->
   <div class="section">
       <div class="container">
           <h3>Kategori</h3>
           <div class="box">
               <?php
                    $kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
                    if(mysqli_num_rows($kategori) > 0){
                        while($k = mysqli_fetch_array($kategori)){
               ?>
                    <a href="produk.php?kat=<?php echo $k['category_id'] ?>">
                        <div class="col-5">
                            <img src="img/download.png" width="50px" style="margin-bottom:3px">
                            <p><?php echo $k['category_name']?></p>
                        </div>
                    </a>
                <?php } }else{ ?>
                        <p>Kategori Tidak Ada</p>
                <?php } ?>
           </div>
       </div>
   </div>

  <!--  product terbaru -->
  <div class="section">
       <div class="container">
            <h3>Produk terbaru </h3>    
            <div class="box">
                <?php
                    if($_GET['search'] != '' || $_GET['kat'] != '' ){
                        $where = "AND product_name LIKE '%".$_GET['search']."%' AND category_id LIKE '%".$_GET['kat']."%' ";
                    }
                    $produk = mysqli_query($conn, "SELECT * FROM tb_product WHERE product_status = 1 $where  ORDER BY product_id DESC LIMIT 8");
                    if(mysqli_num_rows($produk) > 0){
                        while($p = mysqli_fetch_array($produk)){
                ?>
                    <!-- Link to Product Detail Page -->
                    <div class="col-4">
                        <a href="detail-produk.php?id=<?php echo $p['product_id']; ?>">
                            <img src="produk/<?php echo $p['product_image']; ?>" alt="<?php echo $p['product_name']; ?>">
                        </a>
                        
                        <p class="nama"><?php echo substr($p['product_name'], 0, 30); ?></p>
                        <p class="harga">Rp. <?php echo number_format($p['product_price']); ?></p>

                        <!-- Add to Cart Button -->
                        <p>
                            <a href="?add_to_cart=<?php echo $p['product_id']; ?>">
                                <button>Tambah ke Keranjang</button>
                            </a>
                        </p>
                    </div>
                <?php } } else { ?>
                    <p>Produk Tidak Tersedia</p>
                <?php } ?>
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