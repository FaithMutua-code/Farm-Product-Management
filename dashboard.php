<?php include 'config.php';

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($sql);
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Kenyan Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'kenya': {
                            'green': '#006644',
                            'red': '#BB0000',
                            'black': '#000000',
                            'white': '#FFFFFF',
                            'cream': '#F5F5DC'
                        },
                        'currency': {
                            'gold': '#D4AF37',
                            'silver': '#C0C0C0'
                        }
                    },
                    fontFamily: {
                        'display': ['"Roboto Condensed"', 'sans-serif'],
                        'body': ['"Open Sans"', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'body', sans-serif;
            background-color: #f8f9fa;
        }
        .product-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .no-image {
            width: 70px;
            height: 70px;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            color: #9ca3af;
        }
        .nav-divider {
            border-bottom: 2px solid #006644;
        }
        .card-shadow {
            box-shadow: 0 4px 12px rgba(0, 102, 68, 0.1);
        }
        .btn-transition {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .btn-transition:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 102, 68, 0.15);
        }
        .table-row:hover {
            background-color: #f0fdf4;
        }
        .currency::before {
            content: 'KES';
            margin-right: 4px;
            font-weight: bold;
            color: #006644;
        }
    </style>
</head>
<body class="bg-kenya-cream">
    <div class="min-h-screen flex flex-col">
        <!-- Professional Navigation -->
        <nav class="bg-kenya-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-kenya-green text-kenya-white">
                            <i class="fas fa-coins text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-kenya-green font-display">Kenyan Inventory</span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <span class="text-sm text-kenya-black font-medium">
                            <i class="fas fa-user-circle mr-1 text-kenya-green"></i>
                            <?php echo htmlspecialchars($_SESSION['staff_name']); ?>
                        </span>
                        <a href="logout.php" class="text-sm text-kenya-red hover:text-kenya-red-dark font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
            <div class="nav-divider"></div>
        </nav>

        <!-- Main content -->
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-kenya-black font-display">
                            <i class="fas fa-boxes mr-2 text-kenya-green"></i>
                            Product Inventory
                        </h1>
                        <p class="text-sm text-gray-600 mt-1">All prices in Kenyan Shillings (KES)</p>
                    </div>
                    <a href="add_product.php" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-kenya-white bg-kenya-green hover:bg-kenya-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kenya-green btn-transition">
                        <i class="fas fa-plus-circle mr-2"></i> Add Product
                    </a>
                </div>

                <!-- Notifications -->
                <?php if (!empty($error)): ?>
                    <div class="rounded-md bg-red-50 p-4 mb-6 border-l-4 border-kenya-red">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle h-5 w-5 text-kenya-red"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-kenya-red"><?php echo $error; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="rounded-md bg-green-50 p-4 mb-6 border-l-4 border-kenya-green">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle h-5 w-5 text-kenya-green"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-kenya-green"><?php echo $success; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Products Table -->
                <div class="bg-kenya-white rounded-lg shadow-md card-shadow overflow-hidden">
                    <?php if (empty($products)): ?>
                        <div class="p-8 text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-kenya-green/10">
                                <i class="fas fa-box-open text-kenya-green"></i>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-kenya-black">No products</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding a new product</p>
                            <div class="mt-6">
                                <a href="add_product.php" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-kenya-white bg-kenya-green hover:bg-kenya-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kenya-green">
                                    <i class="fas fa-plus-circle mr-2"></i> New Product
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($products as $product): ?>
                                        <tr class="table-row hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if (!empty($product['image_path'])): ?>
                                                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                                                <?php else: ?>
                                                    <div class="no-image">
                                                        <i class="fas fa-image text-lg"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-500 max-w-xs truncate"><?php echo htmlspecialchars($product['description']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 font-medium currency"><?php echo number_format($product['price'], 2); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $product['quantity'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                    <?php echo htmlspecialchars($product['quantity']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="text-kenya-green hover:text-kenya-green-dark mr-4">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="text-kenya-red hover:text-kenya-red-dark" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-kenya-white py-4 nav-divider">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="text-center text-xs text-gray-500">
                    &copy; <?php echo date('Y'); ?> Kenyan Inventory System. All prices in Kenyan Shillings (KES).
                </p>
            </div>
        </footer>
    </div>
</body>
</html>