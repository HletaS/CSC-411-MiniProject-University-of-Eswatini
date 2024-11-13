<?php
include 'config.php';

// Initialize variables
$name = $email = $password = "";
$name_err = $email_err = $password_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name";
    } else {
        $name = trim($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $name_err = "Only letters and white space allowed";
        }
    }
    
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM user_registration WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["email"]);
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->num_rows > 0) {
                    $email_err = "This email is already registered";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Check input errors before inserting into database
    if (empty($name_err) && empty($email_err) && empty($password_err)) {
        
        // Prepare an insert statement
        $sql = "INSERT INTO user_registration (name, email, password) VALUES (?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_name, $param_email, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Set a success message in session
                session_start();
                $_SESSION['registration_success'] = "Registration successful! Please login with your credentials.";
                
                // Redirect to login page
                header("location: login.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    
    // Close database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - BookReviews</title>
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
            margin: 2rem auto;
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

        .form-group .error-message {
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-group input.error {
            border-color: var(--secondary-color);
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 35px;
            cursor: pointer;
            color: #666;
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .login-link a:hover {
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
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">BookReviews</a>
        <div class="nav-links">
            <a href="landing.php">Home</a>
            <a href="login.php">Login</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-header">
            <i class="fas fa-user-plus"></i>
            <h1>Create an Account</h1>
            <p>Join our community of book lovers</p>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="animate__animated animate__fadeIn">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Name</label>
                <input type="text" name="name" class="<?php echo (!empty($name_err)) ? 'error' : ''; ?>" value="<?php echo $name; ?>">
                <?php if(!empty($name_err)): ?>
                    <span class="error-message"><?php echo $name_err; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" class="<?php echo (!empty($email_err)) ? 'error' : ''; ?>" value="<?php echo $email; ?>">
                <?php if(!empty($email_err)): ?>
                    <span class="error-message"><?php echo $email_err; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" class="<?php echo (!empty($password_err)) ? 'error' : ''; ?>">
                <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                <?php if(!empty($password_err)): ?>
                    <span class="error-message"><?php echo $password_err; ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-btn">Sign Up</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Log In</a>
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