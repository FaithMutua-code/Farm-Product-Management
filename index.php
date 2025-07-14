<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenyan Inventory System</title>
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
        .btn-transition {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .btn-transition:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 102, 68, 0.15);
        }
        .card-shadow {
            box-shadow: 0 4px 12px rgba(0, 102, 68, 0.1);
        }
    </style>
</head>
<body class="bg-kenya-cream">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-kenya-white p-8 rounded-lg card-shadow w-full max-w-md">
            <div class="flex justify-center mb-6">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-kenya-green text-kenya-white">
                    <i class="fas fa-coins text-2xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-center mb-2 text-kenya-green font-display">
                Kenyan Inventory System
            </h1>
            <p class="text-center text-sm text-gray-600 mb-6">Product Management Portal</p>
            
            <div class="flex flex-col space-y-4">
                <a href="login.php" class="btn-transition inline-flex items-center justify-center bg-kenya-green hover:bg-kenya-green-dark text-kenya-white py-3 px-4 rounded-md font-medium">
                    <i class="fas fa-sign-in-alt mr-2"></i> Staff Login
                </a>
                <a href="register.php" class="btn-transition inline-flex items-center justify-center bg-kenya-white hover:bg-gray-100 text-kenya-black border border-gray-300 py-3 px-4 rounded-md font-medium">
                    <i class="fas fa-user-plus mr-2"></i> Register New Staff
                </a>
            </div>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    &copy; <?php echo date('Y'); ?> Kenyan Inventory System
                </p>
            </div>
        </div>
    </div>
</body>
</html>