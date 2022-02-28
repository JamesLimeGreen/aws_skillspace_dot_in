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

<section class="mb-5">
    <div class="container-lg">
        <div class="row">
            <div class="col">
                <h3 class="course-carousel-title mb-4"><?php echo site_phrase('top_categories'); ?></h3>
				
                <!-- page loader -->
                <div class="animated-loader"><div class="spinner-border text-secondary" role="status"></div></div>

                <div class="course-carousel shown-after-loading" style="display: none;">
                    
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
                </div>
            </div>
        </div>
    </div>
</section>


