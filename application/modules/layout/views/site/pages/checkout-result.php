<style type="text/css">
    .order-VAT, .order-total, .block-totalcart table .order-VAT th, .block-totalcart table .order-total th{
        color: #f00;
    }
</style>
<article>
  <section>
    <div class="box-warpper">
      <div class="container">
        <?php $this->load->view('breadcrumb'); ?>
        <div class="box-checkout">
          <h2>KẾT QUẢ THANH TOÁN</h2>
          <div class="row">
            <div class="col-xl-7 col-lg-7 col-md-8 col-sm-8">
              <div class="checkout-warpper">
                <div class="report-authe">
                    <div class="icon-order-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="72px" height="72px">
                            <g fill="none" stroke="#8EC343" stroke-width="2">
                                <circle cx="36" cy="36" r="35" style="stroke-dasharray:240px, 240px; stroke-dashoffset: 480px;"></circle>
                                <path d="M17.417,37.778l9.93,9.909l25.444-25.393" style="stroke-dasharray:50px, 50px; stroke-dashoffset: 0px;"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="order-success-text">
                        <h3>Cảm ơn bạn đã đặt hàng. Mã đơn hàng của bạn là <strong><?php echo isset($order['order_code']) ? $order['order_code'] : ''; ?></strong></h3>
                        <div class="block-bank">
                          <?php echo isset($info_result_order_none['content']) ? $info_result_order_none['content'] : ''; ?> <br>
                          Vui Lòng thanh toán tiền vào số tài khoản Công ty: CÔNG TY CỔ PHẦN THƯƠNG MẠI VÀ DỊCH VỤ HOÀNG KIM GROUP<br>
    						Số tài khoản : 5678979668<br>
    						Ngân hàng ACB - Chi nhánh Tp.Hồ Chí Minh khi mua gạo và muối. <br>
    					  Chuyển tiền vào số tài khoản: <br>
    					  Trương Huỳnh Ngọc Anh 9999 540 9999 <br>
    					  Ngân hàng: TPBANK - Chi nhánh HCM, khi mua các sản phẩm khác. <br>
    					  Nội dung thanh toán khi chuyển khoản cho Công ty: ví dụ: HKOL 00001, HK000010 (mã đơn hàng hiển thị khi quý thành viên đặt hàng thành công ở phía trên) <br>
    					  Công ty xác nhận đã nhận tiền và gửi hàng cho quý thành viên, hàng được gửi đến nhà quý thành viên trong vòng 1 đến 10 ngày tùy khu vực.<br>
    					 (trường hợp thành viên chuyển khoản không có nội dung, hoặc nội dung không rõ ràng, công ty sẽ không có thông tin để chuyển hàng, công ty sẽ lưu lại và chờ thành viên liên lạc lại với công ty)<br>
    					  
                        </div>
                        <p>
                            Một email xác nhận đã được gửi tới <strong><?php echo isset($customer['email']) ? $customer['email'] : ''; ?></strong>
                            <br>
                            Xin vui lòng kiểm tra email của bạn!
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="detail-order">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <h3>Thông tin nhận hàng</h3>
                                    <p>Họ tên: <?php echo isset($customer['full_name']) ? $customer['full_name'] : ''; ?></p>
                                    <p>Địa chỉ: <?php echo isset($customer['address']) ? $customer['address'] : ''; ?></p>
                                    <p>Điện thoại: <?php echo isset($customer['phone']) ? $customer['phone'] : ''; ?></p>
                                    <h3>Ngày đặt hàng</h3>
                                    <p><?php echo date('d/m/Y', $order['created']); ?> lúc <?php echo date('h:i A', $order['created']); ?></p>
                                    <h3>Hình thức thanh toán</h3>
                                    <p><strong><?php echo display_value_array($this->config->item($order['forms_of_payment'], 'forms_of_payment'), 'title'); ?></strong></p>
                                    <?php if (isset($order['order_note']) && trim($order['order_note']) != ''): ?>
                                        <h3>Ghi chú</h3>
                                        <p><?php echo nl2br($order['order_note']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <h3>Thông tin thanh toán</h3>
                                    <p>Họ tên: <?php echo isset($customer['full_name']) ? $customer['full_name'] : ''; ?></p>
                                    <p>Địa chỉ: <?php echo isset($customer['address']) ? $customer['address'] : ''; ?></p>
                                    <p>Điện thoại: <?php echo isset($customer['phone']) ? $customer['phone'] : ''; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-4 col-sm-4">
              <div class="block-totalcart">
                <h3 class="title">ĐƠN HÀNG CỦA BẠN</h3>
                <table cellspacing="0">
                  <thead>
                    <tr>
                      <th class="product-name">SẢN PHẨM</th>
                      <th class="product-total">TỔNG CỘNG</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($products as $product): ?>
                      <tr class="cart-item-checkout">
                        <td class="name-product-checkout"><?php echo isset($product['name']) ? $product['name'] : ''; ?><strong>x<?php echo $product['quantity']; ?></strong></td>
                        <td><span><?php echo formatRice($product['monetized'] - $product['VAT_value']); ?>&nbsp;₫</span></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <!--<tr class="cart-subtotal">
                    <th>TỔNG PHỤ</th>
                    <td><strong><span><?php //echo formatRice($order['order_amount']); ?>&nbsp;₫</span></strong></td>
                  </tr>-->
                  <!-- <tr class="shipping">
                  <th>GIAO NHẬN</th>
                  <td>
                  <ul>
                  <li>
                  <input type="radio" name="" value="">
                  <label>Phí giao hàng: <span>20,000&nbsp;₫</span></label>
                </li>
                <li>
                <input type="radio" checked="checked">
                <label>Mua hàng tận nơi (Miễn phí)</label>
              </li>
            </ul>
          </td>
        </tr> -->
        <?php if($order['order_VAT'] > 0): ?>
        <tr class="cart-subtotal order-VAT">
            <th>TIỀN THUẾ GTGT</th>
            <td><strong><span><?php echo formatRice($order['order_VAT']); ?>&nbsp;₫</span></strong> </td>
        </tr>
        <?php endif; ?>
        <tr class="order-total">
            <th>TỔNG CỘNG</th>
            <td><strong><span><?php echo formatRice($order['order_amount']); ?>&nbsp;₫</span></strong> </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
</div>
</div>
</div>
</div>
</section>
</article>
