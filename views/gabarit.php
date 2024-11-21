<?php
require_once("lang/" . $language . ".php");
?>

<!doctype html>
<html lang=<?= $language ?>>

<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="public/styles/<?= $css ?>" />
    <link rel="stylesheet" href="public/styles/gabarit.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
</head>

<body>

    <!-- Header -->
    <?php
    if ($header) {
        include("header.php");
    }
    ?>

    <div id="container">

        <?php
        if ($header) {
            include("sidebar.php");
        }
        ?>

        <!-- #contenu -->
        <div id="content">
            <?= $contenu ?>
        </div>

    </div>
    <!-- Footer -->
    <?php
    if ($footer) {
        include("footer.php");
    }
    ?>
</body>

</html>