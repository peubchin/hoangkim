<style type="text/css">
    .payment {
        color: #f00;
        font-weight: bold;
        display: block;
        margin-top: 10px;
        border: 1px solid #f00;
        padding: 3px;
        text-transform: uppercase;
    }
    .text-red {
        color: #f00 !important;
        font-weight: 700;
    }
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-primary" id="element_print">
            <div class="box-header with-border">
                <h3 class="box-title"><em class="fa fa-table">&nbsp;</em>Xem Đơn Hàng: <?php echo $order['order_code']; ?></h3>
                <div class="pull-right">
                    <a class="btn btn-info" href="<?php echo get_admin_url($module_slug); ?>"><i class="fa fa-table"></i> Danh sách</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td style="border-top: none;">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td width="150px">Họ tên khách hàng:</td>
                                                <td><strong><?php echo isset($customer['full_name']) ? $customer['full_name'] : ''; ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Email khách hàng:</td>
                                                <td><?php echo isset($customer['email']) ? $customer['email'] : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Điện thoại:</td>
                                                <td><?php echo isset($customer['phone']) ? $customer['phone'] : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Địa chỉ:</td>
                                                <td><?php echo isset($customer['address']) ? $customer['address'] : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Ngày đặt hàng:</td>
                                                <td><?php echo date('d/m/Y', $order['order_date']); ?> lúc <?php echo date('h:i A', $order['order_date']); ?></td>
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
                            <?php if($order['order_discount'] > 0): ?>
                            <!-- <tr>
                                <td class="text-right" colspan="4">Chiết khấu</td>
                                <td class="text-right"><strong class="text-red">-<?php echo formatRice($order['order_discount']); ?></strong></td>
                            </tr> -->
                            <?php endif; ?>
                            <tr class="text-red">
                                <td class="text-right" colspan="4">Tổng cộng</td>
                                <td class="text-right"><strong><?php echo formatRice($order['order_monetized'] - $order['order_VAT']); ?></strong></td>
                                <td class="text-right"><strong><?php echo formatRice($order['order_VAT']); ?></strong></td>
                                <td class="text-right"><strong><?php echo formatRice($order['order_monetized']); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php if (trim($order['order_note']) != ''): ?>
                <div class="box-footer clearfix">
                    <i>Ghi chú:</i>
                    <br/>
                    <?php echo nl2br($order['order_note']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="text-center">
        <?php if ($order['transaction_status'] == 0): ?>
            <form class="form-inline noPrint" action="<?php echo get_admin_url('orders/view/' . $order['order_id']); ?>" method="post" id="f-post">
                <input type="hidden" value="<?php echo $order['order_id']; ?>" name="order_id" id="order_id" />
                <!-- <button class="btn btn-success" id="click_pay" type="submit">Xác nhận thanh toán đơn hàng này</button> -->
                <!-- &nbsp;<a class="btn btn-primary" href="<?php echo get_admin_url('orders/content/' . $order['order_id']); ?>">Cập nhật</a> -->
                &nbsp;<button class="btn btn-info" id="click_print" type="button"><span class="glyphicon glyphicon-print"></span>&nbsp;In</button>
            </form>
        <?php elseif ($order['transaction_status'] == -1): ?>
            <button class="btn btn-danger noPrint" type="button">Đã hủy</button>
            &nbsp;<button class="btn btn-info noPrint" id="click_print" type="button"><span class="glyphicon glyphicon-print"></span>&nbsp;In</button>
        <?php else: ?>
            <button class="btn btn-success noPrint" type="button">Đã thanh toán</button>
            &nbsp;<button class="btn btn-info noPrint" id="click_print" type="button"><span class="glyphicon glyphicon-print"></span>&nbsp;In</button>
        <?php endif; ?>
    </div>
</div>