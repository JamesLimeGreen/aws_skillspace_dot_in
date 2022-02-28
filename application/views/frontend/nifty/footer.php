<!-- ========== FOOTER ========== -->
  <footer class="bg-light">
    <div class="container">
      <div class="space-top-2 space-bottom-1 space-bottom-lg-2">
        <div class="row justify-content-lg-between">
          <div class="col-lg-3 ml-lg-auto mb-5 mb-lg-0">
            <!-- Logo -->
            <div class="mb-4">
              <a href="<?= site_url('home'); ?>" aria-label="Front">
                <img class="brand" src="<?= base_url('uploads/system/'.get_frontend_settings('dark_logo')); ?>" alt="Logo">
              </a>
            </div>
            <!-- End Logo -->

            <!-- Nav Link -->
            <ul class="nav nav-sm nav-x-0 flex-column">
              <li class="nav-item">
                <a class="nav-link media" href="javascript:;"><?= get_settings('slogan'); ?></a>
                <a class="nav-link media" href="javascript:;">
                  <span class="media">
                    <span class="fas fa-location-arrow mt-1 mr-2"></span>
                    <span class="media-body">
                      <?= get_settings('address'); ?>
                    </span>
                  </span>
                </a>
              </li>
            </ul>
            <!-- End Nav Link -->
          </div>

          <div class="col-6 col-md-3 col-lg">
            <h5><?= site_phrase('useful_links'); ?></h5>

            <!-- Nav Link -->
            <ul class="nav nav-sm nav-x-0 flex-column">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('home/courses'); ?>">
                  <span class="media align-items-center">
                    <span class="media-body"><?= site_phrase('courses'); ?></span>
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('home/login'); ?>">
                  <span class="media align-items-center">
                    <span class="media-body"><?= site_phrase('login'); ?></span>
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('home/sign_up'); ?>">
                  <span class="media align-items-center">
                    <span class="media-body"><?= site_phrase('registration'); ?></span>
                  </span>
                </a>
              </li>
            </ul>
            <!-- End Nav Link -->
          </div>

          <div class="col-6 col-md-3 col-lg mb-5 mb-lg-0">
            <h5><?= site_phrase('about_us'); ?></h5>

            <!-- Nav Link -->
            <ul class="nav nav-sm nav-x-0 flex-column">
              <li class="nav-item"><a class="nav-link" href="<?php echo site_url('home/about_us'); ?>"><?= site_phrase('about'); ?></a></li>
            </ul>
            <!-- End Nav Link -->
          </div>

          <div class="col-6 col-md-3 col-lg mb-5 mb-lg-0">
            <h5><?= site_phrase('contact'); ?></h5>
            <!-- Nav Link -->
            <ul class="nav nav-sm nav-x-0 flex-column">
              <li class="nav-item">
                <a class="nav-link media" href="tel:1-062-109-9222">
                  <span class="media">
                    <span class="fas fa-phone-alt mt-1 mr-2"></span>
                    <span class="media-body">
                      <?= get_settings('phone'); ?>
                    </span>
                  </span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link media" href="mailto:<?= get_settings('system_email'); ?>">
                  <span class="media">
                    <span class="fas fa-envelope mt-1 mr-2"></span>
                    <span class="media-body">
                      <?= get_settings('system_email'); ?>
                    </span>
                  </span>
                </a>
              </li>
            </ul>
            <!-- End Nav Link -->
          </div>        
        </div>
      </div>

      <hr class="my-0">

      <div class="space-1">
        <div class="row align-items-md-center mb-7">
          <div class="col-md-6 mb-4 mb-md-0">
            <!-- Nav Link -->
            <ul class="nav nav-sm nav-x-0 align-items-center">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('home/privacy_policy'); ?>"><?= site_phrase('privacy_policy'); ?></a>
              </li>
              <li class="nav-item opacity mx-2">|</li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('home/terms_and_condition'); ?>"><?= site_phrase('terms_and_condition'); ?></a>
              </li>
              <li class="nav-item opacity mx-2">|</li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('home/refund_policy'); ?>"><?= site_phrase('refund_policy'); ?></a>
              </li>
            </ul>
            <!-- End Nav Link -->
          </div>

          <div class="col-md-6 text-md-right">
            <ul class="list-inline mb-0">
              <?php $social_links = json_decode($this->user_model->get_admin_details()->row('social_links')); ?>
              <!-- Social Networks -->
              <li class="list-inline-item">
                <a class="btn btn-xs btn-icon btn-soft-secondary" href="<?= $social_links->facebook; ?>">
                  <i class="fab fa-facebook-f"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a class="btn btn-xs btn-icon btn-soft-secondary" href="<?= $social_links->twitter; ?>">
                  <i class="fab fa-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a class="btn btn-xs btn-icon btn-soft-secondary" href="<?= $social_links->linkedin; ?>">
                  <i class="fab fa-linkedin"></i>
                </a>
              </li>
              <!-- End Social Networks -->

              <!-- Language -->
              <li class="list-inline-item">
                <div class="hs-unfold">
                  <a class="js-hs-unfold-invoker dropdown-toggle btn btn-xs btn-soft-secondary" href="javascript:;"
                     data-hs-unfold-options='{
                      "target": "#footerLanguage",
                      "type": "css-animation",
                      "animationIn": "slideInDown"
                     }'>
                    <span><?php echo ucwords($this->session->userdata('language')); ?></span>
                  </a>

                  <div id="footerLanguage" class="hs-unfold-content dropdown-menu dropdown-unfold dropdown-menu-bottom mb-2">
                  <?php
                  $languages = $this->crud_model->get_all_languages();
                  foreach ($languages as $language): ?>
                    <?php if (trim($language) != "" && $this->session->userdata('language') != strtolower($language)): ?>
                      <a id="change_lang" class="dropdown-item" onclick="switch_language('<?php echo strtolower($language); ?>')" ><?php echo ucwords($language);?></a>
                    <?php endif; ?>
                  <?php endforeach; ?>
                  </div>
                </div>
              </li>
              <!-- End Language -->
            </ul>
          </div>
        </div>

        <!-- Copyright -->
        <div class="w-md-75 text-lg-center mx-lg-auto">
          <p class="text-muted small">&copy; <a class="text-muted" href="<?= get_settings('footer_link'); ?>"><?= get_settings('footer_text'); ?>. <?= site_phrase('all_rights_reserved'); ?>.</p>
          
        </div>
        <!-- End Copyright -->
      </div>
    </div>
  </footer>
  <!-- ========== END FOOTER ========== -->
<script type="text/javascript">
  function switch_language(language) {
      $.ajax({
          url: '<?php echo site_url('home/site_language'); ?>',
          type: 'post',
          data: {language : language},
          success: function(response) {
              //console.log(response);
                 //window.location.href = '<?php echo base_url(uri_string()); ?>?lang='+response;

                var str = window.location.href = '<?php echo base_url(); ?>lang/'+response+'/';                   

                // var uri = window.location.toString();
                //   if (uri.indexOf("?") > 0) {
                //       var clean_uri = uri.substring(2, uri.indexOf("?"));
                //       window.history.replaceState({}, '<?php echo base_url(uri_string()); ?>lang='+response);
                // }

              //setTimeout(function(){ location.reload(); }, 500);
          }
      });
  }
</script>
