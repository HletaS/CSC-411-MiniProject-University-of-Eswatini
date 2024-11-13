<?php
include'config.php';
// Retrieve book data from the database
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

// Build the XML data structure
$books_xml = new SimpleXMLElement('<books></books>');

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $book = $books_xml->addChild('book');
        $book->addChild('title', $row['title']);
        $book->addChild('author', $row['author']);
        $book->addChild('description', $row['description']);
        $book->addChild('image', $row['image']);
    }
} else {
    $book = $books_xml->addChild('book');
    $book->addChild('title', 'No books found');
    $book->addChild('author', '');
    $book->addChild('description', '');
    $book->addChild('image', 'https://via.placeholder.com/150x225.png?text=No+Books');
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books - BookReviews</title>
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

        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .book-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-5px);
        }

        .book-card img {
            width: 150px;
            height: 225px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .book-card h3 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .book-card p {
            color: #666;
            margin-bottom: 1rem;
        }

        .book-card a {
            display: inline-block;
            background: var(--secondary-color);
            color: var(--text-light);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background 0.3s ease;
        }

        .book-card a:hover {
            background: #c0392b;
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
        <h1>My Books</h1>
        <div class="book-list">
            <?php
            // Iterate through the books XML and display the book cards
            foreach ($books_xml->book as $book) {
                echo '<div class="book-card">';
                echo '<img src="' . $book->image . '" alt="' . $book->title . '">';
                echo '<h3>' . $book->title . '</h3>';
                echo '<p>by ' . $book->author . '</p>';
                echo '<p>' . $book->description . '</p>';
                echo '<a href="#">View Details</a>';
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