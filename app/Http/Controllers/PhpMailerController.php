<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class PhpMailerController extends Controller
{
    //
    public function sendEmail($email)
    {

        // is method a POST ?
        $mail = new PHPMailer(true); // Passing `true` enables exceptions

        try {

            // Mail server settings

            $mail->SMTPDebug = 4; // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'ldlama357@gmail.com'; // SMTP username
            $mail->Password = 'M@nish@1234'; // SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587; // TCP port to connect to

            $mail->setFrom('ldlama375@gmail.com', 'Juju Hue');
            $mail->addAddress($email); // Add a recipient, Name is optional
            // $mail->addCC($_POST['email-cc']);
            // $mail->addBCC($_POST['email-bcc']);
            // $mail->addReplyTo('your-email@gmail.com', 'Your Name');
            // print_r($_FILES['file']); exit;

            // for ($i = 0; $i < count($_FILES['file']['tmp_name']); $i++) {
            //     $mail->addAttachment($_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]); // Optional name
            // }

            $mail->isHTML(true); // Set email format to HTML

            $mail->Subject = "Thank you for purchase";
            $mail->Body    = '<table align="center" border="0" cellpadding="0" cellspacing="0" style="max-width:600px" width="100%">
            <tbody>
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td align="center" style="padding-left:24px;padding-right:24px">
                                        <a href="https://www.duolingo.com/course/de/en?email_type=practice_reminder&amp;email=3934b3996162fc508f2d5b4487850b11b7851dccImJ1ZGRoYWxhbWEzNTdAZ21haWwuY29tIg==&amp;target=hero_image&amp;utm_content=hero_image&amp;utm_source=comeback&amp;utm_medium=email&amp;utm_campaign=practice_reminder"
                                            target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.duolingo.com/course/de/en?email_type%3Dpractice_reminder%26email%3D3934b3996162fc508f2d5b4487850b11b7851dccImJ1ZGRoYWxhbWEzNTdAZ21haWwuY29tIg%3D%3D%26target%3Dhero_image%26utm_content%3Dhero_image%26utm_source%3Dcomeback%26utm_medium%3Demail%26utm_campaign%3Dpractice_reminder&amp;source=gmail&amp;ust=1609343663522000&amp;usg=AFQjCNGH9_goDJ90ehfFfM4sg4w7aBhA1Q">
                                            <img alt="" height="auto" src="https://scontent.fktm8-1.fna.fbcdn.net/v/t1.0-9/119253907_104810761376192_4086823086680994977_n.jpg?_nc_cat=105&ccb=2&_nc_sid=09cbfe&_nc_ohc=fCOJz5QAO1AAX9P-T17&_nc_ht=scontent.fktm8-1.fna&oh=a4c9ac507d02e765defafd9698a0abab&oe=6010CAD9"
                                                style="display:block;border:0px" width="147" class="CToWUd">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <table align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%;min-width:100%" width="100%">
            <tbody>
                <tr>
                    <td align="center">
    
                        <table border="0" cellpadding="0" cellspacing="0" style="width:100%;max-width:800px" width="800">
                            <tbody>
                                <tr>
                                    <td height="16" style="height:16px;min-height:16px;line-height:16px;font-size:1px">&nbsp;</td>
                                </tr>
    
                            </tbody>
                        </table>
    
                        <table border="0" cellpadding="0" cellspacing="0" style="max-width:1000px" width="100%">
                            <tbody>
                                <tr>
                                    <td align="center" style="padding-left:8px;padding-right:8px">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td align="center" style="padding-top:16px">
                                                        <h1 style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:42px;line-height:50px;font-weight:700;letter-spacing:0;color:#4c4c4c">
                                                            Thank You
                                                        </h1>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-left:24px;padding-right:24px">
                                        <table border="0" cellpadding="0" cellspacing="0" style="width:100%;" width="390">
                                            <tbody>
                                                <tr>
                                                    <td align="center" style="padding-top:16px">
                                                        <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:22px;font-weight:400;letter-spacing:0;color:#555555">
                                                            From of us at JUJU HUE, welcome to our family! Thank you so much for your purchase of And Then There Were None. It’s a classic, and we’re sure you’ll love it! We’re a small, carefully curated shopping store, and we stand by all of our titles.
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" style="padding-top:16px">
                                                        <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:22px;font-weight:400;letter-spacing:0;color:#555555">
                                                            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:22px;font-weight:400;letter-spacing:0;color:#555555">
                                                                Thank you again for choosing JUJU HUE, we hope to hear from you again soon!
                                                            </p>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
    
    
                                <tr>
                                    <td height="24" style="height:24px;line-height:24px">&nbsp;</td>
                                </tr>
    
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="50" style="height:50px;min-height:50px;line-height:50px;font-size:1px;border-bottom:2px solid #f2f2f2">&nbsp;</td>
                </tr>
            </tbody>
        </table>';
            // $mail->AltBody = plain text version of your message;

            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent';
            }
        } catch (Exception $e) {
            echo 'Message has been sent' . $e;
        }
    }
}
