<?php
include '../backend/db.php';;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Energy News </title>
  <link rel="stylesheet" href="css/news.css">
  <script src="news-behavior.js"></script>
</head>

<body>
  <!-- Header -->
  <div class="header">
      <a href = "home.php">
        <img src="images/logo.png" alt="Energy FM 106.3 Naga Logo" class="logo">
      </a>

      <!-- Menu Icon -->
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">&#9776;</label>

    <div class="dropdown-menu">
        <a href="about.html">About</a>
        <a href="profiles.php">Profiles</a>
        <a href="programs.html">Programs</a>
        <a href="stream.html">Stream</a>
        <a href="news.php">News</a>
    </div>

      <!-- Overlay Texts -->
      <div class="header-overlay">
        <h1>ENERGY FEATURED NEWS</h1>
        <p class = "intro-news">
          Pangga, updated ka na ba? Stay in the loop with ENERGY FM's featured news! From latest happenings to must-know
          updates, we've gathered the top stories right here. Check out what's making headlines and stay informed
          anytime.
        </p>
      </div>
  </div>

  <div class="break-box"></div>

  <main>
    <!-- Featured News -->
    <div class="featurednews">

      <img src="images/ahtisa_photo.jpg" alt="Ahtisa Manalo" class="featurednews-image">

      <div class="news-content">
        <div class="featurednews-author">
          <div class="author-profile"></div>
          <p><b> Hannah Mallorca </b></p>
          <div class="ellipse"></div>
          <p> 20 minutes ago </p>
        </div>

        <h1> Ahtisa Manalo comes home to overwhelming support from fans </h1>
        <h5>
          Despite not winning the Miss Universe crown, Ahtisa Manalo felt like a queen upon coming home to overwhelming
          support from fans.
          <br><br>
          Manalo arrived in the Philippines via Terminal 1 of the Ninoy Aquino International Airport (NAIA) on Tuesday,
          Nov. 25, at around dawn, and was greeted by a crowd of fans, members of the press, and pageant vloggers,
          aiming to get a chance to see her up close.
        </h5>
      </div>

    </div>

    <!-- Latest News -->
    <h4> Latest News </h4>

    <!-- Search & Filter Section -->
    <div class="search-filter-section">

      <div class="search">
        <img src="images/search_icon.png" alt="Search Icon">
        <input type="text" placeholder="Search">
      </div>

      <button class="news-filter">
        Name ▼
      </button>
    </div>

    <!-- News Section -->
    <div class="news-section">

      <?php
      $sql = "SELECT * FROM News";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo '
              <div class="news-card">
                  <a href="' . $row["SOURCE_URL"] . '" target="_blank">
                      <img src="' . $row["HEADLINE_IMAGE_PATH"] . '" alt="News Image">
                  </a>
                  <h6>' . $row["HEADLINE"] . '</h6>
                  <div class="news-company">
                      <p><b>' . $row["ORGANIZATION"] . '</b></p>
                      <div class="ellipse"></div>
                      <p>' . $row["DATE_POSTED"] . '</p>
                  </div>
                  <p>' . $row["SUMMARY"] . '</p>
                  <div class="author-category-section">
                      <p>By: <a href="' . $row["SOURCE_URL"] . '" target="_blank">' . $row["AUTHOR"] . '</a></p>
                  </div>
              </div>';
          }
      } else {
          echo "No news inserted yet.";
      }
      ?>

      <div class="news-card">
          
        <a href = "https://newsinfo.inquirer.net/2145802/classes-suspended-shift-to-online-on-nov-25-due-to-inclement-weather" target="_blank">
            <img src="images/suspension_photo.jpg" alt="Suspension Photo">
        </a>
        <h6> Classes suspended, shifted to alternate mode in parts of PH on Nov. 25 </h6>
        <div class="news-company">
          <p><b>Inquirer Net</b></p>
          <div class="ellipse"></div>
          <p> 3 hours ago </p>
        </div>

        <p> MANILA, Philippines - Numerous local government units have either suspended classes or shifted to alternative
            delivery mode (ADM) / alternative learning modalities on Tuesday, due to the effects of Tropical Depression
            Verbena. </p>

        <div class="author-category-section">
          <p> By: <a href="https://newsinfo.inquirer.net/byline/keith-clores" target="_blank"> Keith Clores </a> </p>
          <div class="category-container">
            <p> Weather </p>
          </div>
        </div>

      </div>

      <div class="news-card">
        
        <a href = "https://newsinfo.inquirer.net/2145802/classes-suspended-shift-to-online-on-nov-25-due-to-inclement-weather" target="_blank">
            <img src="images/suspension_photo.jpg" alt="Suspension Photo">
        </a>
        <h6> Classes suspended, shifted to alternate mode in parts of PH on Nov. 25 </h6>
        <div class="news-company">
          <p><b>Inquirer Net</b></p>
          <div class="ellipse"></div>
          <p> 3 hours ago </p>
        </div>

        <p>
          MANILA, Philippines - Numerous local government units have either suspended classes or shifted to alternative
          delivery mode (ADM) / alternative learning modalities on Tuesday, due to the effects of Tropical Depression
          Verbena.
        </p>

        <div class="author-category-section">
          <p>
            By: <a href="https://newsinfo.inquirer.net/byline/keith-clores" target="_blank"> Keith Clores </a>
          </p>
          <div class="category-container">
            <p> Weather </p>
          </div>
        </div>

      </div>

    </div>

    <br>
    <br>
    <br>

  </main>

  <!-- Footer -->
  <div class="footer">
    <footer>Privacy Policy | Energy FM © 2010</footer>
  </div>

</body>

</html>