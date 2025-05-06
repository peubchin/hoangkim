<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
    <tbody>
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" width="890">
                    <tbody>
                        <tr>
                            <td valign="top" style="border:1px solid #c7c7c7;border-top:none">
                                <table border="0" cellpadding="0" cellspacing="0" width="890">
                                    <tbody>
                                        <tr>
                                            <td valign="top" width="100%">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top">
                                                                <table border="0" cellpadding="15" cellspacing="0" width="100%">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td valign="top">
                                                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td valign="top" style="line-height:150%;color:#5e5e5e;font-size:14px;font-family:Arial;text-align:left">
                                                                                                <div>
                                                                                                    Chào <?php echo $customer['full_name']; ?>,
                                                                                                    <br>
                                                                                                    <div>Cảm ơn bạn đã đặt hàng của chúng tôi. Dưới đây là chi tiết đặt hàng của bạn:</div>
                                                                                                    <br>
                                                                                                    Ngày: <?php echo date('d/m/Y', $order['created']); ?> lúc <?php echo date('h:i A', $order['created']); ?><br>
                                                                                                    Mã đơn hàng: <strong style="color: #f00;"><?php echo $order['order_code']; ?></strong><br>
                                                                                                    Hình thức thanh toán: <strong><?php echo display_value_array($this->config->item($order['forms_of_payment'], 'forms_of_payment'), 'title'); ?></strong><br>
                                                                                                    Địa chỉ giao hàng: <strong><?php echo $customer['address']; ?></strong><br>
                                                                                                    <table cellspacing="0" cellpadding="0" width="100%">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-right:40px" align="right">STT</td>
                                                                                                                <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-right:40px" align="left">Tên sản phẩm</td>
                                                                                                                <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-left:10px;padding-right:10px" align="right">Giá sản phẩm (vnđ)</td>
                                                                                                                <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-left:10px;padding-right:10px" align="right">Giảm giá (vnđ)</td>
                                                                                                                <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-left:10px;padding-right:10px" align="right">Số lượng</td>
                                                                                                                <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-left:10px;padding-right:10px" align="right">Thành tiền (vnđ)</td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="6" bgcolor="#888888" height="1"></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="6">&nbsp;</td>
                                                                                                            </tr>
                                                                                                            <?php
                                                                                                            $stt = 1;
                                                                                                            foreach ($products as $product) {
                                                                                                                ?>
                                                                                                                <tr>
                                                                                                                    <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-right:40px" align="right"><?php echo $stt; ?></td>
                                                                                                                    <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-right:40px" align="left"><?php echo isset($product['name']) ? $product['name'] : ''; ?></td>
                                                                                                                    <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-left:10px;padding-right:10px" align="right">
                                                                                                                        <strong><?php echo formatRice($product['promotion_price']); ?></strong>
                                                                                                                        <?php if($product['promotion_price'] != $product['price']): ?>
                                                                                                                        <del style="display: block; color: #9c9696;"><?php echo formatRice($product['price']); ?></del>
                                                                                                                        <?php endif; ?>
                                                                                                                    </td>
                                                                                                                    <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-left:10px;padding-right:10px" align="right"><strong><?php echo formatRice($product['percent_discount']); ?></strong></td>
                                                                                                                    <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-left:10px;padding-right:10px" align="right"><?php echo $product['quantity']; ?></td>
                                                                                                                    <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;padding-left:10px;padding-right:10px" align="right"><strong><?php echo formatRice($product['monetized']); ?></strong></td>
                                                                                                                </tr>
                                                                                                                <?php
                                                                                                                $stt++;
                                                                                                            }
                                                                                                            ?>
                                                                                                            <tr>
                                                                                                                <td colspan="6" bgcolor="#888888" height="1"></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;border-bottom:solid 3px;padding-right:40px" align="left" colspan="5">Tổng cộng</td>
                                                                                                                <td style="font-family:Verdana,Tahoma,Sans-serif;font-size:12px;border-bottom:solid 3px;padding-left:10px;padding-right:10px;" align="right"><strong style="color: #f00; font-weight: 700;"><?php echo formatRice($order['order_amount']); ?></strong></td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td colspan="6" bgcolor="#888888" height="1"></td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                    <?php if (isset($order['order_note']) && trim($order['order_note']) != ''): ?>
                                                                                                        <i>Ghi chú:</i> <?php echo $order['order_note']; ?>
                                                                                                    <?php endif; ?>
                                                                                                    <br>
                                                                                                    <br>
                                                                                                    Để biết thêm thông tin, xin vui lòng tham khảo điều khoản của chúng tôi.<br>
                                                                                                    <br>
                                                                                                    Trân trọng<br>
                                                                                                    <a href="<?php echo base_url(); ?>" style="text-decoration:none;color:#5e5e5e!important" target="_blank"><span class="il"><?php echo ucfirst(str_ireplace('www.', '', parse_url(base_url(), PHP_URL_HOST))); ?></span></a><br>
                                                                                                    <br>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>