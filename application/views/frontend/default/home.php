<section class="home-banner-area" id="home-banner-area" style="background-image: url('<?= base_url("uploads/system/" . get_frontend_settings('banner_image')); ?>'); background-position: center; background-repeat: no-repeat; padding: 150px 0 175px; background-size: cover; color: #fff;">
    <div class="container-xl">
        <div class="row">
            <div class="col position-relative">
                <div class="home-banner-wrap">
                    <h2 class="fw-bold text-white"><?php echo site_phrase(get_frontend_settings('banner_title')); ?></h2>
                    <p class="text-white"><?php echo site_phrase(get_frontend_settings('banner_sub_title')); ?></p>
                    <form class="" action="<?php echo site_url('home/search'); ?>" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" name="query" placeholder="<?php echo site_phrase('what_do_you_want_to_learn'); ?>?">
                            <div class="input-group-append p-6px bg-white">
                                <button class="btn" type="submit"><?php echo site_phrase('search'); ?> <i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</section>

<section class="home-fact-area">
    <div class="container-lg">
        <div class="row">
            <?php  $courses = $this->crud_model->get_courses(); ?>
            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto mr-auto">
                    <i class="fas fa-bullseye float-start"></i>
                    <div class="text-box">
                        <h4><?php
                            $status_wise_courses = $this->crud_model->get_status_wise_courses();
                            $number_of_courses = $status_wise_courses['active']->num_rows();
                            echo $number_of_courses . ' ' . site_phrase('online_courses'); ?></h4>
                        <p><?php echo site_phrase('explore_a_variety_of_fresh_topics'); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto mr-auto">
                    <i class="fa fa-check float-start"></i>
                    <div class="text-box">
                        <h4><?php echo site_phrase('expert_instruction'); ?></h4>
                        <p><?php echo site_phrase('find_the_right_course_for_you'); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="home-fact-box mr-md-auto mr-auto">
                    <i class="fa fa-clock float-start"></i>
                    <div class="text-box">
                        <h4> <?php echo site_phrase('lifetime_access'); ?></h4>
                        <p> <?php echo site_phrase('learn_on_your_schedule'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-5">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <h3 class="course-carousel-title mb-4"><?php echo site_phrase('top_categories'); ?></h3>
				
                <!-- page loader -->
                <!-- <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>-->
                  <!-- shown-after-loading style="display: none;" -->
                <div class="course-carousel">
                    
                    <?php foreach($top_10_categories as $top_10_category): ?>
                        <?php $category_details = $this->crud_model->get_category_details_by_id($top_10_category['sub_category_id'])->row_array();?>
                        <div class="course-box-wrap">
                            <a onclick="return check_action(this);" href="<?php echo site_url('home/courses?category='.$category_details['slug']); ?>">
                                <div class="course-box">
                                    <div class="course-image text-center">
                                        <i class="<?php echo $category_details['font_awesome_class']; ?> text-primary fa-3x m-4"></i>
                                        <p class="mt-4 text-dark"><?php echo $category_details['name']; ?></p>
                                        <small class=""><?php echo $top_10_category['course_number'].' '.site_phrase('courses'); ?></small>
										
                                    </div>

                                </div>
                            </a>

                        </div>
                    <?php endforeach; ?>
                <!-- </div> -->
            </div>
        </div>
    </div>
</section>

<?php if(count($top_10_categories) >0):?>
<section class="course-carousel-area">
    <div class="container-lg">
        <div class="row">

            <div class="col">
                <h3 class="course-carousel-title mb-4"><?php echo site_phrase('top_courses'); ?></h3>

                <!-- page loader -->
                <!-- <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div> -->
                 <!-- shown-after-loading" style="display: none;" -->
                <div class="course-carousel">
                    <?php $current_language = $this->session->userdata("language"); ?>

                    <?php  $top_courses = $this->crud_model->get_top_courses($current_language)->result_array();
                    $cart_items = $this->session->userdata('cart_items');
                    foreach ($top_courses as $top_course) : ?>
                        <?php
                            $lessons = $this->crud_model->get_lessons('course', $top_course['id']);
                            $course_duration = $this->crud_model->get_total_duration_of_lesson_by_course_id($top_course['id']);
                        ?>
                        <div class="course-box-wrap">

                            <a onclick="return check_action(this);" href="<?php echo site_url('home/course/' . rawurlencode(slugify($top_course['title'])) . '/' . $top_course['id']); ?>" class="has-popover">
                                <div class="course-box">
                                    <div class="course-image">
                                        <img src="<?php echo $this->crud_model->get_course_thumbnail_url($top_course['id']); ?>" alt="<?= $top_course['title']; ?>" class="img-fluid lazy">
                                    </div>
                                    <div class="course-details">
                                        <h5 class="title"><?= $top_course['title']; ?></h5>
                                        <div class="rating">
                                            <?php
                                            $total_rating =  $this->crud_model->get_ratings('course', $top_course['id'], true)->row()->rating;
                                            $number_of_ratings = $this->crud_model->get_ratings('course', $top_course['id'])->num_rows();
                                            if ($number_of_ratings > 0) {
                                                $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                                            } else {
                                                $average_ceil_rating = 0;
                                            }
                                           ?> 
                                            <div class="d-inline-block">
                                                <span class="badge badge-sub-warning text-11px"><?php echo site_phrase($top_course['level']); ?></span> &nbsp;&nbsp;
                                                <span class="badge bg-secondary-start"><i class="fa fa-star star-text"></i> <?php echo $average_ceil_rating; ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex text-dark">
                                            <div class="">
                                                <i class="fas fa-book-reader text-14px"></i>
                                                <span class="text-muted text-12px"> <?php echo $lessons->num_rows().' '.site_phrase('lessons'); ?></span>
                                            </div>

                                            <div class="ms-3">
                                                <i class="fas fa-clock text-14px"></i>
                                                <span class="text-muted text-12px"> <?php echo $course_duration; ?></span>
                                            </div>
                                        </div>

                                        <div class="d-block">
                                            <div class="floating-user d-inline-block">
                                                <?php if ($top_course['multi_instructor']):
                                                    $instructor_details = $this->user_model->get_multi_instructor_details_with_csv($top_course['user_id']);
                                                    $margin = 0;
                                                    foreach ($instructor_details as $key => $instructor_detail) { ?>
                                                        <img style="margin-left: <?php echo $margin; ?>px;" class="position-absolute" src="<?php echo $this->user_model->get_user_image_url($instructor_detail['id']); ?>" width="25px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $instructor_detail['first_name'].' '.$instructor_detail['last_name']; ?>" alt="<?php echo $instructor_detail['first_name'].' '.$instructor_detail['last_name']; ?>" onclick="return check_action(this,'<?php echo site_url('home/instructor_page/'.$instructor_detail['id']); ?>');">
                                                        <?php $margin = $margin+17; ?>
                                                    <?php } ?>
                                                <?php else: ?>
                                                    <?php $user_details = $this->user_model->get_all_user($top_course['user_id'])->row_array(); ?>
                                                    
                                                    <img src="<?php echo $this->user_model->get_user_image_url($user_details['id']); ?>" width="30px" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>" alt="<?php echo $user_details['first_name'].' '.$user_details['last_name']; ?>"  onclick="return check_action(this,'<?php echo site_url('home/instructor_page/'.$user_details['id']); ?>');">
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="d-block">
                                            <?php if ($top_course['is_free_course'] == 1) : ?>
                                                <p class="price text-right d-inline-block float-start"><?php echo site_phrase('free'); ?></p>
                                            <?php else : ?>
                                                <?php if ($top_course['discount_flag'] == 1) : ?>
                                                    <p class="price text-right d-inline-block float-start"><?php echo currency($top_course['discounted_price']); ?> <small><?php echo currency($top_course['price']); ?></small></p>
                                                <?php else : ?>
                                                    <p class="price text-right d-inline-block float-start"><?php echo currency($top_course['price']); ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                         <div class="learn-more-btn">
                                            <button class="btn btn-primary">Learn More</button>
                                         </div>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div class="webui-popover-content">
                                <div class="course-popover-content">
                                    <?php if ($top_course['last_modified'] == "") : ?>
                                        <div class="last-updated fw-500"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $top_course['date_added']); ?></div>
                                    <?php else : ?>
                                        <div class="last-updated"><?php echo site_phrase('last_updated') . ' ' . date('D, d-M-Y', $top_course['last_modified']); ?></div>
                                    <?php endif; ?>

                                    <div class="course-title">
                                        <a class="text-decoration-none text-15px" href="<?php echo site_url('home/course/' . rawurlencode(slugify($top_course['title'])) . '/' . $top_course['id']); ?>"><?php echo $top_course['title']; ?></a>
                                    </div>
                                    <div class="course-meta">
                                        <?php if ($top_course['course_type'] == 'general') : ?>
                                            <span class=""><i class="fas fa-play-circle"></i>
                                                <?php echo $this->crud_model->get_lessons('course', $top_course['id'])->num_rows() . ' ' . site_phrase('lessons'); ?>
                                            </span>
                                            <span class=""><i class="far fa-clock"></i>
                                                <?php echo $course_duration; ?>
                                            </span>
                                        <?php elseif ($top_course['course_type'] == 'scorm') : ?>
                                            <span class="badge bg-light"><?= site_phrase('scorm_course'); ?></span>
                                        <?php endif; ?>
                                        <span class=""><i class="fas fa-closed-captioning"></i><?php echo ucfirst($top_course['language']); ?></span>
                                    </div>
                                    <div class="course-subtitle"><?php echo $top_course['short_description']; ?></div>
                                    <div class="what-will-learn">
                                        <ul>
                                            <?php
                                            $outcomes = json_decode($top_course['outcomes']);
                                            foreach ($outcomes as $outcome) : ?>
                                                <li><?php echo $outcome; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="popover-btns">
                                        <?php if (is_purchased($top_course['id'])) : ?>
                                            <div class="purchased">
                                                <a href="<?php echo site_url('home/my_courses'); ?>"><?php echo site_phrase('already_purchased'); ?></a>
                                            </div>
                                        <?php else : ?>
                                            <?php if ($top_course['is_free_course'] == 1) :
                                                if ($this->session->userdata('user_login') != 1) {
                                                    $url = "#";
                                                } else {
                                                    $url = site_url('home/get_enrolled_to_free_course/' . $top_course['id']);
                                                } ?>
                                                <a href="<?php echo $url; ?>" class="btn pink radius-10" onclick="handleEnrolledButton()"><?php echo site_phrase('get_enrolled'); ?></a>
                                            <?php else : ?>
                                                <button type="button" class="btn red add-to-cart-btn <?php if (in_array($top_course['id'], $cart_items)) echo 'addedToCart'; ?> big-cart-button-<?php echo $top_course['id']; ?>" id="<?php echo $top_course['id']; ?>" onclick="handleCartItems(this)">
                                                    <?php
                                                    if (in_array($top_course['id'], $cart_items))
                                                        echo site_phrase('added_to_cart');
                                                    else
                                                        echo site_phrase('add_to_cart');
                                                    ?>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="wishlist-btn <?php if ($this->crud_model->is_added_to_wishlist($top_course['id'])) echo 'active'; ?>" title="Add to wishlist" onclick="handleWishList(this)" id="<?php echo $top_course['id']; ?>"><i class="fas fa-heart"></i></button>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <!-- </div> -->
        </div>
    </div>
