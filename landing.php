<?php
session_start();
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookReviews - Share Your Reading Journey</title>
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
        }

        .navbar {
            background: var(--primary-color);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
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

        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5%;
            background: url('/assets/images/books-bg.jpg') center/cover;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: var(--text-light);
            max-width: 800px;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            animation: fadeInDown 1s ease;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            animation: fadeIn 1.5s ease;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--secondary-color);
            color: var(--text-light);
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid var(--text-light);
            color: var(--text-light);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .features {
            padding: 5rem 5%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            background: white;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        footer {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 2rem 5%;
            text-align: center;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">BookReviews</a>
        <div class="nav-links">
            <a href="landing.php">Home</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>Share Your Reading Journey</h1>
            <p>Join our community of book lovers. Rate, review, and discover your next favorite book.</p>
            <div class="cta-buttons">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="signup.php" class="btn btn-primary">Get Started</a>
                    <a href="login.php" class="btn btn-secondary">Login</a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="feature-card">
            <i class="fas fa-book feature-icon"></i>
            <h3>Track Your Books</h3>
            <p>Keep a record of all the books you've read and want to read. Never lose track of your reading journey.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-star feature-icon"></i>
            <h3>Rate & Review</h3>
            <p>Share your thoughts and rate books to help others discover great reads. Your opinion matters!</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-users feature-icon"></i>
            <h3>Join the Community</h3>
            <p>Connect with fellow book lovers, share recommendations, and discover new perspectives.</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 BookReviews. All rights reserved.</p>
    </footer>
</body>
</html>