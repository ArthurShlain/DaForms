<?php

/**
 * Send email action
 */

use PHPMailer\PHPMailer\PHPMailer;

if (!function_exists('action_mail')) {
    function action_mail($data)
    {

        $errors = array();

        // Send mail using PHPMailer
        if (empty($errors)) {
            $mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch

            try {
                // Server settings
                if (!empty(@$data['SMTPDebug'])) $mail->SMTPDebug = $data['SMTPDebug'];
                if (@$data['isSMTP']) $mail->isSMTP();
                if (!empty(@$data['Host'])) $mail->Host = $data['Host'];
                if ($data['SMTPAuth']) $mail->SMTPAuth = true;
                if (!empty(@$data['Username'])) $mail->Username = $data['Username'];
                if (!empty(@$data['Password'])) $mail->Password = $data['Password'];
                if (!empty(@$data['SMTPSecure'])) $mail->SMTPSecure = $data['SMTPSecure'];
                if (!empty(@$data['Port'])) $mail->Port = $data['Port'];

                // Recipients
                if(!empty(@$data['From'])){
                    $mail->setFrom($data['From'], @$data['FromName']);
                }
                if(!empty(@$data['To'])){
                    $mail->addAddress($data['To'], @$data['ToName']);
                }
                if(!empty(@$data['ReplyTo'])){
                    $mail->addReplyTo($data['ReplyTo'], @$data['ReplyToName']);
                }
                if(!empty(@$data['CC'])){
                    $mail->addCC($data['CC'], @$data['CCName']);
                }
                if(!empty(@$data['BCC'])){
                    $mail->addBCC($data['BCC'], @$data['BCCName']);
                }
                // ToDo: add many recipients

                // Attachments
                // ToDo: add attachments

                // Content
                $mail->CharSet = @$data['CharSet'] ? $data['CharSet'] : 'UTF-8';
                $mail->isHTML(@$data['isHTML'] ? true : false);
                $mail->Subject = @$data['Subject'];
                $mail->Body = @$data['Body'];
                if(@$data['AltBody']){
                    $mail->AltBody = @$data['AltBody'];
                }

                if (!$mail->send()) {
                    $errors['action.mail'] = 'action.mail.failed';
                }
            } catch (phpmailerException $e) {
                $errors['action.mail.phpmailerException'] = $e->errorMessage(); //Pretty error messages from PHPMailer
            }

        }

        return array(
            'success' => empty($errors),
            'errors' => $errors
        );
    }
}