</section>

<section class="course-carousel-area">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <h3 class="course-carousel-title mb-4"><?php echo site_phrase('featured_courses'); ?></h3>

                <!-- page loader -->
                <!-- <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div> -->

                <!-- <div class="course-carousel shown-after-loading" style="display: none;"> -->
                 <div class="row">
                    <?php  $cate=$this->crud_model->get_categories()->result_array(); ?>
                    <?php if(count($cate)>0){ ?>
                        
                        <div class="col-sm-12 mb-3">
                            <ul class="nav custom__tabls_btn">
                              <li class="nav-item">
                                <a class="nav-link active" id="0" aria-current="page" href="javascript:;" >All</a>
                              </li>
                              <?php foreach($cate as $val): ?>
                              <li class="nav-item ">
                                <a class="nav-link" href="javascript:;" id="<?= $val['id'];?>" ><?= $val['name']; ?></a>
                              </li>
                              <?php endforeach; ?>
                              
                            </ul>
                        </div>
                        
                    <?php } ?>
                 </div>
                 <div class="row mt-2" id="featured_data">
                    <!-- Come All The Courses -->
                </div>
            </div>

            <div class="col-sm-12 mb-4 text-center">
                <a href="<?= base_url('home/courses');?>" class="float-end btn btn-outline-primary">See All Courses</a>
            </div>
        </div>
    </div>
