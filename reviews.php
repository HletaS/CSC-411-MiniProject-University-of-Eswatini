<?php
include 'config.php';

// Retrieve review data from the database
$stmt = $conn->prepare("SELECT r.*, b.title as book_title
                       FROM reviews r
                       JOIN books b ON r.book_id = b.id");

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error executing statement: " . $stmt->error);
}

$reviews_html = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews_html .= '<div class="community-post">';
        $reviews_html .= '<h3>' . htmlspecialchars($row['book_title']) . '</h3>';
        $reviews_html .= '<p>Reviewed by ' . htmlspecialchars($row['reviewer']) . '</p>';
        $reviews_html .= '<div class="review-rating">' . str_repeat('<i class="fas fa-star"></i>', (int)$row['rating']) . '</div>';
        $reviews_html .= '<p>' . htmlspecialchars($row['content']) . '</p>';
        $reviews_html .= '<div class="timestamp">Reviewed on ' . date('F j, Y', strtotime($row['created_at'])) . '</div>';
        $reviews_html .= '</div>';
    }
} else {
    $reviews_html = '<div class="community-post">';
    $reviews_html .= '<h3>No reviews found</h3>';
    $reviews_html .= '<p></p>';
    $reviews_html .= '<div class="review-rating"></div>';
    $reviews_html .= '<p></p>';
    $reviews_html .= '<div class="timestamp"></div>';
    $reviews_html .= '</div>';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - BookReviews</title>
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

        .content-container {
            padding: 2rem 5%;
        }

        .community-post {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .community-post h3 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .community-post p {
            color: #666;
            margin-bottom: 1rem;
        }

        .review-rating {
            color: var(--accent-color);
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .community-post .timestamp {
            color: #999;
            font-size: 0.9rem;
            text-align: right;
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
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="content-container">
        <h1>Book Reviews</h1>
        <div class="review-list">
            <?php echo $reviews_html; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 BookReviews. All rights reserved.</p>
    </footer>
</body>
</html>