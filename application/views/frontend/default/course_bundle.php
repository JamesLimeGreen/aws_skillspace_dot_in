<style>
.course_btn2{
 	display: inline-block;
    font-family: OpenSans, arial, "sans-serif";
    color: #fff;
    font-size: 14px;
    font-weight: bold;
    margin-top: 14px;
    background: #1d2b51;
    box-sizing: border-box;
    padding: 12px 20px;
    text-align: center;
    transition: background 0.3s;
    min-width: 120px;
    border-radius: 7px;
}
.course_btn2:hover{
	background: #1d2b51;
	color:white;
}
.course_btn1{
 	display: inline-block;
    font-family: OpenSans, arial, "sans-serif";
    color: #fff;
    font-size: 14px;
    font-weight: bold;
    margin-top: 14px;
    background: #b42b75;
    box-sizing: border-box;
    padding: 12px 20px;
    text-align: center;
    transition: background 0.3s;
    min-width: 73px;
    border-radius: 7px;
}
.course_btn1:hover{
	background: #1d2b51;
	color:white;
}
</style>
<!-- Hero Section -->
<div class="container-fluide ">
	<div class="blue breadcrumb-container px-6" >
    	<div class=" px-6 ">
      		<h1 class="breadcrumb-item display-6 fw-bold"><?php echo site_phrase('course_bundles'); ?></h1>
      		<p class="text-white mb-0">
        		<span class="font-weight-bold"><?php echo count($course_bundles->result_array()); ?> </span><?php echo site_phrase('bundles_on_this_page'); ?>
			</p>
		</div>
	</div>
</div>