</section>


<?php if(get_frontend_settings('blog_visibility_on_the_home_page') && count($latest_blogs)>0): ?>
    <section class="section-blog py-5">
        <div class="container-lg">
            <div class="row row-cols-1 row-cols-lg-4 row-cols-md-3 g-4 justify-content-center">
                <div class="col-12">
                    <h4 class="fw-700"><?php echo site_phrase('latest_blogs'); ?></h4>
                </div>
                
                <?php foreach($latest_blogs as $latest_blog): ?>
                    <?php $user_details = $this->user_model->get_all_user($latest_blog['user_id'])->row_array(); ?>
                    <div class="col">
                        <div class="card radius-10">
                            <?php $blog_thumbnail = 'uploads/blog/thumbnail/'.$latest_blog['thumbnail']; ?>
                            <?php if(file_exists($blog_thumbnail) && is_file($blog_thumbnail)): ?>
                                <img src="<?php echo base_url($blog_thumbnail); ?>" class="card-img-top radius-top-10" alt="<?php echo $latest_blog['title']; ?>">
                            <?php else: ?>
                                <img src="<?php echo base_url('uploads/blog/thumbnail/placeholder.png'); ?>" class="card-img-top radius-top-10" alt="<?php echo $latest_blog['title']; ?>">
                            <?php endif; ?>
                            <div class="card-body pt-4">
                                <p class="card-text">
                                    <small class="text-muted"><?php echo site_phrase('created_by'); ?> - <a href="<?php echo site_url('home/instructor_page/'.$latest_blog['user_id']); ?>"><?php echo $user_details['first_name'].' '.$user_details['last_name']; ?></a></small>
                                </p>
                                <h5 class="card-title blog"><a href="<?php echo site_url('blog/details/'.slugify($latest_blog['title']).'/'.$latest_blog['blog_id']); ?>"><?php echo $latest_blog['title']; ?></a></h5>
                                <p class="card-text ellipsis-line-3">
                                    <?php echo strip_tags(htmlspecialchars_decode($latest_blog['description'])); ?>
                                </p>
                                
                                <a class="fw-600" href="<?php echo site_url('blog/details/'.slugify($latest_blog['title']).'/'.$latest_blog['blog_id']); ?>"><?php echo site_phrase('more_details'); ?></a>
                                
                                <p class="card-text mt-2 mb-0">
                                    <small class="text-muted text-12px"><?php echo site_phrase('published'); ?> - <?php echo get_past_time($latest_blog['added_date']); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="col-12">
                    <a class="float-end btn btn btn-outline-primary px-3 fw-600" href="<?php echo site_url('blogs'); ?>"><?php echo site_phrase('view_all'); ?></a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="join_our_section mt-4">
    <?php  $banner=$this->crud_model->get_banner()->row_array(); ?>
    <div class="container-fluid plr-0">
        <div class="row_custom">
            <div class="custom_col _col_left">
                <div class="section_img">
                    <img src="<?= base_url().$banner['banner_url'].$banner['banner'];?>" alt="<?= $banner['heading'];?>" class="lazy"/>
                </div>
            </div>
            <div class="custom_col">
                <div class="join_content">
                    <h3><?= $banner['heading']; ?></h3>
                    <p><?= $banner['description']; ?></p>
                    <a class="bottom_btn" href="<?= base_url('home/login');?>">Sign up</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border_bottom_s"></section>
<?php endif;?>


