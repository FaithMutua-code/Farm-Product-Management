<?php include 'config.php';

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$name = $description = $price = $quantity = '';
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    
    // Validate inputs
    if (empty($name) || empty($price) || empty($quantity)) {
        $error = "Product name, price and quantity are required fields";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Price must be a positive number";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = "Quantity must be a non-negative number";
    } else {
        // Handle image upload
        $imagePath = '';
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $detectedType = finfo_file($fileInfo, $_FILES['product_image']['tmp_name']);
            finfo_close($fileInfo);
            
            if (in_array($detectedType, $allowedTypes)) {
                $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $destination = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $destination)) {
                    $imagePath = $destination;
                } else {
                    $error = "Failed to upload product image";
                }
            } else {
                $error = "Only JPG, PNG, and GIF images are allowed";
            }
        }
        
        if (empty($error)) {
            // Insert new product with image
            $sql = "INSERT INTO products (name, description, price, quantity, staff_id, image_path) 
                    VALUES ('$name', '$description', '$price', '$quantity', '{$_SESSION['staff_id']}', " . 
                    ($imagePath ? "'$imagePath'" : "'uploads/default.png'")
                    . ")";
            
            if ($conn->query($sql) === TRUE) {
                $success = "Product added successfully";
                // Clear form
                $name = $description = $price = $quantity = '';
            } else {
                $error = "Database error: " . $conn->error;
                // Delete uploaded file if database insert failed
                if ($imagePath && file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Kenyan Shilling Inventory</title>
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
        .currency-input {
            position: relative;
        }
        .currency-input::before {
            content: 'KES';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #006644;
            font-weight: bold;
            z-index: 10;
        }
        .currency-input input {
            padding-left: 50px !important;
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
            <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-kenya-black font-display">
                            <i class="fas fa-plus-circle mr-2 text-kenya-green"></i>
                            Add New Product
                        </h1>
                        <p class="text-sm text-gray-600 mt-1">Kenyan Shilling (KES) pricing</p>
                    </div>
                    <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-kenya-white bg-kenya-green hover:bg-kenya-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kenya-green btn-transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
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

                <!-- Form Card -->
                <div class="bg-kenya-white rounded-lg shadow-md card-shadow overflow-hidden">
                    <form action="add_product.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Product Name <span class="text-kenya-red">*</span>
                            </label>
                            <input type="text" id="name" name="name" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                                   value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                                   placeholder="Enter product name">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="3"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                                   placeholder="Product details and specifications"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Price (KES) <span class="text-kenya-red">*</span>
                                </label>
                                <div class="currency-input mt-1 relative">
                                    <input type="number" id="price" name="price" step="0.01" min="0.01" required 
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                                           value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>"
                                           placeholder="0.00">
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                    Quantity <span class="text-kenya-red">*</span>
                                </label>
                                <input type="number" id="quantity" name="quantity" min="0" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                                       value="<?php echo isset($quantity) ? htmlspecialchars($quantity) : ''; ?>"
                                       placeholder="Enter quantity">
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Product Image
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="product_image" class="relative cursor-pointer bg-white rounded-md font-medium text-kenya-green hover:text-kenya-green-dark focus-within:outline-none">
                                            <span>Upload an image</span>
                                            <input id="product_image" name="product_image" type="file" class="sr-only" accept="image/jpeg, image/png, image/gif">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 5MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-kenya-white bg-kenya-green hover:bg-kenya-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kenya-green btn-transition">
                                <i class="fas fa-save mr-2"></i> Save Product
                            </button>
                        </div>
                    </form>
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