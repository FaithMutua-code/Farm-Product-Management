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

// Fetch product details
$sql = "SELECT * FROM products WHERE id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}

$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    
    // Validate inputs
    if (empty($name) || empty($price) || empty($quantity)) {
        $error = "Name, price and quantity are required!";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Price must be a positive number!";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = "Quantity must be a non-negative number!";
    } else {
        // Update product
        $sql = "UPDATE products SET 
                name = '$name', 
                description = '$description', 
                price = '$price', 
                quantity = '$quantity',
                updated_at = NOW()
                WHERE id = '$product_id'";
        
        if ($conn->query($sql) ){
            $success = "Product updated successfully!";
            // Refresh product data
            $sql = "SELECT * FROM products WHERE id = '$product_id'";
            $result = $conn->query($sql);
            $product = $result->fetch_assoc();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Product Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-semibold text-gray-900">Product Management</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-4">Welcome, <?php echo htmlspecialchars($_SESSION['staff_name']); ?></span>
                        <a href="logout.php" class="text-gray-600 hover:text-gray-900">Logout</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Edit Product</h2>
                    <a href="dashboard.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded transition duration-200">
                        Back to Dashboard
                    </a>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $success; ?></span>
                    </div>
                <?php endif; ?>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                    <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Product Name *</label>
                            <input type="text" id="name" name="name" required 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price *</label>
                                <input type="number" id="price" name="price" step="0.01" min="0.01" required 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       value="<?php echo htmlspecialchars($product['price']); ?>">
                            </div>

                            <div>
                                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity *</label>
                                <input type="number" id="quantity" name="quantity" min="0" required 
                                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                       value="<?php echo htmlspecialchars($product['quantity']); ?>">
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>