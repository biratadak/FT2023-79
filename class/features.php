<?php

  require('../../vendor/autoload.php');
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  use GuzzleHttp\Client;

  //Getting secret credentials using dotenv
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->safeLoad();

  /**
   * Provides features for the sending, validating mail and get HTTP response body.
   * 
   * @method validMailId().
   *   Checks if Mail Id is valid or not using MailBoxLayer and Guzzle.
   * 
   * @method sendMail().
   *   sends mail to given email Id.
   * 
   * @method getURL().
   *   Get response body of given URL using GuzzleHTTP client request.
   * 
   * 
   **/
  class Features {

      /**
       * Checks Mail Id validation with mailBoxLayer API.
       * 
       * @param string $mailId
       *  Stores the Mail Id of the user. 
       * @return bool
       *  returns TRUE if mail-id is valid otherwise returns FALSE.
       **/
      public function validMailId(string $mailId) {

          // API Calling using HttpGuzzle.
          $client = new Client([
              // Base uri of the site
              'base_uri' => 'https://api.apilayer.com/ ?email=',
          ]);

          $request = $client->request('GET', 'email_verification/check', [
              "headers" => [
                  'apikey' => $_ENV['APIKey']
              ],
              'query' => [
                  'email' => $mailId,
              ]
          ]);
          $response = $request->getBody();



          // Checking format, MX, SMTP, and deliverablity score for the mail
          if (json_decode($response)->format_valid == TRUE && json_decode($response)->mx_found == TRUE && json_decode($response)->smtp_check == TRUE) {
              echo "<br>(E-mail deliverablity score is: " . ((json_decode($response)->score) * 100) . "% ).<br>";
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
              return FALSE;
          }
      }

      /** 
       * Send Mails using PHP-Mailer. 
       * 
       * @param string $mailId
       *   takes mailId as input field data of the user. 
       * 
       **/
      public function sendMail(string $mailId, string $subject = "Subject not found", string $body = "Message body not found") {
          $mail = new PHPMailer(TRUE);

          try {
              $mail->SMTPDebug = 0;
              $mail->isSMTP();
              $mail->Host = 'smtp.gmail.com';
              $mail->SMTPAuth = TRUE;
              $mail->Username = $_ENV['SMTPMail'];
              $mail->Password = $_ENV['SMTPKey'];
              $mail->SMTPSecure = 'tls';
              $mail->Port = 587;
              $mail->setFrom($mailId, 'PHP Advance Assignment 2');
              $mail->addAddress($mailId);
              $mail->isHTML(TRUE);
              $mail->Subject = $subject;
              $mail->Body = $body;
              $mail->AltBody = 'Body in plain text for non-HTML mail clients';
              $mail->send();
              echo "Mail has been sent successfully!";
          } catch (Exception $e) {
              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
      }

      /** 
       * Get response body from url using Guzzle.
       * 
       * @param string $url
       *   takes url as input and return response body. 
       * @return array
       *    Returns the body as a stream.
       **/
      public function getURL(string $url) {
          $client = new Client([
              //base uri of the site
              'base_uri' => $url,
          ]);

          $request = $client->request('GET');
          $response = $request->getBody();
          return $response;
      }

  }

?>
