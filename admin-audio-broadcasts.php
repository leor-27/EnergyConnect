<?php
session_start();

if (
    empty($_SESSION['logged_in']) ||
    empty($_SESSION['admin_id'])
) {
    header("Location: index.php");
    exit;
}

include 'backend/db.php';
include 'backend/auto_ingest.php';
include 'backend/config.php';

$sql = "
    SELECT 
        abl.ID,
        abl.DATE,
        abl.START_TIME,
        abl.END_TIME,
        abl.AUDIO_FILE_PATH,
        p.TITLE
    FROM Audio_Broadcast_Log abl
    LEFT JOIN Program p ON abl.PROGRAM_ID = p.id
    ORDER BY abl.DATE DESC, abl.START_TIME DESC
";

$result = $conn->query($sql);

$baseUrl = "";
?>

<!DOCTYPE html>
<html lang="en" class="admin-attach-news">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="frontend/css/admin-audio-broadcasts.css" rel="stylesheet">
    <script src="frontend/js/stream.js"></script>
</head>
<body class="admin-home">

    <?php if (isset($_GET['deleted'])): ?>
    <script>
        alert("Audio broadcast deleted successfully.");
        window.history.replaceState({}, document.title, window.location.pathname);
    </script>
    <?php endif; ?>

    <header>
        <div class="header-content">
            <section class="header-image">
                <a href="admin-home.php" class="logo-link">
                    <img src="frontend/images/logo.png" class="logo" alt="Energy FM 106.3 Naga Logo">
                </a>
            </section>
        </div>

        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>

        <div class="dropdown-menu">
            <a href="backend/logout.php">Logout</a>
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
            <a href="admin-audio-broadcasts.php" class="btn check-audio-broadcasts-button">
                <i class="fa-solid fa-file-audio"></i>  Check Audio Broadcasts
            </a>
        </div> 

        <hr>

            <div class="stream-section">
        <div class="table-container">
            <div class="search-box">
                <span class="search-icon">🔍︎</span>
                    <input type="text" id="streamSearch" onkeyup="filterTable()" placeholder="Search title or date...">

                    <select id="programFilter" onchange="filterTable()">
                        <option value="">All Programs</option>
                        <?php
                        $prog_sql = "SELECT DISTINCT TITLE FROM Program ORDER BY TITLE ASC";
                        $prog_res = $conn->query($prog_sql);
                        if ($prog_res) {
                            while($p_row = $prog_res->fetch_assoc()) {
                                echo '<option value="'.htmlspecialchars($p_row['TITLE']).'">'.htmlspecialchars($p_row['TITLE']).'</option>';
                            }
                        }
                        ?>
                </select>
            </div>

        <div class="table-wrapper">
            <table class="stream-table" id="broadcastTable">
                <thead>
                    <tr>
                        <th class="col-name" onclick="sortTable(0)" style="cursor: pointer;">
                            Name <span class="sort-icon">⇅</span>
                        </th>
                        <th class="col-date" onclick="sortTable(1)" style="cursor: pointer;">
                            Date <span class="sort-icon">⇅</span>
                        </th>
                        <th class="col-time" onclick="sortTable(2)" style="cursor: pointer;">
                            Time <span class="sort-icon">⇅</span>
                        </th>
                        <th class="col-action"></th>
                    </tr>
                </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <audio id="audio-<?= $row['ID'] ?>" preload="none">
                                        <source src="<?= $baseUrl . htmlspecialchars($row['AUDIO_FILE_PATH']) ?>" type="audio/mpeg">
                                    </audio>

                                    <div class="broadcast-title">
                                        <span class="broadcast-icon"
                                            id="icon-<?= $row['ID'] ?>"
                                            onclick="togglePlay(<?= $row['ID'] ?>)">
                                            ▶
                                        </span>

                                        <span class="broadcast-text">
                                            <?= htmlspecialchars($row['TITLE'] ?? 'No Specific Program') ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="center-align">
                                    <?= date("M d, Y", strtotime($row['DATE'])) ?>
                                </td>

                                <td class="center-align">
                                    <?= date("g:i A", strtotime($row['START_TIME'])) ?>
                                    –
                                    <?= date("g:i A", strtotime($row['END_TIME'])) ?>
                                </td>

                                <td class="delete-icon center-align">
                                    <?php if ((int)$_SESSION['admin_id'] === SUPER_ADMIN_ID): ?>
                                        <a href="admin-delete-audio-broadcast.php?id=<?= $row['ID'] ?>"
                                        onclick="return confirm('Are you sure you want to delete this audio broadcast?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="center-align">
                                No audio broadcasts available.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
                    </div>

    <div class = "footer">
        <footer>Privacy Policy | Energy FM © 2025</footer>
    </div>
    
    </body>
    </html>