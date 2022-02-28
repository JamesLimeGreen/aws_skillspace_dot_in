<?php
$instructor_details = $this->user_model->get_all_user($instructor_id)->row_array();
$social_links  = json_decode($instructor_details['social_links'], true);
$course_ids = $this->crud_model->get_instructor_wise_courses($instructor_id, 'simple_array');
$cart_items = $this->session->userdata('cart_items');

$this->db->select('user_id');
$this->db->distinct();
$this->db->where_in('course_id', $course_ids);
$total_students = $this->db->get('enrol')->num_rows();
?>
<section class="instructor-header-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6 order-last order-lg-first text-md-start text-center pt-4 ps-0">
                <h4 class="user-type"><?php echo site_phrase('instructor'); ?></h4>
                <h1 class="instructor-name"><?php echo $instructor_details['first_name'].' '.$instructor_details['last_name']; ?></h1>
                <h2 class="instructor-title"><?php echo $instructor_details['title']; ?></h2>
                <p class="text-12px mt-3">
                    <?php echo $total_students.' '.site_phrase('students_enrolled'); ?>
                </p>
            </div>
            <div class="col-lg-4 col-xl-3 order-first order-lg-last text-center">
                <img class="radius-10" src="<?php echo $this->user_model->get_user_image_url($instructor_details['id']);?>" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<section class="instructor-details-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="order-last order-lg-first col-lg-7 col-xl-6 bg-white radius-8 py-3 px-4">
                <div class="w-100">
                    <h4 class="fw-700"><?php echo site_phrase('about_me'); ?></h4>

                    <div class="biography-content-box view-more-parent">
                        <div class="view-more" onclick="viewMore(this,'hide')"><b><?php echo site_phrase('show_full_biography'); ?></b></div>
                        <div class="biography-content">
                            <?php echo $instructor_details['biography']; ?>
                        </div>
                    </div>
                </div>
             
                <div class="w-100 pb-4">
                    <h4 class="fw-700 my-3"><?php echo site_phrase('my_skills'); ?></h4>

                    <?php $skills = explode(',', $instructor_details['skills']); ?>
                    <?php foreach($skills as $skill): ?>
                      <span class="badge badge-sub-warning text-12px my-1 py-2"><?php echo $skill; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-1 d-none d-lg-block mw-30px"></div>
            <div class="order-first order-lg-last col-lg-4 col-xl-3">
                <div class="row bg-white px-3 py-4 radius-8">
                    <div class="col-4 text-center">
                        <h5 class="fw-700"><?php echo $total_students; ?></h5>
                        <p class="text-12px fw-700 text-muted"><?php echo site_phrase('total_students'); ?></p>
                    </div>
                    <div class="col-4 text-center">
                        <h5 class="fw-700"><?php echo sizeof($course_ids); ?></h5>
                        <p class="text-12px fw-700 text-muted"><?php echo site_phrase('courses'); ?></p>
                    </div>
                    <div class="col-4 text-center">
                        <h5 class="fw-700"><?php echo $this->crud_model->get_instructor_wise_course_ratings($instructor_id, 'course')->num_rows(); ?></h5>
                        <p class="text-12px fw-700 text-muted"><?php echo site_phrase('reviews'); ?></p>
                    </div>

                    <div class="col-12">
                        <div class="instructor-social-links">
                            <?php if($social_links['facebook']): ?>
                                <a href="<?php echo $social_links['facebook']; ?>" target="_blank"><i class="fab fa-facebook-f"></i> <?php echo site_phrase('facebook'); ?></a>
                            <?php endif; ?>

                            <?php if($social_links['twitter']): ?>
                                <a href="<?php echo $social_links['twitter']; ?>" target="_blank"><i class="fab fa-twitter"></i> <?php echo site_phrase('twitter'); ?></a>
                            <?php endif; ?>

                            <?php if($social_links['linkedin']): ?>
                                <a href="<?php echo $social_links['linkedin']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i> <?php echo site_phrase('linkedin'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-xl-10">
            <h3 class="course-carousel-title mb-4 px-2"><?php echo site_phrase('courses'); ?></h3>
            <div class="course-carousel">
                <?php
                foreach ($course_ids as $course_id) :
                    $top_course = $this->crud_model->get_course_by_id($course_id)->row_array();
                    $lessons = $this->crud_model->get_lessons('course', $top_course['id']);
                    $course_duration = $this->crud_model->get_total_duration_of_lesson_by_course_id($top_course['id']);
                    ?>

                     <div class="course-box-wrap">

                            <a onclick="return check_action(this);" href="<?php echo site_url('home/course/' . rawurlencode(slugify($top_course['title'])) . '/' . $top_course['id']); ?>" class="has-popover">
                                <div class="course-box">
                                    <div class="course-image">
                                        <img src="<?= base_url("assets/frontend/default/img/course_thumbnail_placeholder.jpg"); ?>" data-src="<?php echo $this->crud_model->get_course_thumbnail_url($top_course['id']); ?>" alt="<?= $top_course['title']; ?>" class="img-fluid lazy">
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
                                                <span class="badge bg-secondary-start"><i class="fa fa-star star-text"></i> <?= $average_ceil_rating; ?></span>
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
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        if ($(".course-carousel")[0]) {
            $(".course-carousel").slick({
                dots: false,
                infinite: false,
                speed: 300,
                slidesToShow: 3,
                slidesToScroll: 3,
                swipe: false,
                touchMove: false,
                responsive: [
                    { breakpoint: 840, settings: { slidesToShow: 2, slidesToScroll: 2, }, },
                    { breakpoint: 620, settings: { slidesToShow: 1, slidesToScroll: 1, }, },
                ],
            });
        }
    });
</script>