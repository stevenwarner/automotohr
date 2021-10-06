$(document).ready(function(){
    // Add Background Change Class To Navbar
    var nav = $('#masthead');
    if(stopWindowScrollForHeader === 0){
      $(window).scroll(function(e) {
          if($(window).scrollTop() > 2 ){
              nav.addClass("bg-change animated");
            }else if($(window).scrollTop() < 1){
              nav.removeClass("bg-change animated");
            }
          });  
        } else{
      nav.addClass("bg-change animated");

    }   
    // .modal-backdrop classes
    $(".modal-transparent").on('show.bs.modal', function () {
      setTimeout( function() {
        $(".modal-backdrop").addClass("modal-backdrop-transparent");
      }, 0);
    });
    $(".modal-transparent").on('hidden.bs.modal', function () {
      $(".modal-backdrop").addClass("modal-backdrop-transparent");
    });

    $(".modal-fullscreen").on('show.bs.modal', function () {
      setTimeout( function() {
        $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
      }, 0);
    });
    $(".modal-fullscreen").on('hidden.bs.modal', function () {
      $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
    });

    //Check to see if the window is top if not then display button
    jQuery(window).scroll(function(){
        if ($(this).scrollTop() > 300) {
            $('.scrollToTop').fadeIn();
            $('.scrollToTop').css('bottom', '15px');
        } else {
            $('.scrollToTop').fadeOut();
            $('.scrollToTop').css('bottom', '0');
        }
    });
    //Click event to scroll to top
    $('.scrollToTop').each(function () {
        $(this).click(function(){
            $('html, body').animate({
                scrollTop: $("html").offset().top
            }, 1000);

            //$(window).animate({top : 0}, 800);
            //$('body').animate({scrollTop : 0},800);
            //return false;
        });
    });
    // New Script Goes Here

});
