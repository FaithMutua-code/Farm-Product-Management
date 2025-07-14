<?php include 'config.php';

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$product_id = $conn->real_escape_string($_GET['id']);

// Delete product
$sql = "DELETE FROM products WHERE id = '$product_id'";
if ($conn->query($sql)) {
    $_SESSION['success'] = "Product deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting product: " . $conn->error;
}

header("Location: dashboard.php");
exit();
?>