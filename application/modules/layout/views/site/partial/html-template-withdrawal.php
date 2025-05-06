<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF" align="center">
    <tbody>
        <tr>
            <td>
                <table width="750px" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF" align="center">
                    <tbody>
                        <tr>
                            <td valign="top" style="padding-top:30px;font-family:Helvetica neue,Helvetica,Arial,Verdana,sans-serif;color:#205081;font-size:20px;line-height:32px;text-align:left;font-weight:bold" align="left">Thông báo hệ thống: Yêu cầu rút tiền</td>
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
                            <td valign="top" style="font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Bạn nhận được thư này vì bạn là quản trị viên của hệ thống chúng tôi!<br>
                        </tr>
						<tr>
                            <td valign="top" style="font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Thành viên <strong><?php echo $data['full_name']; ?> (ID: <?php echo $data['user_id']; ?>)</strong> vừa gửi yêu cầu rút tiền vào ngày <b><?php echo date('d/m/Y', $data['created']); ?></b> lúc <b><?php echo date('h:i A', $data['created']); ?></b>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-top:10px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">Dưới đây là thông tin yêu cầu chi tiết:</td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-bottom:20px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">
                                <b>Họ tên</b>: <?php echo $data['full_name']; ?><br>
                                <b>Email</b>: <?php echo $data['email']; ?><br>
                                <b>Số điện thoại</b>: <?php echo $data['phone']; ?><br>
                                <b>Số tiền yêu cầu rút</b>: <?php echo formatRice($data['value_cost']); ?> đ<br>
                                <b>Mã giao dịch</b>: #<?php echo $data['id']; ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-bottom:20px;font-family:Helvetica,Helvetica neue,Arial,Verdana,sans-serif;color:#333333;font-size:14px;line-height:20px;text-align:left;font-weight:none" align="left">
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