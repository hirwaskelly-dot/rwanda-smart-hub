<?php

require_once "../languages/language.php";

?>
<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>About - Rwanda Smart Hub</title>

    <link rel="stylesheet"
          href="../assets/style.css">

</head>

<body>

<header>

    <div class="container">

        <nav class="navbar">

            <div class="logo">
                🇷🇼 Rwanda Smart Hub
            </div>

            <div class="nav-links">

               <a href="index.php">
    <?php echo $translations["home"]; ?>
</a>

<a href="index.php#categories">
    <?php echo $translations["categories"]; ?>
</a>

<a href="index.php#information">
    <?php echo $translations["information"]; ?>
</a>

<a href="about.php">
    <?php echo $translations["about"]; ?>
</a>
<div class="language-links">

    <a href="about.php?lang=rw">
         RW
    </a>

    <a href="about.php?lang=en">
         ENG
    </a>

    <a href="about.php?lang=fr">
        FR
    </a>

</div>
            </div>

        </nav>

    </div>

</header>


<main>

    <section class="hero">

        <div class="container">

            <h1>
                About Rwanda Smart Hub
            </h1>

            <p>
                A prototype digital information platform
                designed to help people find useful
                information across Rwanda.
            </p>

        </div>

    </section>


    <section class="container">

        <div class="information-card">

            <h1>
    <?php echo $translations["our_purpose"]; ?>
</h1>

<p>
    <?php echo $translations["purpose_text"]; ?>
</p>

<h2>
    <?php echo $translations["how_it_works"]; ?>
</h2>

<p>
    <?php echo $translations["how_it_works_text"]; ?>
</p>

<h2>
    <?php echo $translations["important_notice"]; ?>
</h2>

<p>
    <?php echo $translations["notice_text"]; ?>
</p>


            <br>

            <a href="index.php">
                ← Back to Home
            </a>

        </div>

    </section>

</main>


<footer>

    <p>
        © <?php echo date("Y"); ?>
        Rwanda Smart Hub
    </p>

    <p>
    <?php echo $translations["prototype_notice"]; ?>
</p>

</footer>

</body>

</html>