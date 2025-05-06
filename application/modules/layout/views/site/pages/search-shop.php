<article>
    <section>
        <div class="box-warpper">
            <div class="container">
                <div class="row">
                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 order-first order-xl-9 order-lg-9 order-md-12">
                        <?php $this->load->view('breadcrumb'); ?>
                        <div class="box-product-rows">
                            <h3>Kết quả tìm kiếm</h3>
                            <div class="row">
                                <?php echo isset($rows) && (trim($rows) != '') ? $rows : '<div class="col-md-12">Không tìm thấy kết quả nào!</div>'; ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php if (isset($pagination) && $pagination != ''): ?>
                        <div class="box-pagination">
                            <nav>
                                <?php echo $pagination; ?>
                            </nav>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php $this->load->view('block-left'); ?>
                </div>
                <?php echo isset($products_bestseller) ? $products_bestseller : ''; ?>
            </div>
        </div>
    </section>
</article>