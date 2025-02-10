<?php
include 'db.php'; // Menyertakan koneksi ke database
session_start();

// Ambil data kontak dari database
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_addres FROM tb_admin WHERE admin_id = 1");
$a = mysqli_fetch_object($kontak);

// Menghapus produk di keranjang belanja ketika halaman ini diakses
unset($_SESSION['cart']); // Menghapus semua produk yang ada dalam session cart

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Denillastore</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

 <!--header-->
 <header>
       <div class="container">
            <h1><a href="index.php">Denillastore</a></h1>
            <ul>
                <li><a href="produk.php" id="search">Produk</a></li>
                <li><a href="cart.php">Keranjang Belanja (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a></li>
       </div>

   </header>

    <div class="section">
        <div class="container">
            <h3>Pesanan Berhasil</h3>
            <div class="box">
                <h4>Terima kasih atas kepercayaan Anda!</h4>
                <p>Pesanan Anda telah berhasil diproses.</p>
                <p>Terima kasih atas pesanan Anda. Kami tunggu pesanan berikutnya!</p>

                <!-- Tombol untuk kembali berbelanja -->
                <a href="index.php"><button>Kembali Berbelanja</button></a>
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
