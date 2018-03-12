<?php
    session_start(); //CRUD FOR SESSIONS
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //Load composer's autoloader
    require '../vendor/autoload.php';

    function verifyLogin() {
        $id = $_GET['id'];
        if($_SESSION[$id]) return $id;
        
        echo '{"status":"forbidden","message":"user is not logged in"}';
        exit;
    }
    
    function getUsers($sajUsers) {
    
        echo '{"status":"success", "message":"user logged in", "data":'.$sajUsers.'}';
        exit;
    }

    function login($ajUsers) {
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        foreach($ajUsers as $jUser) {
            if($jUser->email === $sEmail && $jUser->password === $sPassword) {
                if($jUser->verified != 1) {
                    echo '{"status":"error","code":"403", "message":"please verify your account"}';
                    exit;
                }
                $sjUser = json_encode($jUser);
                $_SESSION[$jUser->id] = "logged in";
                echo '{"status":"success", "message":"user logged in", "data":'.$sjUser.'}';
                exit;
            }
        }
        echo '{"status":"error","message":"username or password is incorrect"}';
        exit;
    }

    function logout() {
        $sId =  verifyLogin();
        unset($_SESSION[$sId]);

        echo '{"status":"success", "message":"user logged out", "data":'.$sId.'}';
        exit;
    }
 
    function signup($ajUsers) {
        $sFirstName = $_POST['firstName'];
        $sLastName = $_POST['lastName'];
        $iAge = $_POST['age'];
        $sEmail = $_POST['email'];
        $sPassword = $_POST['password'];
        $aImage = $_FILES['image']; 

        
        foreach($ajUsers as $jU) {
            if($jU->email == $sEmail) {
                echo '{"status":"error","message":"email already exists"}';
                exit;
            }
        }
        
        if(!$sFirstName || !$sLastName || !$iAge || !$sEmail || !$sPassword || !$aImage['tmp_name']) {
            echo '{"status":"error","message":"make sure you fill up all the required fields"}';
            exit;
        }
  
        if(strlen($sFirstName) > 25 || strlen($sFirstName) < 2) {
            echo '{"status":"error","message":"first name length is not correct"}';
            exit;
        }

        if(strlen($sLastName) > 25 || strlen($sLastName) < 2) {
            echo '{"status":"error","message":"last name length is not correct"}';
            exit;
        }

        if(!filter_var($iAge, FILTER_VALIDATE_INT) || $iAge < 18 || $iAge > 100) {
            echo '{"status":"error","message":"age is not appropriate"}';
            exit;
        }
       
        if(strlen($sEmail) > 30 || strlen($sEmail) < 2) {
            echo '{"status":"error","message":"email length is not correct"}';
            exit;
        }

        if(strlen($sPassword) > 40 || strlen($sPassword) < 2) {
            echo '{"status":"error","message":"password too short"}';
            exit;
        }

        if(!isset($aImage)) {
            echo '{"status":"error","message":"please upload an image"}';
            exit;
        }

        // create new user
        $jUser->id = uniqid();
        $jUser->first_name = $sFirstName;
        $jUser->last_name = $sLastName;
        $jUser->email = $sEmail;
        $jUser->password = $sPassword;
        $jUser->age = $iAge;
        $jUser->imageUrl = saveImage($aImage);
        $jUser->verified = 0;
        $jUser->activation_key = uniqid();

        $sjUser = json_encode($jUser);
        // add user to array
        array_push($ajUsers, $jUser);
        
        // insert data into 'db'
        $sajUsers = json_encode($ajUsers);
        file_put_contents('./storage/users.txt', $sajUsers);

        // save image to file
        sendVerificationEmail($jUser->email, $jUser->activation_key);
        echo '{"status":"success", "message":"user signed up", "data":'.$sjUser.'}';
        exit;
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
        }
    }