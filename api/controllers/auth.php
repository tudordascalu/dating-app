<?php
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../vendor/autoload.php';
    
    function getUsers($sajUsers) {
        echo '{"status":"success", "message":"user logged in", "data":'.$sajUsers.'}';
        exit;
    }

    function login($ajUsers, $db) {
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        if($sEmail == 'admin@gmail.com' && $sPassword == 'admin') {
            $_SESSION['asd321'] = "logged in";
            $sjAdmin = '{"id":"asd321","email":"admin@gmail.com","password":"pass","role":"admin","verified":"1"}';
            $jAdmin = json_decode($sjAdmin);
            sendResponse(201, 'admin logged in', $jAdmin);
        }
        dbLoginUser($sEmail, $sPassword, $db);
        sendResponse(400, 'user login', null);
    }

    function logout() {
        $sId =  verifyLogin();
        unset($_SESSION[$sId]);

        echo '{"status":"success", "message":"user logged out", "data":'.$sId.'}';
        exit;
    }
 
    function signup($ajUsers, $db) {
        $sFirstName = $_POST['firstName'];
        $sLastName = $_POST['lastName'];
        $iAge = $_POST['age'];
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        $aImage = $_FILES['image']; 
        $iGender = $_POST['gender'];
        $iInterest = $_POST['interest'];
        $sDescription = $_POST['description'];

        if(!$sFirstName || !$sLastName || !$iAge || !$sEmail || !$sPassword || !$aImage['tmp_name']) {
            sendResponse(400, "make sure you fill up all the required fields", null);
        }
  
        if(strlen($sFirstName) > 25 || strlen($sFirstName) < 2) {
            sendResponse(400, "first name length is not correct", null);
        }

        if(strlen($sLastName) > 25 || strlen($sLastName) < 2) {
            sendResponse(400, "last name length is not correct", null);
        }

        if(!filter_var($iAge, FILTER_VALIDATE_INT) || $iAge < 18 || $iAge > 100) {
            sendResponse(400, "age is not appropriate", null);
        }
       
        if(!filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {
            sendResponse(400, "email length is not correct", null);
        }

        if(strlen($sPassword) > 40 || strlen($sPassword) < 2) {
            sendResponse(400, "password too short", null);
        }

        if(!isset($aImage)) {
            sendResponse(400, "please upload an image", null);
        }

        if($iGender != 0 && $iGender != 1 ) {
            echo $iGender;
            sendResponse(400, "please specify gender", null);
        }        

        if($iInterest != 0 && $iInterest !=1) {
            echo $iInterest;
            sendResponse(400, "please specify interest", null);
        }
        
        if(strlen($sDescription) < 10 || strlen($sDescription > 100)) {
            sendResponse(400, "please describe yourself", null);
        }
        // create new user
        $jUser->id = uniqid();
        $jUser->first_name = $sFirstName;
        $jUser->last_name = $sLastName;
        $jUser->email = $sEmail;
        $jUser->password = $sPassword;
        $jUser->age = $iAge;
        $jUser->gender = $iGender;
        $jUser->description = $sDescription;
        $jUser->interest = $iInterest;
        $jUser->imageUrl = saveImage($aImage);
        $jUser->verified = 0;
        $jUser->activation_key = uniqid();

        // insert data into 'db'
        dbSaveUser($jUser, $db);
        
        // send notification email
        sendVerificationEmail($jUser->email, $jUser->activation_key);
        sendResponse(200, "user signed up", $jUser);
    }


    function saveImage($aImage) {
        $sName = $aImage['name'];
        $sOldPath = $aImage['tmp_name'];

        $aName = explode('.', $sName);
        $sExt = $aName[count($aName)-1];
        $sId = uniqid();

        $sName = $sId.'.'.$sExt;
        move_uploaded_file($sOldPath, './assets/images/'.$sName);
        return '/assets/images/'.$sName;
    }

    
    function sendVerificationEmail($email, $key) {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;
            // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = getenv("EMAIL");                 // SMTP username
            $mail->Password = getenv("PASSWORD");                         // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            
            //Recipients
            $mail->setFrom(getenv("EMAIL"), 'Tindur');
            $mail->addAddress($email);     // Add a recipient
           
            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'TINDER - account verification';
            $mail->Body    = "Please click on the following link to verify the account </br> <a href='http://localhost/api/verify.php?key=$key' target='_blank'>http://localhost/api/verify.php?key=$key</a>";
            // $mail->AltBody = "http://localhost/api/verify.php?key=$key";

            $mail->send();
        } catch (Exception $e) {
            // echo $e;
        }
    }