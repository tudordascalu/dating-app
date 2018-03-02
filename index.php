<html lang="en">

<head>
    <?php require_once('./components/head.php');?>
</head>

<body>
    <?php require_once('./components/navbar.php');?>
    <div class="pages signup-page">
        <?php require_once('./components/signup.php');?>
    </div>

    <div class="pages users-page">
        <?php require_once('./components/users.php');?>
    </div>

     <div class="pages login-page">
        <?php require_once('./components/login.php');?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <script src="./assets/js/signup.js"></script>
    <script src="./assets/js/routing.js"></script>
</body>

</html>