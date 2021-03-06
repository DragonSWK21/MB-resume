<?php

    $site_owners_email = 'bialasswk21@wp.pl'; // Replace this with your own email address
    $site_owners_name = 'Marcin Białek'; // replace with your name

    $name = filter_var($_POST['contactName'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['contactEmail'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['contactSubject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['contactMessage'], FILTER_SANITIZE_STRING);
    
    $error = "";

    if (strlen($name) < 2) {
        $error['name'] = "Proszę podać imie.";
    }

    if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
        $error['email'] = "Proszę podać poprawny adres e-mail.";
    }

    if (strlen($subject) < 2) {
        $error['subject'] = "Proszę podac temat.";
    }

    if (strlen($message) < 1) {
        $error['message'] = "Proszę wpisać treść wiadomości.";
    }

    if (!$error) {

        require_once('phpmailer/class.phpmailer.php');
        $mail = new PHPMailer();

        $mail->From = $email;
        $mail->FromName = $name;
        $mail->Subject = $subject;
        
        $mail->AddAddress($site_owners_email, $site_owners_name);
        
        $mail->IsHTML(true);
        
        $mail->Body = '<b>Sender Name:</b> '. $name .'<br/><b>Sender Email:</b> '. $email .'<br/><br/>' . $message;

        $mail->Send();

        echo "<div class='alert alert-success'  role='alert'>Thanks " . $name . ". Your message has been sent.</div>";

    } # end if no error
    else {

        $response = (isset($error['name'])) ? "<div class='alert alert-danger'  role='alert'>" . $error['name'] . "</div> \n" : null;
        $response .= (isset($error['email'])) ? "<div class='alert alert-danger'  role='alert'>" . $error['email'] . "</div> \n" : null;
        $response .= (isset($error['subject'])) ? "<div class='alert alert-danger'  role='alert'>" . $error['subject'] . "</div>" : null;
        $response .= (isset($error['message'])) ? "<div class='alert alert-danger'  role='alert'>" . $error['message'] . "</div>" : null;

        echo $response;
    } # end if there was an error sending

?>
