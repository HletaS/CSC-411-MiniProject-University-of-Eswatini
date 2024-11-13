<?php
include'config.php';
// Retrieve community data from the database
$sql = "SELECT * FROM community_posts";
$result = $conn->query($sql);

// Build the XML data structure
$community_xml = new SimpleXMLElement('<community_posts></community_posts>');

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $post = $community_xml->addChild('post');
        $post->addChild('title', $row['title']);
        $post->addChild('author', $row['author']);
        $post->addChild('content', $row['content']);
        $post->addChild('timestamp', $row['timestamp']);
    }
} else {
    $post = $community_xml->addChild('post');
    $post->addChild('title', 'No community posts found');
    $post->addChild('author', '');
    $post->addChild('content', '');
    $post->addChild('timestamp', '');
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community - BookReviews</title>
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

        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem 5%;
            flex-grow: 1;
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
            }

            .nav-links {
                display: none;
            }

            .dashboard-container {
                grid-template-columns: 1fr;
            }
        }
        /* Reuse the CSS styles from the Dashboard page */
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

        .community-post .timestamp {
            color: #999;
            font-size: 0.9rem;
            text-align: right;
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
        <h1>BookReviews Community</h1>
        <div class="community-posts">
            <?php
            // Iterate through the community posts XML and display the posts
            foreach ($community_xml->post as $post) {
                echo '<div class="community-post">';
                echo '<h3>' . $post->title . '</h3>';
                echo '<p>by ' . $post->author . '</p>';
                echo '<p>' . $post->content . '</p>';
                echo '<div class="timestamp">' . $post->timestamp . '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 BookReviews. All rights reserved.</p>
    </footer>
</body>
</html>