<?php
error_reporting(0);
include 'db.php';
session_start();  // Mulai session untuk keranjang belanja

// Ambil data kontak dari database
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_addres FROM tb_admin WHERE admin_id = 1");
$a = mysqli_fetch_object($kontak);

// Fungsi untuk menghitung total harga keranjang
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

// Menghapus produk dari keranjang
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit;
}

// Mengubah kuantitas produk dalam keranjang
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity == 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Denillastore</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class="page-cart">
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

    <!-- Keranjang Belanja -->
    <div class="section">
        <div class="container">
            <h3>Keranjang Belanja</h3>
            <div class="box">
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
                    <form method="POST" action="">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Kuantitas</th>
                                    <th>Total</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                                    $product_result = mysqli_query($conn, "SELECT * FROM tb_product WHERE product_id = '$product_id'");
                                    $product = mysqli_fetch_assoc($product_result);
                                    $total_price = $product['product_price'] * $quantity;
                                    $total += $total_price;
                                ?>
                                    <tr>
                                        <td><?php echo $product['product_name']; ?></td>
                                        <td>Rp. <?php echo number_format($product['product_price']); ?></td>
                                        <td>
                                            <input type="number" name="quantity[<?php echo $product['product_id']; ?>]" value="<?php echo $quantity; ?>" min="1" max="10">
                                        </td>
                                        <td>Rp. <?php echo number_format($total_price); ?></td>
                                        <td>
                                            <a href="cart.php?remove_from_cart=<?php echo $product['product_id']; ?>">Hapus</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="3" style="text-align: right;">Total Harga:</td>
                                    <td>Rp. <?php echo number_format($total); ?></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <input type="submit" name="update_cart" value="Update Keranjang">
                    </form>
                    
                    <br>
                    <a href="checkout.php"><button>Proses Pembayaran</button></a>
                    <?php } else { ?>
                        <p>Keranjang Anda kosong.</p>
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
