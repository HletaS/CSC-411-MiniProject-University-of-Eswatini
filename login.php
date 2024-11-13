
<?php
// Initialize the session
session_start();

// Check if user is already logged in - keep this at the top to prevent unnecessary form display
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

// Include database connection
require_once "config.php";

// Initialize variables
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Only process form if it was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    try {
        // Validate email
        if(empty(trim($_POST["email"]))){
            $email_err = "Please enter your email.";
        } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $email_err = "Please enter a valid email address.";
        } else{
            $email = trim($_POST["email"]);
        }
        
        // Validate password
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter your password.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        // Validate credentials
        if(empty($email_err) && empty($password_err)){
            // Prepare a select statement
            $sql = "SELECT id, name, email, password FROM user_registration WHERE email = ?";
            
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("s", $param_email);
                $param_email = $email;
                
                if($stmt->execute()){
                    $stmt->store_result();
                    
                    if($stmt->num_rows == 1){
                        $stmt->bind_result($id, $name, $email, $hashed_password);
                        if($stmt->fetch()){
                            if(password_verify($password, $hashed_password)){
                                // Password is correct, start a new session
                                session_regenerate_id(true); // Prevent session fixation attacks
                                
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["name"] = $name;
                                $_SESSION["email"] = $email;
                                
                                // If remember me is checked, set cookie
                                if(isset($_POST["remember"]) && $_POST["remember"] == "on") {
                                    $token = bin2hex(random_bytes(32));
                                    setcookie("remember_token", $token, time() + (86400 * 30), "/", "", true, true); // 30 days
                                    // You should also store this token in your database
                                }
                                
                                // Redirect user to dashboard
                                header("location: dashboard.php");
                                exit();
                            } else{
                                $login_err = "Invalid email or password.";
                            }
                        }
                    } else{
                        // Use same message as password failure for security
                        $login_err = "Invalid email or password.";
                    }
                } else{
                    $login_err = "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    } catch (Exception $e) {
        $login_err = "An error occurred. Please try again later.";
        // Log the error securely
        error_log("Login error: " . $e->getMessage());
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BookReviews</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #3498db;
            --text-light: #ecf0f1;
            --text-dark: #2c3e50;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .success-alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #d4edda;
    border-radius: 4px;
    color: #155724;
    background-color: #d4edda;
}

        .navbar {
            background: var(--primary-color);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo {
            color: var(--text-light);
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: var(--text-light);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .nav-links a:hover {
            background: var(--accent-color);
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            padding: 40px;
            margin: auto;
            flex-grow: 1;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header i {
            font-size: 48px;
            color: var(--accent-color);
            margin-bottom: 10px;
        }

        .form-header h1 {
            color: var(--text-dark);
            font-size: 24px;
            margin-bottom: 5px;
        }

        .form-header p {
            color: #666;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 35px;
            cursor: pointer;
            color: #666;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #666;
        }

        .forgot-password {
            color: var(--accent-color);
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: var(--secondary-color);
            color: var(--text-light);
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #c0392b;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .register-link a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        footer {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 2rem 5%;
            text-align: center;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }
        }
        .error-alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
            background-color: #f8d7da;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">BookReviews</a>
        <div class="nav-links">
            <a href="landing.php">Home</a>
            <a href="signup.php">Sign Up</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-header animate__animated animate__fadeIn">
            <i class="fas fa-book-open"></i>
            <h1>Welcome Back</h1>
            <p>Sign in to continue your reading journey</p>
        </div>
        
        <?php 
        if(isset($_SESSION['registration_success'])) {
            echo '<div class="success-alert animate__animated animate__fadeIn">' . htmlspecialchars($_SESSION['registration_success']) . '</div>';
            unset($_SESSION['registration_success']);
        }

        if(!empty($login_err)){
            echo '<div class="error-alert animate__animated animate__fadeIn">' . htmlspecialchars($login_err) . '</div>';
        }        
        ?>

       <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="animate__animated animate__fadeIn">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo htmlspecialchars($email); ?>" required>
                <?php if(!empty($email_err)): ?>
                    <div class="invalid-feedback"><?php echo htmlspecialchars($email_err); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" 
                       class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                <?php if(!empty($password_err)): ?>
                    <div class="invalid-feedback"><?php echo htmlspecialchars($password_err); ?></div>
                <?php endif; ?>
            </div>

            <div class="remember-forgot">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
                <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="submit-btn">Sign In</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 BookReviews. All rights reserved.</p>
    </footer>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.target;
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>