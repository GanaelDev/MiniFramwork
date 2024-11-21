<!--Header-->

<?php
include("config.php");
require_once("lang/" . $language . ".php");
?>

<div id="header">
        <h1><img src="public/images/icon.png" alt="" style="vertical-align:middle" />&nbsp;<a href="index.php">Agence de
                        Voyage
                        <span class="subtitle">1.2</span></a></h1>
</div>
<div id="headernav">
        <ul>
                <li><a href="home"><img src="public/images/house.png" alt="" /></a> <a
                                href="home"><?php echo "Home"; ?></a>
                </li>
                <?php if (isset($_SESSION['User']) && $_SESSION['User']->isAdmin()):
                        echo "<li><a href='admin'><img src='public/images/page_white_text.png' alt='' /></a> <a href='admin'>Admin Panel</a></li>";
                endif; ?>
                <li><a href=""><img src="public/images/context.png" alt="" /></a> <a
                                href=""><?php echo "Forfait"; ?></a>
                </li>
                <?php if (!isset($_SESSION['User'])):
                        echo "<li><a href='login'><img src='public/images/project.png' alt='' /></a> <a href='login'>Login</a></li>";
                endif; ?>
                </li>
                <li><a href=""><img src="public/images/textfield_rename.png" alt="" /></a> <a
                                href=""><?php echo "Searching"; ?></a>
                </li>
                <?php if (isset($_SESSION['User'])):
                        echo "<li><a href='customer'><img src='public/images/user.png' alt='' /></a> <a href='customer'>Account</a></li>";
                endif; ?>
                <?php if (isset($_SESSION['User'])):
                        echo "<li><a href='logout'><img src='public/images/door_in.png' alt='' /></a> <a href='logout'>Logout</a></li>";
                endif; ?>
        </ul>
</div>