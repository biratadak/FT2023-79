
<?php

  require('../../vendor/autoload.php');
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  use GuzzleHttp\Client;

  //Getting secret credentials using dotenv
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->safeLoad();
  /**
   * Provides some usefull features for the checking, validating and upload files.
   * 
   * @method onlyAlpha().
   *   Checks wether given str is only alphabet or not.
   * 
   * @method onlyDigit().
   *   Checks wether given str is only digit or not.
   * 
   * @method validImage().
   *   Checks wether given image is jpg/png and under 500kb.
   * 
   * @method splitMarks().
   *   Splits marks and remove '|' and return array of marks.
   * 
   * @method validPhoneNo().
   *   Checks if phone no is valid or not.
   * 
   * @method validMailId1().
   *   Checks if Mail Id is valid or not using RegEx.
   * 
   * @method validMailBox().
   *   Checks if Mail Id is valid or not using MailBoxLayer and Guzzle.
   * 
   * @method sendMail().
   *   sends mail to given email Id.
   * 
   * @method getURL().
   *   Get response body of given URL using GuzzleHTTP client request.
   * 
   * @property string $name
   *  Store name off the user.
   * 
   * @property string $mailId
   *  Store mailId of the user.
   * 
   * @property string $marks
   *  Store marks of the user.
   * 
   * @property string $phoneNo
   *  Store phoneNo of the user.
   * 
   * @property string $imagePath
   *  Store path of profile-pic of the user.
   * 
   **/
    class features {
      /**
       * @var $name
       *  Store name off the user.
       */
      public $name;
      /**
       * @var $mailId
       *  Store mailId off the user.
       */
      public $mailId;
      /**
       * @var $marks
       *  Store marks off the user.
       */
      public $marks;
      /**
       * @var $phoneNo
       *  Store phone number off the user.
       */
      public $phoneNo;
      /**
       * @var $imagePath
       *  Store path of the image off the user.
       */
      public $imagePath;
      
      // String methods here 

      /** 
       * Checks if a string only contains alphabets and whitespaces
       * 
       * @param  $string
       *   stores the string to varify. 
       **/
      function onlyAlpha($string) {
          if (preg_match("/^[a-zA-Z-' ]*$/", $string)) {
              return TRUE;
          } 
          else {
              return FALSE;
          }
      }

      /** 
       * Fucntion to check the string only has digits
       * 
       * @param  $string
       *   stores the string to varify. 
       **/
      function onlyDigit($string) {
          if (preg_match("/^[1-9][0-9]{0,15}$/", $string))
              return TRUE;
          else
              return FALSE;
      }

      // Image methods here
      /** 
       *   Checks wether given image is jpg/png and under 500kb.
       * 
       * @param  $imageSize
       *   Stores the size of the image. 
       * 
       * @param  $imageType
       *   Stores the datatype of the image. 
       **/
      function validImage($imageSize, $imageType) {
          if (($imageSize / 1000) <= 500 && ($imageType == 'image/jpg' || $imageType == 'image/png' || $imageType == 'image/jpeg')) {
              return TRUE;
          } 
          else {
              if (($imageSize / 1000) > 500) {
                  echo "<br>Image size should be less than 500KB (" . ($imageSize / 1000) . "KB given)";
              }
              if ($imageType != 'image/jpg' || $imageType != 'image/png' || $imageType != 'image/jpeg') {
                  echo "<br>Only Jpeg, Jpg & Png are allowed (" . $imageType . " given)";
              }
              return FALSE;
          }

      }

      /** 
       * Splits the $marks string and return array of different strings
       * 
       * @param  $marks
       *   Stores the marks field data of the user. 
       * 
       **/
      function splitMarks($marks) {
          $lines = array();
          $index = 1;
          foreach (explode("\n", $marks) as $line) {
              if (str_contains($line, '|'))
                  $lines[] = array(explode("|", $line)[0], explode("|", $line)[1]);
              else
                  echo "<br>wrong syntax in line " . $index . ".";
              $index++;
          }
          return $lines;
      }

      /**
       * Checks if the given phone no starts with +91 and has exactly 10 numbers starting with 6-9 
       * 
       * @param  $phoneNo
       *   Stores the Phone No of the user. 
       * 
       **/
      function validPhoneNo($phoneNo) {
          if (preg_match("/^[+][9][1][6-9][0-9]{9}$/", $phoneNo))
              return TRUE;
          else
              return FALSE;
      }

      /**
       * Checks if Mail Id is valid or not using RegEx.
       * 
       * @param  $mailId
       *  Stores the Mail Id of the user. 
       * 
       **/
      function validMailId1($mailId) {
          if (preg_match("/^[a-z-.]{1,20}[@][a-z]{1,10}[.][c][o][m]$/", $mailId))
              return TRUE;
          else
              return FALSE;
      }

      /**
       * Checks Mail Id validation with mailBoxLayer API.
       * 
       * @param  $mailId
       *  Stores the Mail Id of the user. 
       * 
       **/
      function validMailBox($mailId) {

          ////// API Calling Using cURL library.//////

          // $curl = curl_init();
          // // Mailbox Layer API calling
          // curl_setopt_array(
          //     $curl,
          //     array(
          //         CURLOPT_URL => "https://api.apilayer.com/email_verification/check?email=" . $mailId,
          //         CURLOPT_HTTPHEADER => array(
          //             "Content-Type: text/plain",
          //             "apikey: H2AIxxMvhiT1uUKhxs7TuSMJmysHASNI"
          //         ),
          //         CURLOPT_RETURNTRANSFER => TRUE,
          //         CURLOPT_ENCODING => "",
          //         CURLOPT_MAXREDIRS => 10,
          //         CURLOPT_TIMEOUT => 0,
          //         CURLOPT_FOLLOWLOCATION => TRUE,
          //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          //         CURLOPT_CUSTOMREQUEST => "GET"
          //     )
          // );
          // $response = curl_exec($curl);
          // curl_close($curl);


          // API Calling using HttpGuzzle.
          $client = new Client([
              //base uri of the site
              'base_uri' => 'https://api.apilayer.com/ ?email=',
          ]);

          $request = $client->request('GET', 'email_verification/check', [
              "headers" => [
                  'apikey' => 'H2AIxxMvhiT1uUKhxs7TuSMJmysHASNI'
              ],
              'query' => [
                  'email' => $mailId,
              ]
          ]);
          $response = $request->getBody();



          // Checking format, mx, smtp, and deliverablity score for the mail
          if (json_decode($response)->format_valid == TRUE && json_decode($response)->mx_found == TRUE && json_decode($response)->smtp_check == TRUE) {
              echo "<br>(E-mail deliverablity score is: " . ((json_decode($response)->score) * 100) . "% ).";
              return TRUE;
          } 
          else {
              echo "<div class='error'>Error:<br>";

              if (isset(json_decode($response)->format_valid) && json_decode($response)->format_valid == FALSE) {
                  echo "E-mail format is not valid<br>";
              }
              if (isset(json_decode($response)->mx_found) && json_decode($response)->mx_found == FALSE) {
                  echo "MX-Records not found<br>";
              }
              if (isset(json_decode($response)->smtp_check) && json_decode($response)->smtp_check == FALSE) {
                  echo "SMTP validation failed<br>";
              }
              echo "</div>";
              return false;
          }
      }

      //Send Mails using PHP-Mailer
      /** 
       * Send Mails using PHP-Mailer. 
       * @param  $mailId
       *   takes mailId as input field data of the user. 
       * 
       **/
      function sendMail($mailId, $subject = "Subject", $body = "no data found") {
          $mail = new PHPMailer(true);

          try {
              $mail->SMTPDebug = 0;
              $mail->isSMTP();
              $mail->Host = 'smtp.gmail.com';
              $mail->SMTPAuth = true;
              $mail->Username = $_ENV['SMTPMail'];
              $mail->Password = $_ENV['SMTPKey'];
              $mail->SMTPSecure = 'tls';
              $mail->Port = 587;

              $mail->setFrom($mailId, 'PHP Advance Assignment 2');
              $mail->addAddress($mailId);

              $mail->isHTML(true);
              $mail->Subject = $subject;
              $mail->Body = $body;
              $mail->AltBody = 'Body in plain text for non-HTML mail clients';
              $mail->send();
              echo "Mail has been sent successfully!";
          } 
          catch (Exception $e) {
              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
      }

      /** 
       * Get response body from url using Guzzle.
       * @param  $url
       *   takes url as input and return response body. 
       * 
       **/
      function getURL($url) {
          $client = new Client([
              //base uri of the site
              'base_uri' => $url,
          ]);

          $request = $client->request('GET');
          return $request->getBody();
      }

  }

?>
