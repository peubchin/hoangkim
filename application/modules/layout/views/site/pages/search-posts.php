<!-- breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <ul>
                <li class="home"><a href="<?php echo base_url(); ?>" title="Go to Home Page">Home</a><span>&mdash;›</span></li>
                <li><strong>Search</strong></li>
            </ul>
        </div>
    </div>
</div>
<!-- end breadcrumbs --> 
<!-- Two columns content -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/blogmate.css" type="text/css">
<section class="main-container col2-left-layout">
    <div class="main container">
        <div class="row">
            <section class="col-main col-sm-9 col-xs-12 col-sm-push-3 wow">
                <div class="category-title">
                    <h1>Kết quả tìm kiếm</h1>
                </div>
                <?php if (trim($rows_partial) == ''): ?>
                    <p>Không tìm thấy!</p>
                <?php else: ?>
                    <div class="toolbar product-filter">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="display sorter">
                                    <div class="view-mode">                                    
                                        <?php if (isset($get['viewcat']) && $get['viewcat'] == 'page-grid'): ?>
                                            <span title="Grid" class="button button-active button-grid">Grid</span>&nbsp;
                                            <a href="#" title="List" class="button button-list page-option-view" id="page-list">List</a>&nbsp; 
                                        <?php else: ?>                                                                                        
                                            <a href="#" title="Grid" class="button button-grid page-option-view" id="page-grid">Grid</a>&nbsp; 
                                            <span title="List" class="button button-active button-list">List</span>&nbsp;
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-right">
                                <label class="control-label">Sắp xếp</label>
                            </div>
                            <div class="col-md-2 text-right">
                                <select id="id-sort" name="sort" class="form-control input-sm">
                                    <?php if (isset($get['sort']) && $get['sort'] == 'default') : ?>
                                        <option selected="selected" value="default">Mặc định</option>
                                    <?php else : ?>
                                        <option value="default">Mặc định</option>
                                    <?php endif; ?>

                                    <?php if (isset($get['sort']) && $get['sort'] == 'NAZ') : ?>
                                        <option selected="selected" value="NAZ">Tên (A - Z)</option>
                                    <?php else : ?>
                                        <option value="NAZ">Tên (A - Z)</option>
                                    <?php endif; ?>

                                    <?php if (isset($get['sort']) && $get['sort'] == 'NZA') : ?>
                                        <option selected="selected" value="NZA">Tên (Z - A)</option>
                                    <?php else : ?>
                                        <option value="NZA">Tên (Z - A)</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-2 text-right">
                                <label class="control-label">Hiển thị</label>
                            </div>
                            <div class="col-md-2 text-right">
                                <select id="id-per-page" name="per_page" class="form-control input-sm">
                                    <?php
                                    $records_in_page = 3;
                                    for ($i = 1; $i <= 4; $i++) {
                                        $per_page = $i * $records_in_page;
                                        ?>
                                        <?php if (isset($get['per_page']) && $get['per_page'] == $per_page) : ?>
                                            <option selected="selected" value="<?php echo $per_page; ?>"><?php echo $per_page; ?></option>
                                        <?php else : ?>
                                            <option value="<?php echo $per_page; ?>"><?php echo $per_page; ?></option>
                                        <?php endif; ?>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="blog-wrapper" id="main">
                        <div id="ajax_table" class="posts-isotope row">
                            <?php echo $rows_partial; ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-6 pull-left">
                                <?php echo $pagination; ?>
                            </div>
                            <div class="col-md-6" style="text-align: right;">
                                Hiển thị từ <?php echo $s_limit; ?> đến <?php echo $f_limit; ?> của <?php echo $total_rows; ?> bài viết (<?php echo $total_page; ?> trang)
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
            <aside class="col-left sidebar col-sm-3 col-xs-12 col-sm-pull-9 wow">                
                <div class="block block-cart">
                    <div class="block-title"><span>My Cart</span></div>
                    <div class="block-content">
                        <?php
                        if ($this->cart->contents()):
                            $i = 0;
                            $count = count($this->cart->contents());
                            ?>
                            <div class="summary">
                                <p class="amount">There are <a href="#"><?php echo $count; ?> items</a> in your cart.</p>
                                <p class="subtotal"> <span class="label">Cart Subtotal:</span> <span class="price">$<?php echo formatRice($this->cart->total()); ?></span> </p>
                            </div>
                            <div class="ajax-checkout">
                                <button type="button" title="Checkout" class="button button-checkout"><span>Checkout</span></button>
                            </div>
                            <p class="block-subtitle">Recently added item(s) </p>
                            <ul>
                                <?php
                                foreach ($this->cart->contents() as $items):
                                    $i++;
                                    if ($i != $count) {
                                        $class_item = 'item';
                                    } else {
                                        $class_item = 'item last';
                                    }
                                    $homeimgfile = $items['img'];
                                    $src_img = base_url('uploads/shops/' . $homeimgfile);
                                    ?>
                                    <li class="<?php echo $class_item; ?>">
                                        <a class="product-image" title="<?php echo $items['name']; ?>" href="<?php echo $items['url']; ?>">
                                            <img width="80" alt="<?php echo $items['name']; ?>" src="<?php echo $src_img; ?>">
                                        </a>
                                        <div class="product-details">
                                            <div class="access">
                                                <a class="btn-remove1" title="Remove This Item" href="#"><span class="icon"></span> Remove</a>
                                            </div>
                                            <p class="product-name"><a href="<?php echo $items['url']; ?>"><?php echo $items['name']; ?></a></p>
                                            <strong><?php echo $items['qty']; ?></strong> x <span class="price">$<?php echo formatRice($items['price']); ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p class="block-subtitle">Cart empty!</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="block block-subscribe">
                    <div class="block-title"><span>Newsletter</span></div>
                    <form action="#" method="post" id="newsletter-validate-detail">
                        <div class="block-content">
                            <div class="form-subscribe-header"> Sign up for our newsletter:</div>
                            <input type="text" name="email" id="newsletter" title="" class="input-text required-entry validate-email" placeholder="Enter your email address">
                            <div class="actions">
                                <button type="submit" title="Submit" class="subscribe"><span>Subscribe</span></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="block block-compare">
                    <?php
                    if ($this->session->userdata('compare')):
                        $session_compare = $this->session->userdata('compare');
                        $count_compare = count($session_compare);
                        $link_compare = 'compare/';
//                        echo "<pre>";
//                        print_r($session_compare);
//                        echo "</pre>";
                        ?>
                        <div class="block-title "><span>Compare Products (<?php echo $count_compare; ?>)</span></div>
                        <div class="block-content">
                            <ol id="compare-items">
                                <?php
                                $i = 1;
                                foreach ($session_compare as $value) {
                                    $link_compare .= $value['alias'] . '-' . $value['id'];
                                    if ($i != $count_compare) {
                                        $class = 'odd';
                                        $link_compare .= '-vs-';
                                    } else {
                                        $class = 'last even';
                                    }
                                    ?>
                                    <li class="item <?php echo $class; ?>">
                                        <a id="compared_<?php echo $value['id']; ?>" href="<?php echo base_url('compare/delete/' . $value['id']); ?>" title="Remove This Item" class="btn-remove1 link-compare-remove"></a>
                                        <a class="product-name" href="<?php echo base_url('compare/delete/' . $value['id']); ?>"><?php echo $value['title']; ?></a>
                                    </li>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </ol>
                            <div class="ajax-checkout-1">
                                <?php if ($count_compare > 1): ?>
                                    <a href="<?php echo base_url($link_compare); ?>" style="position: relative; display: block;"><button class="button button-compare" title="Submit" type="button"><span>Compare</span></button></a>
                                <?php endif; ?>
                                <button class="button button-clear" title="Submit" type="button"><span>Clear</span></button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="block-title "><span>Compare Products</span></div>
                        <div class="block-content">
                            <strong>Empty!</strong>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="block block-tags">
                    <div class="block-title"><span>Popular Tags</span></div>
                    <div class="block-content">
                        <ul class="tags-list">
                            <li><a style="font-size:98.3333333333%;" href="#tagId/23/">Camera</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/109/">Hohoho</a></li>
                            <li><a style="font-size:145%;" href="#tagId/27/">SEXY</a></li>
                            <li><a style="font-size:75%;" href="#tagId/61/">Tag</a></li>
                            <li><a style="font-size:110%;" href="#tagId/29/">Test</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/17/">bones</a></li>
                            <li><a style="font-size:110%;" href="#tagId/12/">cool</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/184/">cool t-shirt</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/173/">crap</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/41/">good</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/16/">green</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/5/">hip</a></li>
                            <li><a style="font-size:75%;" href="#tagId/51/">laptop</a></li>
                            <li><a style="font-size:75%;" href="#tagId/20/">mobile</a></li>
                            <li><a style="font-size:75%;" href="#tagId/70/">nice</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/42/">phone</a></li>
                            <li><a style="font-size:98.3333333333%;" href="#tagId/30/">red</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/28/">tight</a></li>
                            <li><a style="font-size:75%;" href="#tagId/2/">trendy</a></li>
                            <li><a style="font-size:86.6666666667%;" href="#tagId/4/">young</a></li>
                        </ul>
                        <div class="actions"> <a class="view-all" href="#">View All Tags</a> </div>
                    </div>
                </div>
                <div class="block block-banner"><a href="#"><img src="images/block-banner.png" alt="block-banner"></a></div>
            </aside>
        </div>
    </div>
</section>
<!-- End Two columns content -->