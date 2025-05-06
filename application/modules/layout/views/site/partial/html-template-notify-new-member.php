<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF" align="center">
    <tbody>
        <tr>
            <td>
                <table width="750px" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center">
                    <tbody>
                        <tr>
                            <td valign="top" style="padding-top:30px;font-family:Helvetica neue,Helvetica,Arial,Verdana,sans-serif;color:#205081;font-size:20px;line-height:32px;text-align:left;font-weight:bold" align="left">Thông báo hệ thống: Đăng ký thành viên mới</td>
                        </tr>

						<tr>
                            <td style="color:#cccccc;padding-top:10px" valign="top" width="100%">
                                <hr color="#CCCCCC" size="1">
                            </td>
                        </tr>

                        <tr>
                            <td valign="top" style="padding-top:20px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Chào admin,</td>
                        </tr>

						<tr>
                            <td valign="top" style="padding-top:10px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Bạn nhận được thư này vì bạn là quản trị viên của hệ thống chúng tôi!<br>
                        </tr>

                        <tr>
                            <td valign="top" style="padding-top:10px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Nội dung thông báo: Thành viên <strong><?php echo isset($data['full_name']) ? $data['full_name'] : ''; ?></strong> vừa đăng ký thông qua email <?php echo isset($data['email']) ? $data['email'] : ''; ?> với tên đăng nhập là <strong><?php echo isset($data['username']) ? $data['username'] : ''; ?></strong> lúc <strong><i><?php echo isset($data['regdate']) ? date('h:i A d/m/Y', $data['regdate']) : ''; ?></i></strong></td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-bottom:20px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">
                                <br>
								Trân trọng,<br>
                                Hệ thống
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>