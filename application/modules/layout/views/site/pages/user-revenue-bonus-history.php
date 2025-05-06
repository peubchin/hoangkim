<?php $bool = (isset($rows) && is_array($rows) && !empty($rows)) ? TRUE : FALSE; ?>
<article>
    <section class="user-manager-page">
        <div class="bg-brea">
            <div class="container">
                <?php $this->load->view('breadcrumb'); ?>
                <div class="users_withrawal-history">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="account-structure-page_main-content">
                                <div class="account-change-email">
                                    <h2 class="account-structure-page_title" style="padding-bottom: 0;">Lịch sử thưởng doanh số</h2>
                                    <?php if($bool): ?>
                                    <div class="box-devision-col-mobile" style="padding-top: 0;">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Hoa hồng</th>
                                                        <th class="text-right">Giá trị</th>
                                                        <th>Ghi chú</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($rows as $row): ?>
                                                        <tr>
                                                        <?php //echo date('m/Y', strtotime('-1 month', $row['created'])); ?>
                                                        <td>Tháng <?php echo date('m/Y', $row['created']); ?></td>
                                                        <td class="text-right"><?php echo formatRice($row['value']); ?></td>
                                                        <td><?php echo $row['message']; ?></td>
                                                      </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <?php if (isset($pagination) && trim($pagination) != ''): ?>
                                    <div class="box-pagination">
                                        <?php echo $pagination; ?>
                                    </div>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>