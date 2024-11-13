
<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Get user information from session
$username = htmlspecialchars($_SESSION["name"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BookReviews</title>
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
            align-items: center;
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

        .user-welcome {
            color: var(--text-light);
            margin-right: 1rem;
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem 5%;
            flex-grow: 1;
        }

        .dashboard-header {
            grid-column: 1 / -1;
            text-align: center;
            margin-bottom: 2rem;
        }

        .dashboard-header h2 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-card i {
            font-size: 48px;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .dashboard-card h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .dashboard-card p {
            color: #666;
            margin-bottom: 2rem;
        }

        .dashboard-card a {
            display: inline-block;
            background: var(--secondary-color);
            color: var(--text-light);
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 5px;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .dashboard-card a:hover {
            background: #c0392b;
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
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
                gap: 0.5rem;
            }

            .nav-links a {
                width: 100%;
                text-align: center;
            }

            .dashboard-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">BookReviews</a>
        <div class="nav-links">
            <span class="user-welcome">Welcome, <?php echo $username; ?>!</span>
            <a href="landing.php">Home</a>
            
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container animate__animated animate__fadeIn">
        <div class="dashboard-header">
            <h2>Your Reading Dashboard</h2>
            <p>Manage your books, reviews, and connect with other readers</p>
        </div>

        <div class="dashboard-card animate__animated animate__fadeInUp">
            <i class="fas fa-book"></i>
            <h3>My Books</h3>
            <p>View and manage the books you've added to your library.</p>
            <a href="books.php">Go to Books</a>
        </div>

        <div class="dashboard-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <i class="fas fa-star"></i>
            <h3>Reviews</h3>
            <p>Write and view reviews for the books you've read.</p>
            <a href="reviews.php">Go to Reviews</a>
        </div>

        <div class="dashboard-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <i class="fas fa-users"></i>
            <h3>Community</h3>
            <p>Explore the BookReviews community and connect with other readers.</p>
            <a href="community.php">Go to Community</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 BookReviews. All rights reserved.</p>
    </footer>
</body>
</html>