<html lang="en">

<head>
    <?php require_once('./components/head.php');?>
</head>

<body>
    <div class="modals">
        <?php require_once('./components/modals.php');?>
    </div>
    <div class="wrapper">
        <div class="navbar-container">
            <?php require_once('./components/navbar.php');?>
        </div>

        <div style="display: none" class="navbar-admin">
            <?php require_once('./components/navbar-admin.php');?>
        </div>
        
        <div style="display: none" class="pages signup-page">
            <?php require_once('./components/signup.php');?>
        </div>

        <div class="pages users-page">
            <?php require_once('./components/users.php');?>
        </div>

        <div class="pages admin-page">
            <?php require_once('./components/admin.php');?>
        </div>
        
        <div class="pages matches-page">
            <?php require_once('./components/matches.php');?>
        </div>

        <div class="pages login-page">
            <?php require_once('./components/login.php');?>
        </div>

        <div class="pages tinder-page">
            <?php require_once('./components/tinder.php');?>
        </div>
    </div>
    <?php require_once('./components/footer.php');?>
</body>

</html>