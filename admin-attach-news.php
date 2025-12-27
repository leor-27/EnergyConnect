<?php
include 'backend/db.php';
?>

<!DOCTYPE html>
<html lang="en" class="admin-attach-news">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="frontend/css/admin-attach-news.css" rel="stylesheet">
</head>

<body class="admin-home">
    <header>
        <div class="header-content">
            <section class="header-image">
                <a href="admin-home.php" class="logo-link">
                    <img src="frontend/images/logo.png" class="logo" alt="Energy FM Logo">
                </a>
            </section>
        </div>

        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>

        <div class="dropdown-menu">
            <a href="index.php">Logout</a>
        </div>
    </header>


    <div class="dashboard-content">
        <h1>ADMIN DASHBOARD</h1>

        <div class="admin-buttons">
            <a href="admin-attach-news.php" class="btn attach-news-button">
                <i class="fas fa-paperclip"></i> Attach News
            </a>
            <a href="admin-add-programs.php" class="btn add-programs-button">
                <i class="fas fa-plus"></i> Add Programs
            </a>
        </div>

        <hr>

        <div class="form-container">
            <form>
                <div class="news-link-container">
                    <input type="text" id="news-link" name="news-link" placeholder="Attach News Link Here">

                    <select id="filter-select" class="filter-dropdown-btn">
                        <option value="category">News Category</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="politics">Politics</option>
                        <option value="sports">Sports</option>
                        <option value="lifestyle">Lifestyle</option>
                        <option value="technology">Technology</option>
                    </select>
                </div>

                <div class="two-column-container">

                    <div class="left-column">
                        <div class="image-upload-container">
                            <label for="image-file" id="image-placeholder">
                                Choose File
                            </label>
                            <input type="file" id="image-file" name="image" accept="image/*" style="display: none;">
                        </div>
                    </div>

                    <div class="right-column">
                        <input type="text" id="headline" name="headline" placeholder="Headline">
                        <input type="text" id="author" name="author" placeholder="Author/s Name">
                        <input type="text" id="newsorg" name="newsorg" placeholder="News Organization/Company">
                        <textarea id="description" name="description" placeholder="Description"></textarea>

                        <button type="submit" class="add-button"> <i class="fas fa-plus"></i> Add</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <footer>
        Privacy Policy | Energy FM © 2025
    </footer>
</body>

</html> 