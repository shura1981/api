<?php
set_time_limit(0);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
die();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require '../connection_mysql/connection.php';
require '../Encrypt/encrypt.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
$mail->CharSet = 'UTF-8';
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "nutritionmegastoresoftware@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "stevenpalmira";
//Set who the message is to be sent from
$mail->setFrom('nutritionmegastoresoftware@gmail.com', 'LipoBLue App');
//$mail->addAddress('ara797@gmail.com ', 'Arambus');
//Set the subject line
$mail->Subject = 'Restablecer contraseña';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
$correo =htmlspecialchars($_GET["email"]);
$resultado = $mysqli->query("SELECT clave from tb_blue_u WHERE correo='$correo'");
while($row = $resultado->fetch_assoc())
{
$value= $row['clave'];
}

$mycontraseña=Desencriptar($value);
//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
//Set who the message is to be sent to
$mail->addAddress($correo);
//region
$mail->msgHTML("<!DOCTYPE html>\n" .
"<html lang=\"es\">\n" .
"<head>\n" .
"<meta charset=\"utf-8\">\n" .
"<style>\n" .
"body {\n" .
"background-color: rgb(0, 0, 0);\n" .
"width: 100%;\n" .
"height: 500px;\n" .
"}\n" .
"\n" .
".hijo_uno {\n" .
"height: 80%;\n" .
"margin: 0;\n" .
"box-sizing: border-box;\n" .
"margin-left: 5%;\n" .
"margin-right: 5%;\n" .
"margin-top: 10px;\n" .
"background-color: #fff;\n" .
"border-radius: 9px 9px 9px 9px;\n" .
"background-image: url(\"https://assets.bigcartel.com/product_images/211339486/lipo-blue-usa.jpg?auto=format&fit=max&w=1000\");\n" .
"background-size: cover;\n" .
"height: 500px;\n" .
"}\n" .
".mask{\n" .
"border-radius: 9px 9px 9px 9px;\n" .
"background-color: rgba(4, 129, 179, 0.438);\n" .
"height: 500px;\n" .
"}\n" .
".pass{\n" .
"color: rgb(255, 255, 255);\n" .
"font-size: 15pt;\n" .
"font-weight: bolder;\n" .
"}\n" .
"\n" .
"\n" .
"h3{\n" .
"color: rgb(255, 255, 255);\n" .
"font-weight: bold;\n" .
"}\n" .
"\n" .
"</style>\n" .
"<title>Restar password</title>\n" .
"</head>\n" .
"<body>\n" .
"<div class=\"hijo_uno\">\n" .
"<div class=\"mask\">\n" .
"<div style=\"color: #212121; margin: 4% 10% 2%; text-align: left;font-family: sans-serif; padding-top: 20px\">  \n" .
"<h1 style=\"color: #D50000; text-align: center ;margin: 0 0 7px\">Contraseña</h1>\n" .
"<p style=\"font-weight: bolder\">Tu contraseña es  <span class=\"pass\"> $mycontraseña</span></p>\n" .
"</div>\n" .
"\n" .
"</div>\n" .
"\n" .
"\n" .
"</div>\n" .
"</div>\n" .
"\n" .
"</body>\n" .
"</html>");


//endregion
//$mail->Body= "Hello";
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('/home/steven/Descargas/Typescript-Jumpstart-Book-Udemy.pdf');
//$mail->addAttachment('/home/steven/Descargas/dita.pdf');
//$mail->addAttachment('/home/steven/Descargas/INVENTARIO MEGASTORE.xlsx');
//$mail->addAttachment('/home/steven/Descargas/19399592_1783871528295812_4394923498026369803_n.jpg');
//$mail->addAttachment('/home/steven/Imágenes/default_background_.jpg');
//$mail->addAttachment( $_SERVER['DOCUMENT_ROOT'].'/lipoblue/services/assets/images/logo.png');//file of folder assets inside server
//send the message, check for errors
if (!$mail->send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
} else {
echo "Message sent!";
//Section 2: IMAP
//Uncomment these to save your message in the 'Sent Mail' folder.
#if (save_mail($mail)) {
#    echo "Message saved!";
#}
}
//Section 2: IMAP
//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
//You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
//be useful if you are trying to get this working on a non-Gmail IMAP server.
function save_mail($mail)
{
//You can change 'Sent Mail' to any other folder or tag
$path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
//Tell your server to open an IMAP connection using the same username and password as you used for SMTP
$imapStream = imap_open($path, $mail->Username, $mail->Password);
$result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
imap_close($imapStream);
return $result;
}







