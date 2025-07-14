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
            $error = "Invalid password!";
        }
    } else {
        $error = "Email not found!";
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
    <title>Login - Product Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6 text-blue-600">Staff Login</h1>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" required 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           value="<?php echo isset($_COOKIE['staff_email']) ? $_COOKIE['staff_email'] : ''; ?>">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" required 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           value="<?php echo isset($_COOKIE['staff_password']) ? $_COOKIE['staff_password'] : ''; ?>">
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="form-checkbox" <?php echo isset($_COOKIE['staff_email']) ? 'checked' : ''; ?>>
                        <span class="ml-2 text-gray-700 text-sm">Remember me</span>
                    </label>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200">
                        Sign In
                    </button>
                    <a href="register.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Register here
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>