<?php

  require("../class/features.php");
  require("../../vendor/autoload.php");

?>

<html>

<head>
  <meta charset="UTF-8" />
  <title>PHP-ADV-1</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="../js/loader.js"></script>

</head>

<body>
  <!-- Loader Div -->
  <div class="loader ">
    <div>
    </div>
  </div>
  <!-- Loader Div ends-->

  <div class="content ">
    <div class="container">
      <div class="view-content">
        
        <?php
          
          // Instantiating feature class object to get all required methods.
          $feature = new Features();
          $main_url = 'https://ir-dev-d9.innoraft-sites.com/jsonapi/node/services';
          $data_response = json_decode($feature->getURL($main_url));
          // Looping through the API responses to get elements.
          foreach ($data_response->data as $index=>$data) {
            if (isset($data->attributes->field_services->value)) {
              echo '<div class="view-row">';
              
              // If row number is even put image first then text.
              if ($index % 2 == 0) {
                
                // Start row-left
                echo '<div class="row-left">';
                $image_url = $data->relationships->field_image->links->related->href;
                $image_response = json_decode($feature->getURL($image_url));
                echo "<img src='https://ir-dev-d9.innoraft-sites.com" . $image_response->data->attributes->uri->url . "'>";
                echo '</div>';
                // End row-left.
          
                // Start row-right.
                echo '<div class="row-right">';
                echo '<div class="title">' . $data->attributes->title . '</div>';
                echo $data->attributes->field_services->value;
                echo '<div class="explore-btn"><a href="https://ir-dev-d9.innoraft-sites.com'.$data->attributes->path->alias.'">EXPLORE</a></div>';
                echo '</div>';
                // Ending row-right.
          
              } 
              else {
                // Start row-right.
                echo '<div class="row-right">';
                if (isset($data->attributes->title) || isset($data->attributes->field_services->value)) {
                  echo '<div class="title">' . $data->attributes->title . '</div>
                          ';
                    echo $data->attributes->field_services->value;
                    echo '<div class="explore-btn"><a href="https://ir-dev-d9.innoraft-sites.com'.$data->attributes->path->alias.'">EXPLORE</a></div>';
                    echo '</div>';
                  // Ending row-right.
                }

                // Start row-left.
                echo '<div class="row-left">';
                $image_url = $data->relationships->field_image->links->related->href;
                $image_response = json_decode($feature->getURL($image_url));
                echo "<img src='https://ir-dev-d9.innoraft-sites.com" . $image_response->data->attributes->uri->url . "'>";
                echo '</div>';
                // End row-left.
          
              }

              // Ending in view-row.
              echo '</div>';
            }
          }

        ?>

      </div>
      <!-- View content ends -->
    </div>
    <!-- Container Ends -->
  </div>
  <!-- Content div ends -->

</body>

</html>
