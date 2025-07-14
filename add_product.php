<?php include 'config.php';

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

$name = $description = $price = $quantity = '';
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);

    if (empty($name) || empty($price) || empty($quantity)) {
        $error = "Product name, price and quantity are required fields";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Price must be a positive number";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = "Quantity must be a non-negative number";
    } else {
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
            $finalImagePath = $imagePath ? "'$imagePath'" : "'uploads/default.png'";
            $sql = "INSERT INTO products (name, description, price, quantity, staff_id, image_path) 
                    VALUES ('$name', '$description', '$price', '$quantity', '{$_SESSION['staff_id']}', $finalImagePath)";

            if ($conn->query($sql) === TRUE) {
                $success = "Product added successfully";
                $name = $description = $price = $quantity = '';
            } else {
                $error = "Database error: " . $conn->error;
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
    <title>Add Product | Kenyan Shilling Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        <nav class="bg-kenya-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-kenya-green text-kenya-white flex items-center justify-center">
                            <i class="fas fa-coins text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-kenya-green font-display">Kenyan Inventory</span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <span class="text-sm text-kenya-black font-medium">
                            <i class="fas fa-user-circle mr-1 text-kenya-green"></i>
                            <?php echo htmlspecialchars($_SESSION['staff_name']); ?>
                        </span>
                        <a href="logout.php" class="text-sm text-kenya-red font-medium hover:underline">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
            <div class="nav-divider"></div>
        </nav>

        <main class="flex-grow">
            <div class="max-w-4xl mx-auto py-8 px-4">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-2xl font-bold text-kenya-black font-display">
                        <i class="fas fa-plus-circle mr-2 text-kenya-green"></i> Add New Product
                    </h1>
                    <a href="dashboard.php" class="px-4 py-2 bg-kenya-green text-white rounded-md btn-transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="bg-red-50 border-l-4 border-kenya-red p-4 mb-6">
                        <p class="text-sm font-medium text-kenya-red"><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="bg-green-50 border-l-4 border-kenya-green p-4 mb-6">
                        <p class="text-sm font-medium text-kenya-green"><?php echo $success; ?></p>
                    </div>
                <?php endif; ?>

                <div class="bg-kenya-white rounded-lg shadow-md p-6">
                    <form action="add_product.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-kenya-red">*</span></label>
                            <input type="text" id="name" name="name" required class="w-full p-3 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($name); ?>">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3" class="w-full p-3 border border-gray-300 rounded-md"><?php echo htmlspecialchars($description); ?></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (KES) <span class="text-kenya-red">*</span></label>
                                <div class="currency-input">
                                    <input type="number" step="0.01" min="0.01" id="price" name="price" required class="w-full p-3 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($price); ?>">
                                </div>
                            </div>
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-kenya-red">*</span></label>
                                <input type="number" min="0" id="quantity" name="quantity" required class="w-full p-3 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($quantity); ?>">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center">
                                <label for="product_image" class="cursor-pointer text-kenya-green font-medium hover:underline">
                                    <span>Upload an image</span>
                                    <input id="product_image" name="product_image" type="file" class="sr-only" accept="image/jpeg, image/png, image/gif">
                                </label>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 5MB</p>
                                <img id="image-preview" src="#" alt="Image Preview" class="hidden mt-4 max-h-48 mx-auto rounded-md border border-gray-300" />
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-kenya-green text-white rounded-md btn-transition">
                                <i class="fas fa-save mr-2"></i> Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <footer class="bg-kenya-white py-4 nav-divider">
            <div class="text-center text-xs text-gray-500">
                &copy; <?php echo date('Y'); ?> Kenyan Inventory System. All prices in Kenyan Shillings (KES).
            </div>
        </footer>
    </div>

    <!-- Image Preview Script -->
    <script>
        document.getElementById('product_image').addEventListener('change', function (event) {
            const preview = document.getElementById('image-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
