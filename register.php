<?php include 'config.php';

if (isset($_SESSION['staff_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Check if email already exists
        $check_email = $conn->query("SELECT id FROM staff WHERE email = '$email'");
        if ($check_email->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new staff
            $sql = "INSERT INTO staff (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Error: " . $conn->error;
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
    <title>Register | Kenyan Inventory</title>
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
        .card-shadow {
            box-shadow: 0 4px 12px rgba(0, 102, 68, 0.1);
        }
        .btn-transition {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .btn-transition:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 102, 68, 0.15);
        }
        input:focus, textarea:focus, select:focus {
            border-color: #006644;
            box-shadow: 0 0 0 3px rgba(0, 102, 68, 0.1);
        }
    </style>
</head>
<body class="bg-kenya-cream">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-kenya-white p-8 rounded-lg card-shadow w-full max-w-md">
            <div class="flex justify-center mb-6">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-kenya-green text-kenya-white">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-center mb-2 text-kenya-green font-display">
                Staff Registration
            </h1>
            <p class="text-center text-sm text-gray-600 mb-6">Create your Kenyan Inventory account</p>
            
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
                            <div class="mt-4">
                                <a href="login.php" class="btn-transition inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-kenya-white bg-kenya-green hover:bg-kenya-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kenya-green">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Go to Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <form action="register.php" method="POST" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user mr-2 text-kenya-green"></i>Full Name
                        </label>
                        <input type="text" id="name" name="name" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                               placeholder="John Doe">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope mr-2 text-kenya-green"></i>Email Address
                        </label>
                        <input type="email" id="email" name="email" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                               placeholder="your@email.com">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock mr-2 text-kenya-green"></i>Password
                        </label>
                        <input type="password" id="password" name="password" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                               placeholder="••••••••">
                        <p class="mt-1 text-xs text-gray-500">Minimum 6 characters</p>
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock mr-2 text-kenya-green"></i>Confirm Password
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                               placeholder="••••••••">
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" class="btn-transition w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-kenya-white bg-kenya-green hover:bg-kenya-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kenya-green">
                            <i class="fas fa-user-plus mr-2"></i> Register Account
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="login.php" class="font-medium text-kenya-green hover:text-kenya-green-dark">
                            Login here
                        </a>
                    </p>
                </div>
            <?php endif; ?>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    &copy; <?php echo date('Y'); ?> Kenyan Inventory System
                </p>
            </div>
        </div>
    </div>
</body>
</html>