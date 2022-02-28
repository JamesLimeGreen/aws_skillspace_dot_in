<!-- start page title -->
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?= $page_title; ?></h4>
                <a href="<?php echo site_url('admin/banner'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-eye"></i><?php echo get_phrase('show_banner'); ?></a>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row justify-content-center">
    <div class="col-xl-7">
        <div class="card">
            <div class="card-body">
              <div class="col-lg-12">
                <h4 class="mb-3 header-title"><?php echo get_phrase('banner_add_form'); ?></h4>

                <form class="required-form" action="<?php echo site_url('admin/submitBanner/add'); ?>" method="post" enctype="multipart/form-data">
                    

                    <div class="form-group">
                        <label for="heading"><?php echo get_phrase('banner_heading'); ?><span class="required">*</span></label>
                        <input type="text" class="form-control" id="heading" name = "heading" placeholder="Enter Heading" required>
                    </div>


                    <!-- <div class="form-group">
                        <label for="font_awesome_class"><?php echo get_phrase('icon_picker'); ?></label>
                        <input type="text" id ="font_awesome_class" name="font_awesome_class" class="form-control icon-picker" autocomplete="off">
                    </div> -->

                    <div class="form-group" id = "thumbnail-picker-area">
                        <label> <?php echo get_phrase('banner_image'); ?> <small>(<?php echo get_phrase('the_image_size_should_be'); ?>: 200 X 255)</small> </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" required="" class="custom-file-input" id="category_thumbnail" name="banner" accept="image/*" onchange="changeTitleOfImageUploader(this)">
                                <label class="custom-file-label" for="category_thumbnail"><?php echo get_phrase('choose_banner'); ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"></textarea>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="checkRequiredFields()"><?php echo get_phrase("submit"); ?></button>
                </form>
              </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<script type="text/javascript">
    function checkCategoryType(category_type) {
        if (category_type > 0) {
            $('#thumbnail-picker-area').hide();
        }else {
            $('#thumbnail-picker-area').show();
        }
    }
</script>
