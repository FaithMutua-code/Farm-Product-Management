<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6 text-blue-600">Product Management System</h1>
            
            <div class="flex flex-col space-y-4">
                <a href="login.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded text-center transition duration-200">
                    Staff Login
                </a>
                <a href="register.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded text-center transition duration-200">
                    Register New Staff
                </a>
            </div>
        </div>
    </div>
</body>
</html>