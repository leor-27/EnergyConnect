<?php
include 'backend/db.php';

/* Fetch categories */
$categories = [];
$catSql = "SELECT ID, NAME FROM Category ORDER BY NAME ASC";
$catResult = $conn->query($catSql);

if ($catResult && $catResult->num_rows > 0) {
    while ($cat = $catResult->fetch_assoc()) {
        $categories[] = $cat;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $source_url   = trim($_POST['news-link'] ?? '');
    $headline     = trim($_POST['headline'] ?? '');
    $author       = trim($_POST['author'] ?? '');
    $organization = trim($_POST['newsorg'] ?? '');
    $summary      = trim($_POST['description'] ?? '');
    $imagePath    = trim($_POST['image_url'] ?? '');
    $categoryIds  = $_POST['categories'] ?? [];

    /* Backend validation */
    if (
        empty($source_url) ||
        empty($author) ||
        empty($organization) ||
        empty($summary)
    ) {
        die("Required fields are missing.");
    }

    $sql = "INSERT INTO News 
        (SOURCE_URL, HEADLINE_IMAGE_PATH, HEADLINE, AUTHOR, ORGANIZATION, SUMMARY, DATE_POSTED)
        VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssss",
        $source_url,
        $imagePath,
        $headline,
        $author,
        $organization,
        $summary
    );

    if ($stmt->execute()) {
        $newsId = $conn->insert_id;

        if (!empty($categoryIds)) {

    $catStmt = $conn->prepare(
        "INSERT INTO News_Category (NEWS_ID, CATEGORY_ID) VALUES (?, ?)"
    );

    foreach ($categoryIds as $categoryId) {
        $categoryId = (int)$categoryId; // safety
        $catStmt->bind_param("ii", $newsId, $categoryId);
        $catStmt->execute();
    }

    $catStmt->close();
}

        header("Location: admin-home.php?added=1");
        exit;
    } else {
        echo "Error adding news.";
    }
}
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

    <?php if (isset($_GET['added'])): ?>
    <script>
        alert("News article added successfully.");
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
    <?php endif; ?>

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
            <form method="POST">
<div class="news-link-container">
    <input type="text" id="news-link" name="news-link" placeholder="Attach News Link Here" required>
</div>

<div class="two-column-container">
<div class="left-column">
    <span class="category-placeholder">Category/s:</span>
    
    <div class="categories-checkboxes">
        <?php foreach ($categories as $category): ?>
            <label class="category-item">
                <input type="checkbox" name="categories[]" value="<?= $category['ID'] ?>">
                <?= htmlspecialchars($category['NAME']) ?>
            </label>
        <?php endforeach; ?>
    </div>
    
    <input type="text" id="image_url" name="image_url" placeholder="Image URL (https://...)">
</div>

    <div class="right-column">
        <input type="text" id="headline" name="headline" placeholder="Headline">
        <input type="text" id="author" name="author" placeholder="Author/s Name">
        <input type="text" id="newsorg" name="newsorg" placeholder="News Organization/Company" required>
        <textarea id="description" name="description" placeholder="Description" required></textarea>

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