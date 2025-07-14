<?php include 'config.php';

if (isset($_SESSION['staff_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $sql = "SELECT * FROM staff WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        if (password_verify($password, $staff['password'])) {
            $_SESSION['staff_id'] = $staff['id'];
            $_SESSION['staff_name'] = $staff['name'];
            
            // Set cookie for 30 days if "Remember me" is checked
            if (isset($_POST['remember'])) {
                setcookie('staff_email', $email, time() + (30 * 24 * 60 * 60), '/');
                setcookie('staff_password', $password, time() + (30 * 24 * 60 * 60), '/');
            }
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials!";
        }
    } else {
        $error = "Invalid credentials!";
    }
}

// Check for cookies
if (isset($_COOKIE['staff_email']) && isset($_COOKIE['staff_password'])) {
    $email = $conn->real_escape_string($_COOKIE['staff_email']);
    $password = $conn->real_escape_string($_COOKIE['staff_password']);
    
    $sql = "SELECT * FROM staff WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        if (password_verify($password, $staff['password'])) {
            $_SESSION['staff_id'] = $staff['id'];
            $_SESSION['staff_name'] = $staff['name'];
            header("Location: dashboard.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kenyan Inventory</title>
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
                    <i class="fas fa-lock text-2xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-center mb-2 text-kenya-green font-display">
                Kenyan Inventory Login
            </h1>
            <p class="text-center text-sm text-gray-600 mb-6">Access your product management account</p>
            
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
            
            <form action="login.php" method="POST" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-envelope mr-2 text-kenya-green"></i>Email Address
                    </label>
                    <input type="email" id="email" name="email" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                           value="<?php echo isset($_COOKIE['staff_email']) ? htmlspecialchars($_COOKIE['staff_email']) : ''; ?>"
                           placeholder="your@email.com">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-key mr-2 text-kenya-green"></i>Password
                    </label>
                    <input type="password" id="password" name="password" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-kenya-green focus:ring focus:ring-kenya-green focus:ring-opacity-50 p-3 border"
                           value="<?php echo isset($_COOKIE['staff_password']) ? htmlspecialchars($_COOKIE['staff_password']) : ''; ?>"
                           placeholder="••••••••">
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-kenya-green focus:ring-kenya-green border-gray-300 rounded" <?php echo isset($_COOKIE['staff_email']) ? 'checked' : ''; ?>>
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    
                    <div class="text-sm">
                        <a href="#" class="font-medium text-kenya-green hover:text-kenya-green-dark">
                            Forgot password?
                        </a>
                    </div>
                </div>
                
                <div class="pt-4">
                    <button type="submit" class="btn-transition w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-kenya-white bg-kenya-green hover:bg-kenya-green-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kenya-green">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="register.php" class="font-medium text-kenya-green hover:text-kenya-green-dark">
                        Register here
                    </a>
                </p>
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