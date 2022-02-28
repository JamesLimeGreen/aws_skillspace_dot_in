$(function () {
  $('[data-bs-toggle="tooltip"]').tooltip()
});

window.onscroll = function() {myFunction()};

var header = document.getElementById("menu-area");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}

 //copy by me from home.php line no 29  
var border_bottom = $('.home-banner-wrap').height() + 242;
$('.cropped-home-banner').css('border-bottom', border_bottom + 'px solid #f1f7f8');

mRight = Number("<?php echo $banner_ratio; ?>") * $('.home-banner-area').outerHeight();
$('.cropped-home-banner').css('right', (mRight-65) + 'px');


//copy by me from eu-cookies bottom
$(document).ready(function () {
  if (localStorage.getItem("accept_cookie_academy")) {
    //localStorage.removeItem("accept_cookie_academy");
  }else{
    $('#cookieConsentContainer').fadeIn(1000);
  }
});

function cookieAccept() {
  if (typeof(Storage) !== "undefined") {
    localStorage.setItem("accept_cookie_academy", true);
    localStorage.setItem("accept_cookie_time", "<?php echo date('m/d/Y'); ?>");
    $('#cookieConsentContainer').fadeOut(1200);
  }
}



