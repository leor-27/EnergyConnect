<?php
include 'backend/db.php';

$programs = $conn->query("
    SELECT 
        p.ID,
        p.TITLE,
        p.START_TIME,
        p.END_TIME,
        p.DESCRIPTION,
        p.TYPE,
        GROUP_CONCAT(
            DISTINCT COALESCE(d.STAGE_NAME, UPPER(d.REAL_NAME))
            SEPARATOR ', '
        ) AS HOSTS,
        GROUP_CONCAT(DISTINCT dt.DAY_TYPE ORDER BY dt.ID SEPARATOR ', ') AS DAY_TYPES
    FROM Program p
    LEFT JOIN Program_Anchor_Assignment paa
        ON p.ID = paa.PROGRAM_ID
    LEFT JOIN DJ_Profile d
        ON paa.DJ_ID = d.ID
    LEFT JOIN Program_Day_Type pdt
        ON p.ID = pdt.PROGRAM_ID
    LEFT JOIN Day_Type dt
        ON pdt.DAY_TYPE_ID = dt.ID
    GROUP BY p.ID
    ORDER BY p.TITLE ASC
");

$dj_list = $conn->query("SELECT * FROM DJ_Profile ORDER BY STAGE_NAME ASC");

if (!$dj_list) {
    die("Query failed: " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = mb_strtoupper(trim($_POST['title']), 'UTF-8');
    $type        = $_POST['type'];
    $start_time  = $_POST['start_time'];
    $end_time    = $_POST['end_time'];
    $description = $_POST['description'];

    $day_types = $_POST['day_types'] ?? [];
    $djs       = $_POST['djs'] ?? [];

    if ($type === "WITH DJ/HOST" && empty($djs)) {
        die("Please select at least one DJ/Host.");
    }

    // 1️⃣ Insert Program
    $sql = "INSERT INTO Program (TITLE, TYPE, START_TIME, END_TIME, DESCRIPTION)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $type, $start_time, $end_time, $description);

    if (!$stmt->execute()) {
        die("Error adding program.");
    }

    // Get the new program ID
    $program_id = $stmt->insert_id;
    $stmt->close();

    // 2️⃣ Insert Day Types
    if (!empty($day_types)) {
        $stmtDay = $conn->prepare(
            "INSERT INTO Program_Day_Type (PROGRAM_ID, DAY_TYPE_ID) VALUES (?, ?)"
        );

        foreach ($day_types as $day_type_id) {
            $stmtDay->bind_param("ii", $program_id, $day_type_id);
            $stmtDay->execute();
        }
        $stmtDay->close();
    }

    // 3️⃣ Insert DJs (only if WITH DJ/HOST)
    if ($type === "WITH DJ/HOST" && !empty($djs)) {
        $stmtDJ = $conn->prepare(
            "INSERT INTO Program_Anchor_Assignment (PROGRAM_ID, DJ_ID)
             VALUES (?, ?)"
        );

        foreach ($djs as $dj_id) {
            $stmtDJ->bind_param("ii", $program_id, $dj_id);
            $stmtDJ->execute();
        }
        $stmtDJ->close();
    }

    // 4️⃣ Redirect
    header("Location: admin-add-programs.php?added=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="frontend/css/admin-add-programs.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src = "frontend/js/admin-add-programs.js"></script>
</head>

<body class="admin-home">

    <?php if (isset($_GET['added'])): ?>
    <script>
        alert("Program added successfully.");
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
    <script>
        alert("Program deleted successfully.");
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
    <?php endif; ?>

    <header>
        <div class="header-content">
            <div class="header-left">
                <a href="admin-home.php" class="logo-link">
                    <img src="frontend/images/logo.png" class="logo" alt="Energy FM 106.3 Naga Logo">
                </a>
            </div>

            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="menu-icon">&#9776;</label>

            <div class="dropdown-menu">
                <a href="index.php">Logout</a>
            </div>
        </div>
    </header>

    <main class="dashboard-content">
        <h1>ADMIN DASHBOARD</h1>
        
        <div class="admin-buttons">
            <a href="admin-attach-news.php" class="btn attach-news-button">
                <i class="fas fa-paperclip"></i> Attach News </a>
                
            <a href="admin-add-programs.php" class="btn add-programs-button">
                <i class="fas fa-plus"></i> Add Programs </a>
        </div>
        
        <hr>

        <section class="program-list-section">
            <div class="program-list">

            <?php while ($row = $programs->fetch_assoc()): ?>

                <div class="program-card">
                    <div class="card-details">
                        <div class="card-left-info">
                            <h3 class="program-title"><?= htmlspecialchars($row['TITLE']) ?></h3>
                            <p class="schedule">
                                <?= date("g:i A", strtotime($row['START_TIME'])) ?>
                                –
                                <?= date("g:i A", strtotime($row['END_TIME'])) ?>
                            </p>
                                <p class="hosts">
                                    <?= $row['HOSTS'] ? htmlspecialchars($row['HOSTS']) : 'MUSIC ONLY' ?>
                                </p>
                                <?php if (!empty($row['DAY_TYPES'])): ?>
                                    <p class="day-type"><?= htmlspecialchars($row['DAY_TYPES']) ?></p>
                                <?php endif; ?>
                        </div>

                        <div class="card-description">
                            <p><?= htmlspecialchars($row['DESCRIPTION']) ?></p>
                        </div>
                    </div>

                    <div class="card-actions card-actions-news">
                        <a href="admin-edit-program.php?id=<?= $row['ID'] ?>" class="edit-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <a href="admin-delete-program.php?id=<?= $row['ID'] ?>"
                        onclick="return confirm('Are you sure you want to delete this program?');"
                        class="delete-icon">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>

            <?php endwhile; ?>

            </div>
        </section>

        <section class="add-item-form">
            <form method="POST">
                <div class="form-row form-top-row">

                <input type="text" id="description" name="description" placeholder="Title" required>
                    
                    <div class="input-group-left">
                        <div class="horizontal-align-row">
                            <textarea id="headline" name="title" placeholder="Description" required></textarea>
                            <div class="checkbox-container">
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="day_types[]" value="1"> WEEKDAYS</label>
                                    <label><input type="checkbox" name="day_types[]" value="2"> SAT</label>
                                    <label><input type="checkbox" name="day_types[]" value="3"> SUN</label>
                                </div>
                            </div>
                        </div>

                        <div class="horizontal-align-row">
                            <div class="form-bottom-row">
                                <input type="time" name="start_time" required>
                                <p class="to">-</p>
                                <input type="time" name="end_time" required>
                                <select id="type-selector" name="type" onchange="toggleDJFields()">
                                    <option value="" disabled selected hidden>Type</option>
                                    <option value="MUSIC ONLY">MUSIC ONLY</option>
                                    <option value="WITH DJ/HOST">WITH DJ/HOST</option>
                                </select>
                            </div>

                            <div id="dj-selection-container" class="checkbox-container disabled-dj">
                                <div class="checkbox-group dj-list-scroll">
                                    <?php while($dj = $dj_list->fetch_assoc()): ?>
                                        <label>
                                            <input type="checkbox" class="dj-checkbox" name="djs[]" value="<?= $dj['ID'] ?>" disabled> 
                                            <?= htmlspecialchars($dj['STAGE_NAME'] ?: strtoupper($dj['REAL_NAME'])) ?>
                                        </label>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="add-button">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </form>
        </section>

    </main>
    
    <footer>
        Privacy Policy | Energy FM © 2025
    </footer>

</body>
</html> 