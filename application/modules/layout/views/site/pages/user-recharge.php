<article>
  <section class="user-manager-page">
    <div class="bg-brea">
      <div class="container">
        <?php $this->load->view('breadcrumb'); ?>
        <div class="users_cash-drawing">
          <div class="row">
            <?php $this->load->view('block-left-admin'); ?>
            <div class="col-xl-9 col-lg-9 col-sm-9">
              <form class="form-horizontal" id="formdeposit" style="border: 1px solid #ccc;padding: 20px;margin-bottom: 20px;" novalidate="novalidate">
                <div class="box-body box-profile">
                  <div class="deposit-with-cash">
                    <div class="form-group">
                      <div class="row">
                        <label class="col-xl-3 control-label">Số tiền cần nạp:</label>
                        <div class="col-xl-8">
                          <input name="txtAmount" class="form-control" type="number" min="0" placeholder="Tối thiểu 100,000">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <label class="col-xl-3 control-label">Chuyển khoản qua ngân hàng:</label>
                        <div class="col-xl-8">
                          <select name="txtBankName" class="form-control number" style="text-align: right">
                            <option value="VIETCOMBANK">VIETCOMBANK</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="offset-lg-3 col-lg-8">
                        <button type="submit" class="btn btn-info">Khởi tạo giao dịch</button>
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </form>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr class="footable-header text-center">
                      <th scope="col">Thời gian</th>
                      <th scope="col">Số tiền</th>
                      <th scope="col">Trạng thái</th>
                      <th scope="col">Mã giao dịch</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="text-center">
                      <td>05-04-20</td>
                      <td>100.000 VNĐ</td>
                      <td>Thành công</td>
                      <td>NT99315</td>
                    </tr>
                    <!-- <tr class="footable-empty">
                    <td colspan="5">Không có dữ liệu hiển thị</td>
                  </tr> -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</article>
