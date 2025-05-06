<style type="text/css">
    .input-group-addon {
        padding: .375rem .75rem;
        margin-bottom: 0;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        text-align: center;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-color: #d2d6de;
        background-color: #fff;
        border-top-right-radius: 3px;
        border-bottom-right-radius: 3px;
    }
    .pull-right{
        float: right;
    }
    .payment {
        color: #f00;
        font-weight: 700;
        display: block;
        margin-top: 10px;
        border: 1px solid #f00;
        padding: 3px;
        text-transform: uppercase;
    }
    .text-red, .order-VAT, .order-total, .block-totalcart table .order-VAT th, .block-totalcart table .order-total th{
        color: #f00;
    }
</style>
<article>
    <section class="user-manager-page">
        <div class="bg-brea">
            <div class="container">
                <?php $this->load->view('breadcrumb'); ?>
                <div class="users-commission-buy-history">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="account-structure-page_main-content">
                                <div class="account-change-email">
                                    <h2 class="account-structure-page_title" style="font-weight: 700;">Xem đơn hàng <strong class="text-info"><?php echo $order['order_code']; ?></strong> <a class="pull-right btn btn-primary btn-sm" href="<?php echo site_url('quan-ly-don-hang'); ?>"><i class="fa fa-table" aria-hidden="true"></i> Đơn mua</a></h2>
                                    <div class="box-devision-col-mobile">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td style="border-top: none;">
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td width="150px">Họ tên:</td>
                                                                        <td><strong><?php echo $customer['full_name']; ?></strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Email:</td>
                                                                        <td><?php echo $customer['email']; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Điện thoại:</td>
                                                                        <td><?php echo $customer['phone']; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Địa chỉ:</td>
                                                                        <td><?php echo $customer['address']; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Ngày đặt hàng:</td>
                                                                        <td><?php echo date('d/m/Y', $order['created']); ?> lúc <?php echo date('h:i A', $order['created']); ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td style="border-top: none;" width="100px" valign="top" class="text-center">
                                                            <div class="order_code">
                                                                Mã đơn hàng
                                                                <br>
                                                                <span class="text_date"><strong><?php echo $order['order_code']; ?></strong></span>
                                                                <br>
                                                                <span class="payment">
                                                                    <?php echo display_value_array($this->config->item('orders_transaction_status'), $order['transaction_status']); ?>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <td style="width: 30px;">STT</td>
                                                        <td>Tên sản phẩm</td>
                                                        <td class="text-right">Số lượng</td>
                                                        <td class="text-right">Đơn giá (chưa VAT)</td>
                                                        <td class="text-right">Thành tiền (vnđ)</td>
                                                        <td class="text-right">Thuế GTGT (%)</td>
                                                        <td class="text-right">Tổng cộng (vnđ)</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $stt = 1;
                                                    foreach ($products as $product) {
                                                        ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $stt; ?></td>
                                                            <td><?php echo isset($product['name']) ? $product['name'] : ''; ?></td>
                                                            <td class="text-right"><?php echo $product['quantity']; ?></td>
                                                            <td class="text-right">
                                                                <strong><?php echo formatRice(get_price_before_tax($product['promotion_price'], $product['VAT'])); ?></strong>
                                                                <?php if($product['promotion_price'] != $product['price']): ?>
                                                                <strong style="text-decoration: line-through;"><?php echo formatRice(get_price_before_tax($product['price'], $product['VAT'])); ?></strong>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-right"><?php echo formatRice($product['monetized'] - $product['VAT_value']); ?></td>
                                                            <td class="text-right"><?php echo formatRice($product['VAT_value']); ?></td>
                                                            <td class="text-right"><strong><?php echo formatRice($product['monetized']); ?></strong></td>
                                                        </tr>
                                                        <?php
                                                        $stt++;
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="text-red">
                                                        <td class="text-right" colspan="4"><strong>Tổng cộng</strong></td>
                                                        <td class="text-right"><strong><?php echo formatRice($order['order_monetized'] - $order['order_VAT']); ?></strong></td>
                                                        <td class="text-right"><strong><?php echo formatRice($order['order_VAT']); ?></strong></td>
                                                        <td class="text-right"><strong><?php echo formatRice($order['order_monetized']); ?></strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <?php if (trim($order['order_note']) != ''): ?>
                                            <div class="box-footer clearfix" style="padding-left: 15px; padding-right: 15px;">
                                                <i>Ghi chú:</i>
                                                <br/>
                                                <?php echo nl2br($order['order_note']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>