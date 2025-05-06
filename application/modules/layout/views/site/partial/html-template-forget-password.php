<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF" align="center">
    <tbody>
        <tr>
            <td>
                <table width="750px" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center">
                    <tbody>
                        <tr>
                            <td valign="top" style="padding-top:30px;font-family:Helvetica neue,Helvetica,Arial,Verdana,sans-serif;color:#205081;font-size:20px;line-height:32px;text-align:left;font-weight:bold" align="left">Đừng lo lắng, khi quên mật khẩu</td>
                        </tr>
                        <tr>
                            <td style="color:#cccccc;padding-top:10px" valign="top" width="100%">
                                <hr color="#CCCCCC" size="1">
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-top:20px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left"> Chào
                                <?php echo isset($data['full_name']) ? $data['full_name'] : ''; ?>,</td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-top:10px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Gần đây bạn đã hỏi để thiết lập lại <span class="il">mật khẩu</span> cho tài khoản
                                <br>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-top:10px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Để cập nhật mật khẩu của bạn, nhấp vào nút bên dưới:</td>
                        </tr>
                        <tr>
                            <td valign="top" align="left">
                                <table border="0" cellspacing="0" cellpadding="0" align="left">
                                    <tbody>
                                        <tr>
                                            <td align="center" style="padding-bottom:20px;padding-top:20px"> <a href="<?php echo site_url('reset-mat-khau') . "?u=" . $data['username'] . "&code=" . $data['activation_key']; ?>">Thiết lập lại mật khẩu của tôi</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-bottom:20px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">
                                Trân trọng,
                                <br> Admin
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>