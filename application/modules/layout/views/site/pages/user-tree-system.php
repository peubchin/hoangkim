<article>
    <section class="user-manager-page">
        <div class="bg-brea">
            <div class="container">
                <?php $this->load->view('breadcrumb'); ?>
                <div class="users_commission">
                    <div class="row">
                      <?php $this->load->view('block-left-admin'); ?>
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            <div class="account-structure-page_main-content">
                                <div class="account-change-email">
                                    <h2 class="account-structure-page_title">Hệ thống</h2>
                                   <p style="color:blue; font-weight:bold;">Hệ thống trực tiếp: đang cập nhật...</p>
                                   <p style="color:green;font-weight:bold;">Hệ thống gián tiếp: đang cập nhật...</p>
                                    <div class="box-devision-col-mobile custom-tabletree">
                                        <?php if(isset($users) && is_array($users) && !empty($users)): ?>
                                        <div class="table-responsive">
                                            <table class="table tt-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width:100%;">Danh sách thành viên thuộc hệ thống của bạn</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php show_treetable_users($users, $user_id); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php else: ?>
                                        <div class="alert alert-warning">
                                            <strong>Thông báo!</strong><br>
                                            <p>
                                                Chưa có hệ thống!
                                            </p>
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
