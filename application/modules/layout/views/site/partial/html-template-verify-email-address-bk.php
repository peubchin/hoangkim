<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF" align="center">

    <tbody>

        <tr>

            <td>

                <table width="750px" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center">

                    <tbody>

                        <tr>

                            <td valign="top" style="padding-top:30px;font-family:Helvetica neue,Helvetica,Arial,Verdana,sans-serif;color:#205081;font-size:20px;line-height:32px;text-align:left;font-weight:bold" align="left">Xác minh địa chỉ email</td>

                        </tr>

						<tr>

                            <td style="color:#cccccc;padding-top:10px" valign="top" width="100%">

                                <hr color="#CCCCCC" size="1">

                            </td>

                        </tr>

                        <tr>

                            <td valign="top" style="padding-top:20px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left"> Chào <?php echo isset($data['full_name']) ? $data['full_name'] : ''; ?>,</td>

                        </tr>

						<tr>

                            <td valign="top" style="padding-top:10px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Bạn nhận được thư này vì bạn đã đăng ký thành viên của chúng tôi!<br>

                        </tr>

                        <tr>

                            <td valign="top" style="padding-top:10px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Để kiểm tra địa chỉ email của bạn vui lòng nhấp vào nút bên dưới:</td>

                        </tr>

                        <tr>

                            <td valign="top" align="left">

                                <table border="0" cellspacing="0" cellpadding="0" align="left">

                                    <tbody>

                                        <tr>

                                            <td align="center" style="padding-bottom:20px;padding-top:20px"> <a href="<?php echo site_url('xac-nhan-thanh-vien') . "?u=" . $data['username'] . "&code=" . $data['activation_key']; ?>">Xác minh ngay</a></td>

                                        </tr>

                                    </tbody>

                                </table>

                            </td>

                        </tr>

                        <tr>

                            <td valign="top" style="padding-bottom:20px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">

                                Trân trọng,<br>

                                Admin

                            </td>

                        </tr>

                    </tbody>

                </table>

            </td>

        </tr>

    </tbody>

</table>