<div class="container mt-5">

	<div class="row mb-5">
		<div class="col-sm-12 col-md-6"></div>
		<div class="col-sm-12 col-md-6">
            <form class="" action="<?= site_url('course_bundles/search/query'); ?>" method="get">
                <div class="input-group">
                    <input type="text" name="string" value="<?php if(isset($search_string)) echo $search_string; ?>" class="form-control" placeholder="<?= site_phrase('search_for_bundle'); ?>">
                    <button class="btn blue m-0 rounded-0" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
		</div>
	</div>

	<div class="row justify-content-center">
    	<div class="col-lg-12">
			<div class="row">
				<?php if(count($course_bundles->result_array())>0){ ?>
					<?php foreach($course_bundles->result_array() as $bundle):
					    $course_ids = json_decode($bundle['course_ids']);
					    sort($course_ids);
						
						//Bundle Rating
				        $ratings = $this->course_bundle_model->get_bundle_wise_ratings($bundle['id']);
				        $bundle_total_rating = $this->course_bundle_model->sum_of_bundle_rating($bundle['id']);
				        if ($ratings->num_rows() > 0) {
				            $bundle_average_ceil_rating = ceil($bundle_total_rating / $ratings->num_rows());
				        }else {
				            $bundle_average_ceil_rating = 0;
				        }
				    ?>
				    <article class="col-md-6 col-lg-4 mb-5">
				      <!-- Article -->
				      <div class="card border h-100">
				      	<?php if(!empty($bundle['banner']) && file_exists('uploads/course_bundle/banner/' . $bundle['banner'])){?>
				      	  <img src="<?= base_url();?>uploads/course_bundle/banner/<?php echo $bundle['banner'];?>" alt="<?= $bundle['title']; ?>" class="img-fluid lazy">
				      	<?php }else{ ?>  
				      		<img src="<?= base_url();?>uploads/course_bundle/banner/thumbnail.png" alt="<?php echo $bundle['title']; ?>" class="img-fluid lazy">
				        <?php } ?>

				        <div class="card-body pb-1">
				          <div class="mb-3">
				          	<div class="mb-3">
				            	<h5 class="mb-0 pb-0">
				              		<a class="text-inherit text-muted" href="<?php echo site_url('bundle_details/'.$bundle['id'].'/'.rawurlencode(slugify($bundle['title']))); ?>"><?= $bundle['title']; ?></a>
				            	</h5>
				            	<span class="text-13"><?= count($course_ids).' '.site_phrase('courses'); ?></span>
				            </div>
				            <?php $total_courses_price = 0; ?>
				            <?php foreach($course_ids as $key => $course_id):
				                ++$key;
				                $this->db->where('id', $course_id);
				                $this->db->where('status', 'active');
				                $course_details = $this->db->get('course')->row_array();

								if ($course_details['is_free_course'] != 1):
					                if ($course_details['discount_flag'] != 1): ?>
		                                <?php $total_courses_price += $course_details['price'];
		                            else:
		                            	$total_courses_price += $course_details['discounted_price'];
		                            endif;
	                            endif;
	                            if($key <= 3): ?>
					                <div class="row mb-2">
										<div class="col-md-12">
											<a href="<?php echo site_url('home/course/'.rawurlencode(slugify($course_details['title'])).'/'.$course_details['id']); ?>" target="_blank">
												<img src="<?php echo $this->crud_model->get_course_thumbnail_url($course_details['id']); ?>" alt="" class="img-fluid mr-2 float-left " width="60px;">
								                <p class="text-muted p-0 m-0 cursor-pointer text-13 lh-20">
					                                <span class="text-13"><?= $course_details['title']; ?></span>

					                                <?php if ($course_details['is_free_course'] == 1): ?>
					                                    <b><span class="float-right d-block"><?php echo site_phrase('free'); ?></span></b>
					                                <?php else: ?>
					                                    <?php if ($course_details['discount_flag'] != 1): ?>
					                                        <b><span class="float-right d-block"><?php echo currency($course_details['price']); ?></span></b>
					                                    <?php else: ?>
					                                        <b><span class="float-right d-block"><?php echo currency($course_details['discounted_price']); ?></span></b>
					                                    <?php endif; ?>
					                                <?php endif; ?>
					                            </p>
					                        </a>
				                        </div>
				                    </div>
				                    <hr>
			                	<?php endif; ?>
				            <?php endforeach; ?>
				          </div>
				          <div class="row bundle-arrow-down text-center cursor-pointer" id="bundle_arrow_down_<?= $bundle['id']; ?>" onclick="toggleBundleCourses('<?= $bundle['id']; ?>', '<?= count($course_ids); ?>')" style="display:none;">
		                    <div class="col-md-12"><i class="fas fa-angle-down"></i></div>
		                  </div>

		                  <!-- This is loading gif -->
	                      <div class="row bundle-slider closed" id="gif_loader_<?= $bundle['id']; ?>"></div>

	                      <!--Here is load more bundle-->
	                      <div class="row bundle-slider closed" id="course_of_bundle_<?= $bundle['id']; ?>"></div>
				        </div>
				        <div class="card-footer border-0 pt-0">
				          <div class="d-flex justify-content-between align-items-center">
				          	<div class="mr-2">
								<a href="<?= site_url('bundle_details/'.$bundle['id'].'/'.slugify($bundle['title'])); ?>" class="course_btn2 w-100 p-2 mb-2"><?= site_phrase('bundle_details'); ?></a>
							</div>
							<div class="mr-2">
							    <?php if(get_bundle_validity($bundle['id'], $this->session->userdata('user_id')) == 'invalid'): ?>
							        <a href="<?= site_url('course_bundles/buy/'.$bundle['id']); ?>" class="btn course_btn1   w-100 p-2 mb-2"><?= site_phrase('buy'); ?></a>
							    <?php elseif(get_bundle_validity($bundle['id'], $this->session->userdata('user_id')) == 'expire'): ?>
							        <a href="<?= site_url('course_bundles/buy/'.$bundle['id']); ?>" class="btn course_btn1  w-100 p-2 mb-2"><?= currency($bundle['price']); ?> | <?= site_phrase('renew'); ?></a>
							    <?php else: ?>
							        <a href="<?= site_url('home/my_bundles'); ?>" class="course_btn1 w-100 p-2 mb-2"><?= site_phrase('purchased'); ?></a>
							    <?php endif; ?>
							</div>
				            <div class="mr-2">
				            	<span class="d-block h5 text-lh-sm mb-0"><?php echo currency($bundle['price']); ?></span>
								<span class="d-block text-muted text-lh-sm text-13"><del><?php echo currency($total_courses_price); ?></del></span>
				            </div>
				          </div>
				        </div>
				      </div>
				      <!-- End Article -->
				    </article>
				  	<?php endforeach; ?>
				<?php }else{ ?>
					<div class="alert alert-primary" role="alert">
					  <b>Sorry!</b> We could not find any result.
 					</div>
				<?php } ?>
			  <!-- Pagination -->
			  <div class="col-md-12">
			    <div class="d-flex justify-content-between align-items-center mt-8">
			      <small class="d-none d-sm-inline-block text-body"></small>
			      <nav aria-label="Page navigation">
			        <?= $this->pagination->create_links(); ?>
			      </nav>
			    </div>
			  </div>
			  <!-- End Pagination -->
			</div>
		</div>
	</div>
</div>


	<!-- End Hero Section -->
<?php include "course_bundle_scripts.php"; ?>