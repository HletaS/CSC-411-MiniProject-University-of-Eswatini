Siyabonga Hleta 202102934

# BookReviews System
A dynamic web-based book review platform that enables users to browse books, write reviews, and engage with a reading community.

##  Features
- **User Authentication System**
  - Secure login/logout functionality
  - Session management
  - User profile management

- **Book Management**
  - Browse book catalog
  - View book details

- **Community Features**
  - Community posts
  - Discussion threads
  - User interactions

- **Review System**
  - Write and edit reviews
  - Rating system

## 🛠 Technologies Used
- **Frontend**
  - HTML5
  - CSS3
  - JavaScript
  - Font Awesome 6.0.0
  - Animate.css 4.1.1

- **Backend**
  - PHP
  - MySQL
  - Apache Server

- **Data Format**
  - XML for data transfer
  - JSON support

-**DataBase Schema**

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    password VARCHAR(255),
    email VARCHAR(100)
);


CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    author VARCHAR(100),
    description TEXT,
    image VARCHAR(255)
);

CREATE TABLE community_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    author VARCHAR(50),
    content TEXT,
    timestamp DATETIME
);

##  Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server
- XAMPP Control Panel v3.3.0 or higher
- Web browser (Chrome, Firefox, or Edge)




