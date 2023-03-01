//  Script for loader
  $(document).ready(function () {
    $.ajax({
      type: "GET",
      url: "index.php",
      dataType: 'json',
      beforeSend: function () {
        $('.loader').fadeIn(1000);
        $('.content').hide();
      },
      success: function (data) {
        $('.content').fadeIn(1000);
        $('.loader').fadeOut(1000);

      },
      complete: function () {
        $('.content').fadeIn(1000);
        $('.loader').fadeOut(1000);
      },
    });
  });
