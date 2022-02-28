<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?= $page_title; ?>
                    <a href="<?php echo site_url('admin/banner_form/add_banner'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo get_phrase('add_new_banner'); ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<div class="row">
    <?php if(count($banner)>0){ ?>
    <?php foreach($banner as $val):
    ?>
       
        <div class="col-md-6 col-lg-6 col-xl-4 on-hover-action" id="<?php echo $val['banner_id']; ?>">
            <div class="card">

                <!-- <span class="badge badge-danger banner_badge"><i class="fa fa-trash"></i></span> -->
                <img class="card-img-top" src="<?php echo base_url($val['banner_url'] . $val['banner']); ?>" alt="Card image cap">
                <div class="card-body">
                    <small style="font-style: italic;">
                        <p class="card-text h4"><?= $val['heading']; ?></p>
                    </small>
                </div>

                <div class="card-footer">

                    <ul class="flex-container space-between">
                      <li class="flex-item">
                          <a href="<?php echo site_url('admin/banner_form/edit_banner/' . $val['banner_id']); ?>" class="btn  btn-outline-info btn-sm mb-2" id="banner-edit-btn-<?php echo $val['banner_id']; ?>" style="display: non;" style="margin-right:5px;">
                            <i class="mdi mdi-wrench"></i> <?php echo get_phrase('edit'); ?>
                          </a>
                      </li>
                      <li class="flex-item">
                          <a href="#" data-banner_id="<?= $val['banner_id']; ?>" class="btn btn-icon btn-outline-<?= (($val['status']==1)?"success":"default"); ?> btn-sm mb-2" id="banner-active-btn" style="margin-right:5px;">
                            <i class="mdi mdi-check"></i> <?php echo get_phrase('Activate'); ?>
                          </a>
                      </li>
                      <li class="flex-item">
                          <a href="#" class="btn btn-icon btn-outline-danger btn-sm mb-2" id="banner-delete-btn-<?php echo $val['banner_id']; ?>" style=" display: non;" onclick="confirm_modal('<?php echo site_url('admin/submitBanner/delete/' . $val['banner_id']); ?>');" style="margin-right:5px;">
                             <i class="mdi mdi-delete"></i> <?php echo get_phrase('delete'); ?>
                           </a>
                      </li>
                    </ul>

                    

                    

                    
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    <?php endforeach; } ?>
</div>


<script type="text/javascript">

    $(document).on("click","#banner-active-btn",function(e){
        var banner_id=$(this).attr("data-banner_id");
        var self=this;
        if(confirm("Are you sure?")){
            $.ajax({
                url:"<?= base_url();?>admin/activateBanner",
                type:"post",
                data:{"banner_id":banner_id},
                success:function(success){
                    var data = JSON.parse(success);
                    if(data.status == 0){
                        error_notify(data.msg);
                    }else{
                        success_notify(data.msg);
                        setTimeout(function(){ location.reload(); },1500);
                    }
                },
                error:function(error){
                    error_notify(error);
                }
            });
        }
    });

    // $('.on-hover-action').mouseenter(function() {
    //     var id = this.id;
    //     $('#category-delete-btn-' + id).show();
    //     $('#category-edit-btn-' + id).show();
    // });
    // $('.on-hover-action').mouseleave(function() {
    //     var id = this.id;
    //     $('#category-delete-btn-' + id).hide();
    //     $('#category-edit-btn-' + id).hide();
    // });
</script>