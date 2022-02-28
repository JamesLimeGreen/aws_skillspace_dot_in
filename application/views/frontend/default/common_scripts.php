<?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . ":$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5e6d2135470839001284a244&product=inline-share-buttons&cms=sop' async='async' defer></script>
<script type="text/javascript"> 
function toggleRatingView(course_id) {
  $('#course_info_view_'+course_id).toggle();
  $('#course_rating_view_'+course_id).toggle();
  $('#edit_rating_btn_'+course_id).toggle();
  $('#cancel_rating_btn_'+course_id).toggle();
}

function publishRating(course_id) {
    var review = $('#review_of_a_course_'+course_id).val();
    var starRating = 0;
    starRating = $('#star_rating_of_course_'+course_id).val();
    if (starRating > 0) {
        $.ajax({
            type : 'POST',
            url  : '<?php echo site_url('home/rate_course'); ?>',
            data : {course_id : course_id, review : review, starRating : starRating},
            success : function(response) {
                location.reload();
            }
        });
    }else{

    }
}

function handleCartItems(elem) {
    url1 = '<?php echo site_url('home/handleCartItems'); ?>';
    url2 = '<?php echo site_url('home/refreshWishList'); ?>';
    $.ajax({
        url: url1,
        type: 'POST',
        data: {
            course_id: elem.id
        },
        success: function(response) {
            $('.cart_items').html(response);
            if ($(elem).hasClass('addedToCart')) {
                $('.big-cart-button-' + elem.id).removeClass('addedToCart')
                $('.big-cart-button-' + elem.id).text("<?php echo site_phrase('add_to_cart'); ?>");
            } else {
                $('.big-cart-button-' + elem.id).addClass('addedToCart')
                $('.big-cart-button-' + elem.id).text("<?php echo site_phrase('added_to_cart'); ?>");
            }
            $.ajax({
                url: url2,
                type: 'POST',
                success: function(response) {
                    $('#wishlist_items').html(response);
                }
            });
        }
    });
}

function handleWishList(elem) {
    var self=elem;
    $.ajax({
        url: '<?php echo site_url('home/handleWishList'); ?>',
        type: 'POST',
        data: {
            course_id: elem.id
        },
        success: function(response) {
            if (!response) {
                window.location.replace("<?php echo site_url('login'); ?>");
            } else {
                if ($(elem).hasClass('active')) {
                    $(elem).removeClass('active')
                } else {
                    $(elem).addClass('active')
                }
                $('#wishlist_items').html(response);
                $("#wishlist_"+self.id).remove();
            }
        }
    });
}


function handleBuyNow(elem) {
    url1 = '<?php echo site_url('home/handleCartItemForBuyNowButton');?>';
    urlToRedirect = '<?php echo site_url('home/shopping_cart'); ?>';
    var explodedArray = elem.id.split("_");
    var course_id = explodedArray[1];

    if("<?= $this->session->userdata('user_login');?>" == '1'){
      $.ajax({
        url: url1,
        type : 'POST',
        data : {course_id : course_id},
        success: function(response)
        {
          setTimeout(function() {
            window.location.replace(urlToRedirect);
          }, 1000);
        }
      });
    }else if("<?= $this->session->userdata('admin_login');?>" == '1'){
      toastr.error('<?= site_phrase('please_sign_in_as_a_student_or_instructor'); ?>');
    }else{
      handleEnrolledButton();
    }
  }

  function handleEnrolledButton() {
    $.ajax({
        url: '<?php echo site_url('home/isLoggedIn?url_history='.base64_encode($actual_link)); ?>',
        success: function(response) {
            if (!response) {
                window.location.replace("<?php echo site_url('login'); ?>");
            }
        }
    });
  }

function isTouchDevice() {
  return (('ontouchstart' in window) ||
     (navigator.maxTouchPoints > 0) ||
     (navigator.msMaxTouchPoints > 0));
}

function viewMore(element, visibility) {
  if (visibility == "hide") {
    $(element).parent(".view-more-parent").addClass("expanded");
    $(element).remove();
  } else if ($(element).hasClass("view-more")) {
    $(element).parent(".view-more-parent").addClass("expanded has-hide");
    $(element)
      .removeClass("view-more")
      .addClass("view-less")
      .html("- <?php echo site_phrase('view_less'); ?>");
  } else if ($(element).hasClass("view-less")) {
    $(element).parent(".view-more-parent").removeClass("expanded has-hide");
    $(element)
      .removeClass("view-less")
      .addClass("view-more")
      .html("+ <?php echo site_phrase('view_more'); ?>");
  }
}

function redirect_to(url){
  if(!isTouchDevice() && $(window).width() > 767){
    window.location.replace(url);
  }
}

//Event call after loading page
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function(){
        $('.animated-loader').hide();
        $('.shown-after-loading').show();
    });
}, false);


function check_action(e, url){
  var tag = $(e).prop("tagName").toLowerCase();
  if(tag == 'a'){
    return true;
  }else if(tag != 'a' && url){
    $(location).attr('href', url);
    return false;
  }else{
    return true;
  }
}
</script>

<!-- lazy Loader -->


<script>
    !function(window){
      lazyloader();

    }(this);

    function lazyloader(){
      
      var $q = function(q, res){
        if (document.querySelectorAll) {
          res = document.querySelectorAll(q);
        } else {
          var d=document
            , a=d.styleSheets[0] || d.createStyleSheet();
          a.addRule(q,'f:b');
          for(var l=d.all,b=0,c=[],f=l.length;b<f;b++)
            l[b].currentStyle.f && c.push(l[b]);

          a.removeRule(0);
          res = c;
        }
        return res;
      },
      addEventListener = function(evt, fn){
          window.addEventListener
            ? this.addEventListener(evt, fn, false)
            : (window.attachEvent)
              ? this.attachEvent('on' + evt, fn)
              : this['on' + evt] = fn;
        }, _has = function(obj, key) {
          return Object.prototype.hasOwnProperty.call(obj, key);
        };

      function loadImage (el, fn) {
        var img = new Image()
          , src = el.getAttribute('data-src');
        img.onload = function() {
          if (!! el.parent)
            el.parent.replaceChild(img, el)
          else
            el.src = src;

          fn? fn() : null;
        }
        img.src = src;
      }

      function elementInViewport(el) {
        var rect = el.getBoundingClientRect()

        return (
           rect.top    >= 0
        && rect.left   >= 0
        && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
        )
      }

      var images = new Array()
        , query = $q('img.lazy')
        , processScroll = function(){
            for (var i = 0; i < images.length; i++) {
              if (elementInViewport(images[i])) {
                loadImage(images[i], function () {
                  images.splice(i, i);
                });
              }
            };
          }
        ;
      // Array.prototype.slice.call is not callable under our lovely IE8 
      for (var i = 0; i < query.length; i++) {
        images.push(query[i]);
      };

      processScroll();
      addEventListener('scroll',processScroll);
    }
</script>
