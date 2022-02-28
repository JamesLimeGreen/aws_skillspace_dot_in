<style>

  .hidden-large {
    display: none;
  }
    @media (max-width: 767px)  {
      .mobile-menu-helper-bottom {
        display: block;
      }

      .sider_list_custom ul {
        padding: 0px;
        position: inherit;
      }

      .sider_list_custom ul li {
        list-style: none;
      }
      .search-box {
        margin-right: 50px;
        padding: 0 0;
        max-width: 258px;
        margin-top: 237px;
      }
      .inline-form{
        z-index: 9999;
      }
      .sider_list_custom ul li a i {
        margin-right: 10px;
      }
      .menu-icon-box .icon .number{
		top: 17px;
		right: 196px;
      }
    }
</style>
<div class="main-nav-wrap">
  <div class="mobile-overlay"></div>
  <ul class="mobile-main-nav">
    <div class="mobile-menu-helper-top"></div>
    <li class="has-children text-nowrap fw-bold">
      <a href="">
        <i class="fas fa-th d-inline text-20px"></i>
        <span class="fw-500"><?= site_phrase('categories'); ?></span>
        <span class="has-sub-category"><i class="fas fa-angle-right"></i></span>
      </a>
      <ul class="category corner-triangle top-left is-hidden pb-0">
        <li class="go-back"><a href=""><i class="fas fa-angle-left"></i><?php echo site_phrase('menu'); ?></a></li>
        <?php
        $categories = $this->crud_model->get_categories()->result_array();
        foreach ($categories as $key => $category) : ?>
          <li class="has-children">
            <a href="javascript:;" class="py-2" onclick="redirect_to('<?php echo site_url('home/courses?category=' . $category['slug']); ?>')">
              <span class="icon"><i class="<?php echo $category['font_awesome_class']; ?>"></i></span>
              <!--  style="text-overflow: ellipsis !important;white-space: nowrap;overflow: hidden;" -->
              <span><?php echo ellipsis($category['name'], 25); ?></span>
              <span class="has-sub-category"><i class="fas fa-angle-right"></i></span>
            </a>
            <ul class="sub-category is-hidden">
              <li class="go-back-menu"><a href=""><i class="fas fa-angle-left"></i><?php echo site_phrase('menu'); ?></a></li>
              <li class="go-back"><a href="">
                  <i class="fas fa-angle-left"></i>
                  <span class="icon"><i class="<?php echo $category['font_awesome_class']; ?>"></i></span>
                  <?php echo $category['name']; ?>
                </a></li>
              <?php
              $sub_categories = $this->crud_model->get_sub_categories($category['id']);
              foreach ($sub_categories as $sub_category) : ?>
                <li><a href="<?php echo site_url('home/courses?category=' . $sub_category['slug']); ?>"><?php echo $sub_category['name']; ?></a></li>
              <?php endforeach; ?>
            </ul>
          </li>
        <?php endforeach; ?>
        <li class="all-category-devided mb-0 p-0">
          <a href="<?php echo site_url('home/courses'); ?>" class="py-3">
            <span class="icon"><i class="fa fa-align-justify"></i></span>
            <span><?php echo site_phrase('all_courses'); ?></span>
          </a>
        </li>
        <?php if (addon_status('course_bundle')) : ?>
          <li class="all-category-devided m-0 p-0">
            <a href="<?php echo site_url('course_bundles'); ?>" class="py-3">
              <span class="icon"><i class="fas fa-cubes"></i></span>
              <span><?php echo site_phrase('course_bundles'); ?></span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </li>
    <li>
      <div class="mobile-menu-helper-bottom hidden-large">
      <div class="cart-box menu-icon-box cart_items" id="cart_items">
          <?php include 'cart_items.php'; ?>
        </div>
        <div class="sider_list_custom">
          <ul>
            <li> <a class="mobile-search-trigger hidden-mobile" href="#mobile-search"><span></span></a></li>
          </ul>
        </div>
        
      </div>
    </li>
  </ul>
</div>