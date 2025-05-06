<footer>
  <?php $this->load->view('block-partner'); ?>
  <!-- <div class="section-footer">
    <div class="container">
      <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
          <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <div class="section-footer--title">
                <h3>CHĂM SÓC KHÁCH HÀNG</h3>
              </div>
              <div class="section-footer--content">
                <ul>
                  <?php echo menu_single(0, $menu_left_list, $menu_left_data); ?>
                </ul>
              </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <div class="section-footer--title">
                <h3>CHÍNH SÁCH</h3>
              </div>
              <div class="section-footer--content">
                <ul>
                  <?php echo menu_single(0, $menu_bottom_list, $menu_bottom_data); ?>
                </ul>
              </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
              <div class="section-footer--title">
                <h3>TUYỂN DỤNG</h3>
              </div>
              <div class="section-footer--content">
                <ul>
                  <?php echo menu_single(0, $menu_right_list, $menu_right_data); ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 offset-xl-0 offset-lg-0 offset-md-4 offset-sm-3">
          <div class="section-footer--title">
            <h3>HÌNH THỨC THANH TOÁN</h3>
          </div>
          <div class="section-footer--content">
            <img src="<?php echo get_asset('img_path'); ?>icon-checkout_11.png" alt="" class="img-fluid">
            <img src="<?php echo get_asset('img_path'); ?>icon-checkout_13.png" alt="" class="img-fluid">
            <img src="<?php echo get_asset('img_path'); ?>icon-checkout_15.png" alt="" class="img-fluid">
            <img src="<?php echo get_asset('img_path'); ?>icon-checkout_17.png" alt="" class="img-fluid">
          </div>
          <hr>
          <div class="box-newsletter">
            <form method="POST" action="<?php echo base_url('newsletter'); ?>" id="newsletter-signup">
              <div class="input-group">
                <input type="email" id="signup_email" name="signup_email" class="form-control form-control-sm" placeholder="Email của bạn">
                <div class="input-group-append">
                  <button class="btn btn-sm btn-success" type="submit">GỬI</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div> -->
  <div class="box-footer">
    <div class="container">
      <div class="row">
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="block-footer-title">
                <h3><?php echo isset($info_address_center_none['title']) ? $info_address_center_none['title'] : ''; ?></h3>
              </div>
              <div class="block-content">
                <p><?php echo isset($info_address_center_none['content']) ? $info_address_center_none['content'] : ''; ?></p>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              <div class="block-footer-title">
                <h3><?php echo isset($info_service_footer_none['title']) ? $info_service_footer_none['title'] : ''; ?></h3>
              </div>
              <div class="block-content-service">
                <p><?php echo isset($info_service_footer_none['content']) ? $info_service_footer_none['content'] : ''; ?></p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
          <div class="block-footer-title">
            <h3>FANPAGE</h3>
          </div>
          <div class="block-content">
            <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FHo%25C3%25A0ng-Kim_Group-104544012245127&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=771511799994976" width="340" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
           
          </div>
        </div>
        <div class="clearfix"></div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <hr class="hr-footer mb-5">
          </div>
        <div class="clearfix"></div>
        <?php if(isset($footer_introdue_none) && is_array($footer_introdue_none) && !empty($footer_introdue_none)): ?>
          <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 offset-xl-2 offset-lg-2 offset-md-2 offset-sm-0">
            <?php foreach ($footer_introdue_none as $value): ?>
              <div class="box-footer-logo">
                <a href="<?php echo $value['link']; ?>"><img src="<?php echo base_url(get_module_path('images') . ((trim($value['image']) != '') ? $value['image'] : $value['image'])); ?>" alt="" class="img-fluid"></a>
              </div>
              <a href='http://online.gov.vn/Home/WebDetails/79232><img alt='online.gov.vn' title='online.gov.vn' src='http://online.gov.vn/Content/EndUser/LogoCCDVSaleNoti/logoSaleNoti.png'/></a>

              <div class="box-footer-content">
                <h3><?php echo $value['title']; ?></h3>
                <?php echo $value['content']; ?>
              </div>
            <?php endforeach; ?>
            <div class="clearfix"></div>
          </div>
        <?php endif; ?>
      </div>

    </div>

  </div>
  <div class="box-copyright">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <p><?php echo isset($info_copyright_none['content']) ? $info_copyright_none['content'] : ''; ?></p>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <p class="text-xl-right text-lg-right text-md-right text-sm-right">Design by Thietkewebuytin.vn</p>
        </div>
      </div>
    </div>
  </div>
</footer>
<div class="modal">
    <div class="modal_overlay">

    </div>
    <div class="full-model">
        <div class="card">
            <div id="close" class="close-item">+</div>
            <div class="card-body">
                <img class="img_abc" src="./assets/images/763e51a65c14aa4af305.jpg" alt="">
            </div>
        </div>
    </div>
</div>
