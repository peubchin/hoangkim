<article>
  <section class="user-manager-page">
    <div class="bg-brea">
      <div class="container">
        <?php $this->load->view('breadcrumb'); ?>
        <div class="users_cash-drawing">
          <div class="row">
            <?php $this->load->view('block-left-admin'); ?>
            <div class="col-xl-9 col-lg-9 col-sm-9">
              <form method="post" style="border: 1px solid #ccc;padding: 20px;margin-bottom: 20px;">
                <div class="form-group">
                  <label class="control-label">Chọn loại ví bạn muốn chuyển: </label>
                  <select class="form-control border-cart" style="width: 100%; padding: 5px 10px;">
                    <option value="1">Ví hoa hồng - 74,000 đ</option>
                    <option value="2">Ví điểm - 0 đ</option>
                    <option value="4">Ví mua hàng - 24,000 đ</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Tài khoản người nhận</label>
                  <input type="text" class="form-control" name="txtUserName" value="" placeholder="Nhập username người muốn chuyển" required="">
                </div>
                <div class="form-group">
                  <label>Tiền chuyển</label>
                  <input type="number" class="form-control" name="txtAmount" value="" placeholder="Nhập số tiền muốn chuyển" required="">
                </div>
                <div class="form-group">
                  <label>Mật khẩu cấp 2</label>
                  <input type="password" class="form-control" name="txtPass2" value="" placeholder="Cài đặt tại Bảo mật 2 lớp" required="">
                </div>
                <div class="form-group">
                  <label>Ghi chú chuyển tiền</label>
                  <textarea type="text" class="form-control" name="txtNote" value="" placeholder="Nhập ghi chú chuyển tiền nếu có"></textarea>
                </div>
                <button class="btn btn-success btn-block" type="submit">Chuyển tiền</button>
              </form>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr class="footable-header text-center">
                      <th>Thời gian</th>
                      <th>Số tiền</th>
                      <th>Người Chuyển / Nhận	</th>
                      <th>Trạng thái</th>
                      <th>Mã giao dịch</th>
                      <th>Ghi chú</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="text-center">
                      <td>05-04-20</td>
                      <td>355,000 đ</td>
                      <td>xuanmai2</td>
                      <td>Thành công</td>
                      <td>CT99315</td>
                      <td>Ok</td>
                    </tr>
                    <!-- <tr class="footable-empty"><td colspan="5">Không có dữ liệu hiển thị</td></tr></tbody> -->
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </article>
