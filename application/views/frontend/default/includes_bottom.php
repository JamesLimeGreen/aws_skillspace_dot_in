<script src="<?php echo base_url() . 'assets/frontend/default/js/vendor/modernizr-3.5.0.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/frontend/default/js/vendor/jquery-3.2.1.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/frontend/default/js/popper.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/frontend/default/js/bootstrap.min.js'; ?>"></script>

<?php if ($page_name == "home" || $page_name == "instructor_page") : ?>
    <script src="<?php echo base_url() . 'assets/frontend/default/js/slick.min.js'; ?>"></script>
    <script src="<?php echo base_url() . 'assets/frontend/default/js/jquery.webui-popover.min.js'; ?>"></script>
<?php endif; ?>

<?php if ($page_name == "user_profile") : ?>
    <script src="<?php echo base_url() . 'assets/frontend/default/js/tinymce.min.js'; ?>"></script>
<?php endif; ?>

<script src="<?php echo base_url() . 'assets/frontend/default/js/main.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/global/toastr/toastr.min.js'; ?>"></script>
<script src="<?php echo base_url() . 'assets/frontend/default/js/jquery.form.min.js'; ?>"></script>
<script src="<?php echo base_url(); ?>assets/frontend/default/js/jQuery.tagify.js"></script>

<script type="text/javascript">
    $(function () {
      $('[data-bs-toggle="tooltip"]').tooltip()
    });
    if($('.tagify').height()){
        $('.tagify').tagify();
    }
</script>

<!-- SHOW TOASTR NOTIFIVATION -->
<?php if ($this->session->flashdata('flash_message') != "") : ?>

	<script type="text/javascript">
		toastr.success('<?php echo $this->session->flashdata("flash_message"); ?>');
	</script>

<?php endif; ?>

<?php if ($this->session->flashdata('error_message') != "") : ?>

	<script type="text/javascript">
		toastr.error('<?php echo $this->session->flashdata("error_message"); ?>');
	</script>

<?php endif; ?>

<?php if ($this->session->flashdata('info_message') != "") : ?>

	<script type="text/javascript">
		toastr.info('<?php echo $this->session->flashdata("info_message"); ?>');
	</script>

