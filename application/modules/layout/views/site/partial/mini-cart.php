<?php $count = count($this->cart->contents()); ?>
<a href="<?php echo site_url('gio-hang'); ?>" class="minicart-icons">
  <i class="fas fa-shopping-basket fa-icons"></i>
  <span class="number-minicart">[<?php echo $count; ?>]<br></span>
  <div class="clearfix"></div>
</a>
<?php if ($this->cart->contents()): ?>
<div class="nav-cart-dropdown">
  <ul class="list-unstyled container-mini-cart">
    <?php foreach ($this->cart->contents() as $items): ?>
      <li>
        <div class="block-image-cart">
          <a href="<?php echo $items['url']; ?>"><img src="<?php echo get_image(get_module_path('shops') . $items['img'], get_module_path('shops') . 'no-image.png'); ?>" alt="" class="img-fluid"></a>
        </div>
        <div class="block-detail">
          <a data-rowid="<?php echo $items['rowid']; ?>" class="remove-item mini-cart-remove-item" href="javascript:void(0)" title="Xóa"><i class="fa fa-trash" aria-hidden="true"></i></a>
          <h3><a href="<?php echo $items['url']; ?>"><?php echo $items['name']; ?></a></h3>
          <span class="price"><?php echo formatRice($items['price']); ?> ₫</span> <span class="quality">/ SỐ LƯỢNG : <?php echo $items['qty']; ?></span>
          <!-- <input data-rowid="<?php echo $items['rowid']; ?>" class="after-btn-cart form-control mini-cart-qty" type="number" value="<?php echo $items['qty']; ?>" min="1" max="10" /> -->
        </div>
        <div class="clearfix"></div>
      </li>
    <?php endforeach; ?>
    <div class="clearfix"></div>
    <div class="total-price">
		Tổng cộng <span class="price pull-right" id="mini-cart-total"><?php echo formatRice($this->cart->total()); ?> ₫</span>
    </div>
    <div class="clearfix"></div>
    <div class="redirect-checkout">
      <a href="<?php echo site_url('gio-hang'); ?>" class="btn btn-cart">GIỎ HÀNG</a>
      <a href="<?php echo site_url('thanh-toan'); ?>" class="btn btn-checkout btn-dark">THANH TOÁN</a>
    </div>
  </ul>
</div>
<?php endif; ?>
