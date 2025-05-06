<?php
$image = get_image(get_module_path('shops') . $row['homeimgfile'], get_module_path('shops') . 'no-image.png');
$data_price = isset($row['data_price']) ? $row['data_price'] : $row['product_price'];
$data_sales_price = get_promotion_price($row['product_price'], $row['product_sales_price']);
$stock_status = isset($row['stock_status']) ? $row['stock_status'] : '';
$product_discount_percent = isset($row['product_discount_percent']) ? $row['product_discount_percent'] : '';
$is_wholesale = isset($row['is_wholesale']) ? filter_var($row['is_wholesale'], FILTER_VALIDATE_BOOLEAN) : FALSE;
?>
<article>
  <section>
    <div class="box-warpper">
      <div class="container">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 order-first order-xl-9 order-lg-9 order-md-12">
            <?php $this->load->view('breadcrumb'); ?>
            <div class="box-single-product">
              <div class="single-detail-product">
                <div class="row">
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="detail-product--slider-producted">
                      <?php if (isset($row['options']) && is_array($row['options']) && !empty($row['options'])): ?>
                        <div class="slider-producted">
                          <?php foreach ($row['options'] as $value): $image_option = get_image(get_module_path('shops') . $value['image'], get_module_path('shops') . 'no-image.png'); ?>
                            <a class="item" href="<?php echo $image_option; ?>" data-size="1600x1600" data-med="<?php echo $image_option; ?>" data-med-size="1024x1024" data-author="Dược Lê Đình">
                  	          <img src="<?php echo $image_option; ?>" alt="" />
                  	          
                  	        </a>
                          <?php endforeach;?>
                        </div>
                        <?php else: ?>
                        <div class="slider-producted">
                          <a class="item" href="<?php echo $image; ?>" data-size="1600x1600" data-med="<?php echo $image; ?>" data-med-size="1024x1024" data-author="Dược Lê Đình">
                            <img src="<?php echo $image; ?>" alt="" />
                            
                          </a>
                        </div>
                      <?php endif;?>
                      <?php if (isset($row['options']) && is_array($row['options']) && !empty($row['options'])): ?>
                        <div class="slider-thumbnail-navigation">
                          <?php foreach ($row['options'] as $value): $image_option = get_image(get_module_path('shops') . $value['image'], get_module_path('shops') . 'no-image.png'); ?>
                            <div class="item">
                              <img width="180" height="180" class="img-fluid" src="<?php echo $image_option; ?>" alt="image"  draggable="false"/>
                            </div>
                          <?php endforeach;?>
                        </div>
                        <?php else: ?>
                        <div class="slider-thumbnail-navigation">
                          <div class="item">
                            <img width="180" height="180" class="img-fluid" src="<?php echo $image; ?>" alt="image"  draggable="false"/>
                          </div>
                        </div>
                      <?php endif;?>
                    </div>
                  </div>
                  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="detail-product--infomation-producted">
                        <h1><?php echo isset($row['title']) ? $row['title'] : ''; ?></h1>
                        
                      <div class="rating-public-view">
                        <ul>
                          <li><a class="active" title="1"></a></li>
                          <li><a class="active" title="2"></a></li>
                          <li><a class="active" title="3"></a></li>
                          <li><a class="active"title="4"></a></li>
                          <li><a class="active"title="5"></a></li>
                        </ul>
                      </div>
                      <small class="code-product">Mã Hàng <?php echo isset($row['product_code']) ? $row['product_code'] : ''; ?></small>
                      <div class="product-stock">Tình trạng: <?php echo display_value_array($this->config->item('stock_status_display'), $stock_status); ?></div>
                      <div class="product-description">
                        <p><?php echo isset($row['hometext']) ? $row['hometext'] : ''; ?></p>
                        <?php //$this->load->view('addthis'); ?>
                      </div>
                        <?php if($is_wholesale): ?>
                            <h3><span class="badge badge-warning">SẢN PHẨM SỈ</span></h3>
                        <?php endif; ?>
                      <div class="product-price">
                        <?php if ($data_sales_price > 0): ?>
                            <?php if ($data_sales_price == $data_price): ?>
              					<?php echo formatRice($data_price); ?><sup>đ</sup>
                            <?php if ($product_discount_percent > 0): ?>
                                Khuyến mãi: <?php echo formatRice($product_discount_percent); ?>%
                            <?php endif; ?>
      						<?php else: ?>
                            <?php echo formatRice($data_sales_price); ?><sup>đ</sup>
                            <del><?php echo formatRice($data_price); ?><sup>đ</sup></del>
                            <?php if ($product_discount_percent > 0): ?>
                              Khuyến mãi: <?php echo formatRice($product_discount_percent); ?>%
                						<?php endif; ?>
                          <?php endif; ?>
                        <?php else: ?>
                          <?php echo isset($info_noprice_infomation_none['content']) ? $info_noprice_infomation_none['content'] : ''; ?>
              					<?php endif; ?>
                        <div class="clearfix"></div>
                      </div>
                      <form action="#" method="get" id="f-add-to-cart" role="form">
                        <?php if(in_array($stock_status, array('instock'))): ?>
                        <div class="product-quantity">
                          <input type="hidden" name="product_id" id="product_id" value="<?php echo $row['id']; ?>" />
                          <input type="text" class="input-text soluong form-control" title="Qty" value="1" maxlength="12" id="qty" name="qty">
                        </div>
                        <button type="submit" name="add" value="Mua hàng" class="btn muahang btn-cart btn-addcart">Mua Hàng</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary btn-advisory btn-buy-now">Thêm vào giỏ hàng</button>
                        <div class="clearfix"></div>
                      </form>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="detail-product--content-producted">
                      <ul class="nav nav-tabs">
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#infomation">THÔNG TIN SẢN PHẨM</a>
                        </li>
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane fade show active" id="infomation">
                          <?php echo isset($row['bodyhtml']) ? $row['bodyhtml'] : ''; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-form-rating mb-4">
              <div class="card">
                <div class="card-body">
                  <p>Chia sẻ nhận xét của bạn về <strong><?php echo isset($row['title']) ? $row['title'] : ''; ?></strong></p>
                  <form id="f-comment" action="#" method="post">
                    <div class="form-group row">
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <input class="form-control form-control-sm mb-4" type="text" id="f-comment-author" value="" placeholder="Vui lòng nhập họ tên">
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                        <input class="form-control form-control-sm mb-4" type="text" id="f-comment-subject" value="" placeholder="Vui lòng nhập tiêu đề nhận xét">
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <textarea class="form-control form-control-sm" id="f-comment-content" rows="4" cols="40" placeholder="Vui lòng nhập nội dung đánh giá"></textarea>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-6 col-form-label">Đánh giá</label>
                      <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-6">
                        <div class="my-rating-6"></div>
                      </div>
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-12">
                        <button type="submit" class="btn btn-success">Đánh giá</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="box-list-rating mb-4">
                <div class="block-title"><h3>DANH SÁCH NHẬN XÉT</h3></div>
                <div class="list-rating-rows" id="data-comments">
                    <?php echo isset($row['comments']) ? $row['comments'] : ''; ?>
                </div>
            </div>
            <?php if (isset($related_products) && trim($related_products) != ''): ?>
              <div class="box-product-related">
                <div class="block-title">
                    <h3>SẢN PHẨM LIÊN QUAN</h3>
                  </div>
                <div class="row">
                  <?php echo $related_products; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <?php //$this->load->view('block-left'); ?>
        </div>
      </div>
    </div>
	<script>
		$('a').removeAttr('style');
	</script>
  </section>
</article>