<?php endif; ?>
<script type="text/javascript">

    function switch_language(language) {
        $.ajax({
            url: '<?php echo site_url('home/site_language'); ?>',
            type: 'post',
            data: {language : language},
            success: function(response) {
               var str = window.location.href = '<?php echo base_url(); ?>lang/'+response+'/';                   
              //setTimeout(function(){ location.reload(); }, 500);
            }
        });
    }

   //copy by me from home.php page 
  $(document).on("click",".nav-link",function(e){
    var self=this;
	setTimeout(()=>{
	 $.ajax({
        url:"<?= base_url();?>home/getFeaturedCourse",
        type:"post",
        data:{"category":this.id},
		cache: false,
        beforeSend:function(){
          $(".custom__tabls_btn").find(".nav-link").removeClass("active");  
          // $('#featured_data').html('<div class="col-sm-4 mx-auto text-center"><i class="fa fa-spinner text-danger fa-spin fa-4x"></i></div>');
        },
        success:function(success){

            var res=JSON.parse(success);
            if(res.status==1){
                var html=``;
                for(i=0;i<res.data.length;i++){
                    html+=`<div class="col-sm-3 course-box-wrap">

                    <a onclick="return check_action(this);" href="`+res.data[i].SingleCourseUrl+`" class="has-popover">
                       <div class="course-box">
                          <div class="course-image">
                             <img src="`+res.data[i].ImageUrl+`" alt="" class="img-fluid">
                          </div>
                          <div class="course-details">
                             <h5 class="title">`+res.data[i].CourseTitle+`</h5>

                             <div class="rating">
                                
                                <div class="d-inline-block">
                                   <span class="badge badge-sub-warning text-11px">`+res.data[i].Level+`</span> &nbsp;&nbsp;
                                   <span class="badge bg-secondary-start"><i class="fa fa-star star-text"></i>`+res.data[i].AverageRating+`</span>
                                </div>
                             </div>
                             <div class="d-flex text-dark">
                                
                                <div class="">
                                   <i class="fas fa-book-reader text-14px"></i>
                                   <span class="text-muted text-12px">`+res.data[i].NoOfLession+` `+res.data[i].Lessons+ `</span>
                                </div>
                                <div class="ms-3">
                                   <i class="fas fa-clock text-14px"></i>
                                   <span class="text-muted text-12px">`+res.data[i].CourseDuration+`</span>
                                </div>
                             </div>
                             
                             <hr class="divider-1">
                             <div class="d-block">
                                <div class="floating-user d-inline-block">`;
                                if(res.data[i].IsMultiInstructor==1){
                                    var margin=0;
                                   for(j=0;j<res.data[i].MultiInstructor.instructor.length;j++){

                                   html+=`<img style="margin-left: `+margin+`px;" class="position-absolute" src="`+res.data[i].MultiInstructor.instructor[j].InsImage+`" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="`+res.data[i].MultiInstructor.instructor[j].InstructorName+`" onclick="return check_action(this,'`+res.data[i].MultiInstructor.instructor[j].InstructorUrl+`');">`;
                                     margin=margin+17;
                                   }
                                }else{
                                   html+=`<img style="margin-left: 0px;" class="position-absolute" src="`+res.data[i].MultiInstructor.instructor.InsImage+`" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="`+res.data[i].MultiInstructor.instructor.InstructorName+`" onclick="return check_action(this,'`+res.data[i].MultiInstructor.instructor.InstructorUrl+`');">`;
                                }

                            html+= `</div>`;
                            html+=`<div class="d-block">`;

                                     if (res.data[i].IsFree != null) {
                                        html+=`<p class="price text-right d-inline-block float-start">`+res.data[i].Free+`</p>`;
                                     }else{ 
                                       if (res.data[i].DiscountFlag != null){ 
                                        html+=`<p class="price text-right d-inline-block float-start">`+res.data[0].DiscountPrice+`  <small>`+res.data[0].Price+`</small></p>`;
                                        }else{ 
                                         html+=`<p class="price text-right d-inline-block float-start">`+res.data[0].Price+`</p>`;
                                        }
                                    }
                                html+=`<div class="learn-more-btn"><button class="btn btn-primary">Learn More</button></div></div>
                             </div>
                          </div>
                       </div>
                    </a>
                    <div class="webui-popover-content">
                       <div class="course-popover-content">`;

                       if (res.data[i].LastModified != null){                            
                        html+=`<div class="last-updated fw-500">`+res.data[i].LastUpdated+` `+ res.data[i].LastAddedDateTime +`</div>`;
                       }else{ 
                        html+=`<div class="last-updated">`+res.data[i].LastUpdated+` `+ res.data[i].LastAddedDateTime +`</div>`;
                       }

                    html+=`<div class="course-title">
                             <a class="text-decoration-none text-15px" href="`+res.data[0].SingleCourseUrl+`">`+res.data[0].CourseTitle+`</a>
                          </div>
                          <div class="course-meta">
                             <span class=""><i class="fas fa-play-circle"></i>`+res.data[i].NoOfLession+` `+ res.data[i].Lectures +` </span>
                             <span class=""><i class="fas fa-clock"></i>`+res.data[i].CourseDuration+`</span>
                             <span class=""><i class="fas fa-closed-captioning"></i>`+res.data[i].Language+`</span>
                          </div>
                          <div class="course-subtitle">`+res.data[i].ShortDescription+`</div>
                          <div class="what-will-learn">
                             <ul>`;
                             for(k=0;k<res.data[i].Outcomes.length;k++){
                                html+=`<li>`+res.data[i].Outcomes[k]+`</li>`;
                             }
                        html+=`</ul>
                          </div>
                          <div class="popover-btns">`;

                            if (res.data[i].IsPurchased) {
                                html+=`<div class="purchased"><a href="`+res.data[i].MyCourseUrl+`">`+res.data[i].AlreadyPurchased+`</a></div>`;
                            }else{
                                if (res.data[i].IsFreeCourse!=null){
                                        
                                     html+=`<a href="`+res.data[i].IsFreeUrl+`" class="btn pink radius-10" onclick="handleEnrolledButton()"><?= site_phrase('get_enrolled'); ?></a>`;

                                   }else{
                                     html+=`<button type="button" class="btn red add-to-cart-btn `+res.data[i].addedToCartClass+` big-cart-button-`+res.data[i].CourseId+`" id="`+res.data[i].CourseId+`" onclick="handleCartItems(this)">`+res.data[i].checkAddToCart+`</button>`;

                                    }
                                html+=`<button type="button" class="wishlist-btn `+res.data[i].IsAddedToWishlist+`" title="Add to wishlist" onclick="handleWishList(this)" id="`+res.data[i].CourseId+`"><i class="fas fa-heart"></i></button>`;
                                   
                            }
                        html+=`</div>
                       </div>
                    </div>
                 </div>`;
                }
               $('#featured_data').html(html);
            }else{
                
             $('#featured_data').html('<div class="col-sm-4 mt-4 mb-4 mx-auto"><img src="<?= base_url();?>uploads/system/noresult.png" class="img-fluid"></div>');
            }
            $(self).addClass("active");
            lazyloader();
            custom_popup_script();
        }
    });
	},500)
  });  
  
    $(document).ready(function() {
        lazyloader();
        custom_popup_script();        
    });
    $(".nav-item ").find("#0")[0].click(); // Important Line

    function custom_popup_script(){
        if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            if ($(window).width() >= 840) {
                $('a.has-popover').webuiPopover({
                    trigger: 'hover',
                    animation: 'pop',
                    placement: 'horizontal',
                    delay: {
                        show: 300,
                        hide: null
                    },
                    width: 330
                });
            } else {
                $('a.has-popover').webuiPopover({
                    trigger: 'hover',
                    animation: 'pop',
                    placement: 'vertical',
                    delay: {
                        show: 100,
                        hide: null
                    },
                    width: 335
                });
            }
        }

        if ($(".course-carousel")[0]) {
            $(".course-carousel").slick({
                dots: false,
                infinite: false,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                swipe: false,
                touchMove: false,
                responsive: [
                    { breakpoint: 840, settings: { slidesToShow: 3, slidesToScroll: 3, }, },
                    { breakpoint: 620, settings: { slidesToShow: 2, slidesToScroll: 2, }, },
                    { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1, }, },
                ],
            });
        }

        if ($(".top-istructor-slick")[0]) {
            $(".top-istructor-slick").slick({
                dots: false
            });
        }
    }
</script>
<script src="<?php echo base_url() . 'assets/frontend/default/js/all_pages_script.js'; ?>"></script>