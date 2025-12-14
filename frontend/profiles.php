<?php
include '../backend/db.php';
?>

<?php
$sql = "SELECT * FROM DJ_Profile ORDER BY ID ASC";
$result = $conn->query($sql);

$djs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $djs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Profiles</title>
    <link href="css/profiles.css" rel="stylesheet">
    <script src = "js/profiles.js"></script>
</head>
<body class="profiles-page">

    <header class="header-profiles">
        <a href="home.php">
            <img src="images/logo.png" alt="Energy FM 106.3 Naga Logo" class="logo">
        </a>

        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>

        <div class="dropdown-menu">
            <a href="about.html">About</a>
            <a href="profiles.php">Profiles</a>
            <a href="programs.html">Programs</a>
            <a href="stream.html">Stream</a>
            <a href="news.php">News</a>
        </div>

        <div class="banner-profiles">
            <div class="hosts-profile">

            <?php $total = count($djs); ?>

                <?php foreach ($djs as $index => $dj): ?>
                <section class="dj-section">
                    <button class="left-button">
                        <img src="images/leftbutton.png" alt="Left Button">
                    </button>

                    <img class="dj-image"
                        src="<?= $dj['IMAGE_PATH']; ?>"
                        alt="<?= htmlspecialchars($dj['STAGE_NAME'] ?: $dj['REAL_NAME']); ?>">

                    <?php if ($index < $total - 1): ?>
                        <button class="right-button">
                            <img src="images/rightbutton.png" alt="Right Button">
                        </button>
                    <?php endif; ?>
                </section>
                <?php endforeach; ?>

                <!-- Main DJs image -->
                <section id="main">
                    <img style="width: 918.5px; height: auto;" src="images/djs_profiles.png" class="alldjs" alt="Main DJs Photo">
                </section>

            </div>
        </div>
    </header>

    <div class="break-box"></div>

    <div class="description">

        <!-- MAIN DESCRIPTION (must exist) -->
        <section id="main-description">
            <h1>DJ PROFILES</h1>
            <p>
                Meet our DJs and Anchors - the voices who bring out the energy,
                the vibe, and the fun to all your favorite shows.
            </p>
        </section>

        <!-- DJ DESCRIPTIONS FROM DB -->
        <?php foreach ($djs as $dj): ?>
            <section class="dj-description">
                <h1 class="dj-name">
                    <?= strtoupper(htmlspecialchars($dj['STAGE_NAME'] ?: $dj['REAL_NAME'])); ?>
                </h1>

                <?php if (!empty($dj['STAGE_NAME'])): ?>
                    <h2><?= $dj['REAL_NAME']; ?></h2>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>

    </div>

    <div class="footer">
        <footer>Privacy Policy | Energy FM © 2025</footer>
    </div>

    <!-- updates image & descriptions -->
    <!-- <script>
        const djSections = document.querySelectorAll(".dj-section");
        const mainImage = document.querySelector(".alldjs");
        const mainDescription = document.querySelector("#main-description");
        const descriptions = document.querySelectorAll(".dj-description");

        const totalDJs = djSections.length;
        let currentIndex = -1; // -1 = main view

        // hide all DJ sections & descriptions initially
        djSections.forEach(sec => sec.style.display = "none");
        descriptions.forEach(d => d.style.display = "none");

        // show main image and description by default
        mainImage.style.display = "block";
        mainDescription.style.display = "block";

        function showDJ(index) {
            mainImage.style.display = "none";
            mainDescription.style.display = "none";
            djSections.forEach(sec => sec.style.display = "none");
            descriptions.forEach(d => d.style.display = "none");

            djSections[index].style.display = "flex";
            descriptions[index].style.display = "block";
            currentIndex = index;
        }

        function showMain() {
            mainImage.style.display = "block";
            mainDescription.style.display = "block";
            djSections.forEach(sec => sec.style.display = "none");
            descriptions.forEach(d => d.style.display = "none");
            currentIndex = -1;
        }

        // clicking main image goes to first DJ
        mainImage.addEventListener("click", () => showDJ(0));

        djSections.forEach((sec, i) => {
            const left = sec.querySelector(".left-button");
            const right = sec.querySelector(".right-button");

            if (left) left.addEventListener("click", () => {
                if (i === 0) showMain();
                    else showDJ(i - 1);
                });

            if (right) right.addEventListener("click", () => {
                if (i < totalDJs - 1) showDJ(i + 1);
            });
        });
    </script> -->
</body>
</html>