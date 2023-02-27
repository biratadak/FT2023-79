<?php


//Load Composer's autoloader
require '../../vendor/autoload.php';
require('../../class/features.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Advance PHP 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
$feature = new features();
if ($feature->validMailId1($_POST['mailId']))
{
   $feature->sendMail($_POST['mailId'],"SUBJECT: PHP_MAILER","Thank you for your submission.");
    
}
else
echo "Invalid  mailID";
?>   
</body>
</html>
