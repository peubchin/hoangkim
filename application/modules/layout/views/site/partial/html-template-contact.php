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
                                                                                                    <b>Thông báo liên hệ từ khách hàng đến <?php echo $data['site_name']; ?></b>
                                                                                                    <br>
                                                                                                    <div>Dưới đây là chi tiết thông tin liên hệ được gửi vào ngày <b><?php echo date('d/m/Y', $data['add_time']); ?></b> lúc <b><?php echo date('h:i A', $data['add_time']); ?></b></div>
                                                                                                    <br>
                                                                                                    <div style="text-align:left">
																										<b>Nội dung liên hệ</b><br>
																										<div style="color: #0c3279;"><?php echo $data['message']; ?></div>
																									</div>
																									<br/>
																									<div style="text-align:left">
																										<b>Thông tin người liên hệ</b><br>
																										<b>Họ tên</b>: <?php echo $data['full_name']; ?><br>
																										<b>Email</b>: <?php echo $data['email']; ?><br>
																										<b>Số điện thoại</b>: <?php echo $data['phone']; ?><br>
																									</div>																									
                                                                                                    <br>                                                                                                    
                                                                                                    <br>
                                                                                                    Trân trọng!
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