<html lang="en">

<head>
    <?php require_once('./components/head.php');?>
</head>

<body>
    <header>
        <ul>
            <li id="navSignup">SIGNUP</li>
            <li id="navUsers">USERS</li>
        </ul>
    </header>
    <div class="signup-page">
        <?php require_once('./components/signup.php');?>
    </div>

    <div class="users-page">
        <?php require_once('./components/users.php');?>
    </div>

     <div class="login-page">
        <?php require_once('./components/login.php');?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
    <!-- <script src="./assets/js/main.js"></script> -->
    <script src="./assets/js/signup.js"></script>
</body>

</html>