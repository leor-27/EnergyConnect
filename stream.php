<?php
include 'backend/db.php';
include 'backend/auto_ingest.php';

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

$baseUrl = "http://localhost:8000/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Streams</title>
    <link rel="stylesheet" href="frontend/css/stream.css">
    <script src = "frontend/js/stream.js"></script>
</head>
<body>
   <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" 
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0" 
        nonce="energyfm">
    </script>

    <header class="header">
        <a href = "home.php">
            <img src = "frontend/images/logo.png" alt = "Energy FM 106.3 Naga Logo" class = "logo">
        </a>

        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>

        <div class="dropdown-menu">
            <a href="about.php">About</a>
            <a href="profiles.php">Profiles</a>
            <a href="programs.php">Programs</a>
            <a href="stream.php">Stream</a>
            <a href="news.php">News</a>
        </div>

        <div class="header-overlay">
            <h1>ENERGY STREAMS</h1>
            <p class = "intro">Welcome to ENERGY Streams! Explore latest  live broadcasts and past audio highlights. 
                Replay your favorite moments, search for the songs you love, and vibe anytime you want. Tune in, pangga!</p>
        </div>
    </header>

    <div class = "break-box"></div>

    <div class="stream-section">
        <div class="harambogan-content">
            <div class="harambogan-text">
                <h2>CATCH ENERGY FM LIVE</h2>
                <p>The station is well-known in the city for its popular catchphrases, such as "Pangga, may Energy ka pa ba?" and "Basta Energy, Number 1 pirmi!". 
                    It has also been recognized several times as the Number 1 radio station in Naga.</p>
            </div>
            <div class="harambogan-video">
                <div class="fb-video-mask">
                    <div class="fb-video-cropper">
                        <div class="fb-page" 
                            data-href="https://www.facebook.com/EnergyFMNaga" 
                            data-tabs="timeline" 
                            data-width="1100" 
                            data-height="350" 
                            data-small-header="true" 
                            data-adapt-container-width="true" 
                            data-hide-cover="true" 
                            data-show-facepile="false">
                            <blockquote cite="https://www.facebook.com/EnergyFMNaga" class="fb-xfbml-parse-ignore">
                                <a href="https://www.facebook.com/EnergyFMNaga">Energy FM Naga</a>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class = "break-box"></div>

    <div class="stream-section">
        <h2 class="section-heading">STREAM THE LATEST YOUTUBE LIVESTREAMS!</h2>
        <div class="carousel">
            <?php 
            $playlist_id = "UUJRPf-4NvEbTGY-zYWcOqwg"; 

            // amount of videos to show (10)
            for ($i = 1; $i <= 10; $i++) { 
            ?>
                <div class="player-card">
                    <iframe 
                        loading="lazy"
                        width="100%" 
                        height="100%" 
                        src="https://www.youtube.com/embed?listType=playlist&list=<?= $playlist_id ?>&index=<?= $i ?>" 
                        frameborder="0" 
                        allowfullscreen>
                    </iframe>
                </div>
            <?php 
            } 
            ?>
        </div>
    </div>

    <div class="stream-section">
        <h2 class="section-heading">AUDIO BROADCASTS</h2>
        <div class="table-container">
            <div class="search-box" style="display: flex; gap: 10px; align-items: center;">
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
                <table id="broadcastTable" class="stream-table">
                    <thead>
                        <tr>
                            <th class="col-name" onclick="sortTable(0)">Name <span class="sort-icon">▼</span></th>
                            <th class="col-date" onclick="sortTable(1)">Date <span class="sort-icon">▼</span></th>
                            <th class="col-time" onclick="sortTable(2)">Time <span class="sort-icon">▼</span></th>
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

                                <td>
                                    <span class="heart-icon">♡</span>
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
