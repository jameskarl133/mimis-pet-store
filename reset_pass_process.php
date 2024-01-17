<?php
    if(isset($_POST['reset'])) {
        $email = $_POST['email'];
    }
    else {
        exit();
    }

    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailerComps/Exception.php';
    require 'PHPMailerComps/PHPMailer.php';
    require 'PHPMailerComps/SMTP.php';
    
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'mimispetshop84@gmail.com';                     // SMTP username
        $mail->Password   = 'bnfe fsre bykn arlp';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    
        //Recipients
        $mail->setFrom('mimispetshop84@gmail.com', 'Mimis Pet Shop');
        $mail->addAddress($email);     // Add a recipient

        $code = substr(str_shuffle('1234567890QWERTYUIOPASDFGHJKLZXCVBNM'),0,10);
    
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Password Reset';
        $mail->Body    = 'To reset your password click <a href="http://localhost:8000/change_password.php?verify_token='.$code.'">here </a>. </br>';

        $conn = new mySqli('localhost', 'root', '', 'mimi pet store');

        if($conn->connect_error) {
            die('Could not connect to the database.');
        }

        $verifyQuery = $conn->query("SELECT * FROM employee WHERE emp_email = '$email'");

        if($verifyQuery->num_rows) {
            $codeQuery = $conn->query("UPDATE employee SET verify_token = '$code' WHERE emp_email = '$email'");
                
            $mail->send();
            echo 'Message has been sent, check your email';
        }
        $conn->close();
    
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }    
?>