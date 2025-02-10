<?php
include 'db.php'; // Menyertakan koneksi ke database
session_start();

// Ambil data kontak dari database
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_addres FROM tb_admin WHERE admin_id = 1");
$a = mysqli_fetch_object($kontak);

// Jika keranjang belanja kosong, redirect ke halaman index
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

// Ambil order_id dari URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Ambil data pesanan dari tb_orders berdasarkan order_id
    $order_result = mysqli_query($conn, "SELECT * FROM tb_orders WHERE order_id = '$order_id'");
    $order = mysqli_fetch_assoc($order_result);

    // Ambil detail produk pesanan dari tb_order_details
    $order_details_result = mysqli_query($conn, "SELECT * FROM tb_order_details WHERE order_id = '$order_id'");
} else {
    // Jika tidak ada order_id, redirect ke halaman index
    header("Location: index.php");
    exit;
}
// Menghitung total harga dari keranjang belanja
$total_price = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $result = mysqli_query($conn, "SELECT product_name, product_price FROM tb_product WHERE product_id = '$product_id'");
    $product = mysqli_fetch_assoc($result);
    $total_price += $product['product_price'] * $quantity;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - Denillastore</title>
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
            <h3>Konfirmasi Pesanan</h3>
            <div class="box">

                <!-- Menampilkan Data Pembeli -->
                <h4>Data Pembeli:</h4>
                <p><strong>Nama:</strong> <?php echo $order['customer_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $order['customer_email']; ?></p>
                <p><strong>Telepon:</strong> <?php echo $order['customer_phone']; ?></p>
                <p><strong>Alamat:</strong> <?php echo nl2br($order['customer_address']); ?></p>

                <h4>Metode Pembayaran:</h4>
                <p><?php echo $order['payment_method']; ?></p>

                <!-- Menampilkan daftar produk yang dipesan -->
                <h4>Daftar Produk:</h4>
                <table border="1" cellpadding="10">
                    <tr>
                        <th>Produk</th>
                        <th>Kuantitas</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                    <?php
                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        $result = mysqli_query($conn, "SELECT product_name, product_price FROM tb_product WHERE product_id = '$product_id'");
                        $product = mysqli_fetch_assoc($result);
                        $total = $product['product_price'] * $quantity;
                    ?>
                        <tr>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td>Rp. <?php echo number_format($product['product_price']); ?></td>
                            <td>Rp. <?php echo number_format($total); ?></td>
                        </tr>
                    <?php } ?>
                </table>

                <!-- Menampilkan total harga -->
                <h4>Total Harga: Rp. <?php echo number_format($total_price); ?></h4>

                <!-- Konfirmasi pesanan -->
                <form method="POST" action="order-success.php">
                    <input type="hidden" name="customer_name" value="<?php echo $customer_name; ?>">
                    <input type="hidden" name="customer_email" value="<?php echo $customer_email; ?>">
                    <input type="hidden" name="customer_phone" value="<?php echo $customer_phone; ?>">
                    <input type="hidden" name="customer_address" value="<?php echo $customer_address; ?>">
                    <input type="hidden" name="payment_method" value="<?php echo $payment_method; ?>">
                    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

                    <input type="submit" name="confirm_order" value="Pesan Sekarang">
                </form>
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
