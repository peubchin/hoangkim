<article>
  <?php if (isset($slideshow_none) && is_array($slideshow_none) && !empty($slideshow_none)): ?>
    <section>
      <div class="box-slider">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <?php
            $i = -1;
            foreach ($slideshow_none as $value):
              $i++;
              ?>
              <div class="carousel-item<?php echo $i == 0 ? ' active' : ''; ?>">
                <a href="<?php echo $value['link']; ?>"><img class="img-fluid"
                    src="<?php echo base_url(get_module_path('images') . ((trim($value['image']) != '') ? $value['image'] : $value['image'])); ?>"
                    alt=""></a>
                <div class="overlay-caption">
                  <h3><?php echo $value['title']; ?></h3>
                  <p><?php echo $value['content']; ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </a>
          <a class="carousel-control-next" href="#myCarousel" data-slide="next">
            <span class="carousel-control-next-icon"></span>
          </a>
        </div>
      </div>
      <!--<div class="section-sub container-flid">
            <div class="posts-list flex-box flex-box-3i flex-space-20">
                <div class="list-item">
                    <article class="post post--horizontal post--horizontal-xxs post--horizontal-middle">
                        <div class="post__thumb atbs-thumb-object-fit">
                            <a href="#single-url">
                                <img src="assets/images/icon_tuvan.png" alt="image">
                            </a>
                        </div>
                        <div class="post__text">
                            <h3 class="post__title f-w-700 f-16">
                                <a href="#single-url"> TƯ VẤN &amp; CHĂM SÓC 1 KÈM 1</a>
                            </h3>
                            <div class="post__excerpt f-16">
                                Trong suốt quá trình sử dụng
                            </div>
                        </div>
                    </article>
                </div>
                <div class="list-item">
                    <article class="post post--horizontal post--horizontal-xxs post--horizontal-middle">
                        <div class="post__thumb atbs-thumb-object-fit">
                            <a href="#single-url">
                                <img src="assets/images/product.png" alt="image">
                            </a>
                        </div>
                        <div class="post__text">
                            <h3 class="post__title f-w-700 f-16">
                                <a href="#single-url"> SẢN PHẨM 100% CHÍNH HÃNG</a>
                            </h3>
                            <div class="post__excerpt f-16">
                                Cam kết và đảm bảo về nguồn gốc
                            </div>
                        </div>
                    </article>
                </div>
                <div class="list-item">
                    <article class="post post--horizontal post--horizontal-xxs post--horizontal-middle">
                        <div class="post__thumb atbs-thumb-object-fit">
                            <a href="#single-url">
                                <img src="assets/images/return15day.png" alt="image">
                            </a>
                        </div>
                        <div class="post__text">
                            <h3 class="post__title f-w-700 f-16">
                                <a href="#single-url"> CHO PHÉP TRẢ HÀNG</a>
                            </h3>
                            <div class="post__excerpt f-16">
                                Đổi trả trong vòng 15 ngày
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>-->
      <!--<div class="atbs-block atbs-block--fullwidth atbs-posts--listing-grid-no-sidebar-1">
            <div class="container">
                <!--<div class="block-heading block-heading-style-1 text-center">
                    <h4 class="block-heading__title f-30 f-w-400">
                        Sản phẩm thịnh hành
                    </h4>
                </div>-->
      <!--<div class="atbs-block__inner">
                    <div class="posts-list flex-box flex-box-4i flex-space-20 flex-item-special">
                        
                        <div class="list-item sub-img-child">
                            <div class="post post--no-text post--no-text-default">
                                <div class="post__thumb atbs-thumb-object-fit thumb-390">
                                    <a href="">
                                        <img src="https://hoangkimonline.net/uploads/shops/Gao4724.jpg" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-item sub-img-child">
                            <div class="post post--no-text post--no-text-default">
                                <div class="post__thumb atbs-thumb-object-fit thumb-390">
                                    <a href="">
                                        <img src="https://hoangkimonline.net/uploads/shops/Gao4735-11.jpg" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="list-item sub-img-child">
                            <div class="post post--no-text post--no-text-default">
                                <div class="post__thumb atbs-thumb-object-fit thumb-390">
                                    <a href="">
                                        <img src="https://hoangkimonline.net/uploads/shops/nh-1-chai-ruou.jpg" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
      </div>
      </div>
    </section>
  <?php endif; ?>
  <div class="warpper-inner-home">
    <div class="container">
      <?php echo isset($products_wholesale) ? $products_wholesale : ''; ?>

      <!--<?php //$this->load->view('block-bottom-slider'); ?>-->
      <section>
        <!-- <div class="row">
          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
            <div class="catelogy-menu-home">
              <div class="block-title">
                <h3>DANH MỤC SẢN PHẨM</h3>
              </div>
              <div id="cssmenu">
                <ul>
                  <?php //echo multilevelMenu(0, $shops_cat_list, $shops_cat_data, isset($shops_cat_active_page) ? $shops_cat_active_page : ''); ?>
                </ul>
              </div>
            </div>
          </div> -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <div class="box-product-home">
            <div class="box-navigation-tabs">
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" data-toggle="tab" href="#product-home-1">SẢN PHẨM MỚI</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#product-home-2">SẢN PHẨM KHUYẾN MÃI</a>
                  </li> -->
              </ul>
            </div>
            <div class="box-content-product-rows">
              <div class="tab-content">
                <div class="tab-pane fade show active" id="product-home-1">
                  <?php echo isset($products_new) ? $products_new : ''; ?>
                </div>
                <!-- <div class="tab-pane fade" id="product-home-2">
                    <?php //echo isset($products_promotion) ? $products_promotion : ''; ?>
                  </div> -->
              </div>
            </div>
          </div>
        </div>
        <!-- </div> -->
      </section>
      <section>
        <?php echo isset($info_content_main_none['content']) ? $info_content_main_none['content'] : ''; ?>
      </section>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
          <?php //echo isset($products_cat_home) ? $products_cat_home : ''; ?>
          <?php if (isset($advertise_right_top_none) && is_array($advertise_right_top_none) && !empty($advertise_right_top_none)): ?>
            <section>
              <div class="box-adversite-right-top">
                <div class="row">
                  <?php foreach ($advertise_right_top_none as $value):
                    $data_src = get_image(get_module_path('images') . $value['image'], get_module_path('images') . 'no-image.png');
                    ?>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4">
                      <a href="<?php echo ($value['link']); ?>" class="d-block mb-2"><img width="100%"
                          src="<?php echo base_url('timthumb.php?src=' . $data_src . '&w=525&h=350&zc=1&q=100'); ?>" alt=""
                          class="img-fluid"></a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </section>
          <?php endif; ?>
          <?php //echo isset($products_bestseller) ? $products_bestseller : ''; ?>
          <?php if (isset($advertise_bottom_none) && is_array($advertise_bottom_none) && !empty($advertise_bottom_none)): ?>
            <section>
              <div class="adversite-bottom">
                <?php foreach ($advertise_bottom_none as $value): ?>
                  <a href="<?php echo ($value['link']); ?>" class="d-block"><img width="100%"
                      src="<?php echo base_url(get_module_path('images') . ((trim($value['image']) != '') ? $value['image'] : $value['image'])); ?>"
                      alt="" class="img-fluid"></a>
                <?php endforeach; ?>
              </div>
            </section>
          <?php endif; ?>
          <div class="clearfix"></div>
          <?php echo isset($posts_new) ? $posts_new : ''; ?>
        </div>
      </div>
    </div>
  </div>
</article>