<style>
   @media (max-width: 767px) {
        .hidden-mobile {
          display: none;
        }
      }
</style>
<section class="menu-area bg-white" id="menu-area">
  <div class="container-xl">
    <nav class="navbar navbar-expand-lg bg-white">

      <ul class="mobile-header-buttons">
        <li><a class="mobile-nav-trigger" href="#mobile-primary-nav">Menu<span></span></a></li>
        <li><a class="mobile-search-trigger hidden-mobile" href="#mobile-search">Search<span></span></a></li>
      </ul>

      <a href="<?php echo site_url(''); ?>" class="navbar-brand" href="#"><img src="<?php echo base_url('uploads/system/'.get_frontend_settings('dark_logo')); ?>" alt="" height="35"></a>

      <?php include 'menu.php'; ?>

      <form class="inline-form" action="<?php echo site_url('home/search'); ?>" method="get">
        <div class="input-group search-box mobile-search">
          <input type="text" name = 'query' class="form-control" placeholder="<?php echo site_phrase('search_for_courses'); ?>">
          <div class="input-group-append">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </form>

      <?php if ($this->session->userdata('admin_login')): ?>
        <div class="instructor-box menu-icon-box ms-auto">
          <div class="icon">
            <a href="<?php echo site_url('admin'); ?>" style="border: 1px solid transparent; margin: 0px; font-size: 14px; width: max-content; border-radius: 5px; max-height: 40px; line-height: 40px; padding: 0px 10px;"><?php echo site_phrase('administrator'); ?></a>
          </div>
        </div>
      <?php endif; ?>

      <div class="cart-box menu-icon-box ms-auto">
        <select class="language_selector" onchange="switch_language(this.value)">
            <?php
             $languages = $this->crud_model->get_all_languages();
             foreach ($languages as $language): ?>
                <?php if (trim($language) != ""): ?>
                    <option value="<?php echo strtolower($language); ?>" <?php if ($this->session->userdata('language') == $language): ?>selected<?php endif; ?>><?php echo ucwords($language);?></option>
                <?php endif; ?>
            <?php endforeach; ?>
          </select>
      </div>

      <div class="cart-box menu-icon-box hidden-mobile cart_items" id = "cart_items">
        <?php include 'cart_items.php'; ?>
      </div>

      <span class="signin-box-move-desktop-helper"></span>
      <div class="sign-in-box btn-group">
        <a href="<?php echo site_url('home/login'); ?>" class="btn btn-sign-in"><?php echo site_phrase('log_in'); ?></a>
        <a href="<?php echo site_url('home/sign_up'); ?>" class="btn btn-sign-up"><?php echo site_phrase('sign_up'); ?></a>
      </div> <!--  sign-in-box end -->
    </nav>
  </div>
</section>
