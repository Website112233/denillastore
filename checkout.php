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

// Proses konfirmasi pesanan jika form disubmit
if (isset($_POST['confirm_order'])) {
    // Ambil data dari form
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
    $customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Menghitung total harga
    $total_price = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $result = mysqli_query($conn, "SELECT product_price FROM tb_product WHERE product_id = '$product_id'");
        $product = mysqli_fetch_assoc($result);
        $total_price += $product['product_price'] * $quantity;
    }

    // Menyimpan data pesanan ke dalam tabel tb_orders
    $order_query = "INSERT INTO tb_orders (customer_name, customer_email, customer_phone, customer_address, total_price, payment_method, order_status)
                    VALUES ('$customer_name', '$customer_email', '$customer_phone', '$customer_address', '$total_price', '$payment_method', 'pending')";

    if (mysqli_query($conn, $order_query)) {
        $order_id = mysqli_insert_id($conn);  // Mendapatkan ID pesanan yang baru dibuat

        // Redirect ke halaman confirm-order.php dan kirim order_id
        header("Location: confirm-order.php?order_id=" . $order_id);
        exit;
    } else {
        $error_message = "Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Denillastore</title>
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
            <h3>Checkout</h3>
            <div class="box">
                <form method="POST" action="">
                    <label for="customer_name">Nama Pemesan:</label>
                    <input type="text" name="customer_name" required>

                    <label for="customer_email">Email:</label>
                    <input type="email" name="customer_email" required>

                    <label for="customer_phone">Nomor Telepon:</label>
                    <input type="text" name="customer_phone" required>

                    <label for="customer_address">Alamat:</label>
                    <textarea name="customer_address" required></textarea>

                    <label for="payment_method">Metode Pembayaran:</label>
                    <select name="payment_method" required>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="COD">Cash on Delivery</option>
                    </select>

                    <input type="submit" name="confirm_order" value="Konfirmasi Pesanan">
                </form>
                <?php if (isset($error_message)) { ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
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
