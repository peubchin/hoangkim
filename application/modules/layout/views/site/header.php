<header>
    <div class="box-header">
        <div class="header--top">
            <div class="raw">
                <div class="header--top_logo_menu">
                    <div class="header-middle--rows">
                        <div class="header-middle--companyname">
                            <h2><a href="<?php echo site_url(); ?>"><?php echo isset($info_companyname_none['content']) ? $info_companyname_none['content'] : ''; ?></h2></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!-- <div class="header--top_search_items">
            <div class="title-info-top-search">
              <span style="
                font-weight: 700;
                font-size: 1.5rem;
                color: #306DAD;
                text-shadow: -0.0714285714285714rem 0.1428571428571429rem #fff, -0.1428571428571429rem 0.5714285714285714rem 0.2857142857142857rem #c5c5c5;" class="title-info-top-search-name">CÔNG TY CỔ PHẦN THƯƠNG MẠI VÀ DỊCH VỤ HOÀNG KIM GROUP</span>
            </div>
          <div class="header-middle--searchbox">
            <form action="<?php echo site_url('search'); ?>" method="get" autocomplete="off">
              <div class="input-group">
                <div class="input-group-append search-panel">
                  <div class="dropdown">
                    <button type="button" class="btn btn-select dropdown-toggle size150" data-toggle="dropdown">
                      <span id="search_concept">All</span> <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu" role="menu" id="search-dropdown-menu">
                      <?php echo isset($html_category_search) ? $html_category_search : ''; ?>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#all">Tất cả</a>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="search_param" value="<?php echo isset($get['search_param']) ? $get['search_param'] : 'all'; ?>" id="search_param">
                <input type="text" class="form-control" name="q" value="<?php echo isset($q) ? $q : ''; ?>" placeholder="Tìm kiếm ...">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary btn-search" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </div>
            </form>
          </div>
        </div> -->
                <div class="header--bottom">
                    <div class="container">
                        <div class="header-bottom--menu-main">
                            <div class="wsmenucontainer clearfix">
                                <div id="overlapblackbg" class="overlapblackbg"></div>
                                <div class="wsmobileheader clearfix">
                                    <a id="wsnavtoggle" class="animated-arrow"><span></span></a>
                                    <a href="<?php echo site_url(); ?>" class="smallogo">
                                        <div class="box-mobile-logo">
                                            <div class="mobile--logo">
                                                <img src="<?php echo base_url(get_module_path('logo') . $site_logo); ?>" alt="" class="img-fluid d-block mx-auto">
                                            </div>
<!--                                            <div class="mobile--companyname">-->
<!--                                                <h2>--><?php ////echo isset($info_companyname_none['content']) ? $info_companyname_none['content'] : ''; ?><!--</h2>-->
<!--                                            </div>-->
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                    <a class="icon-user2" href="<?php echo site_url('dang-nhap'); ?>"><i class="fas fa-user"></i></a>
                                    <a href="<?php echo site_url('gio-hang'); ?>" class="minicart-icons-mobile">
                                        <i class="fas fa-shopping-basket fa-icons"></i>
                                        <span class="number-minicart">[<?php echo count($this->cart->contents()); ?>]<br></span>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                                <div class="header">

                                    <div class="wsmain">
                                        <nav id="cssmenu1" class="wsmenu clearfix">
                                            <ul class="mobile-sub wsmenu-list">
                                                <!-- <li class="first-menu"><a id="first-menu" href="#">DANH MỤC SẢN PHẨM</a>
                      <ul class="wsmenu-submenu<?php echo is_home() ? ' ' : ''; ?>">
                        <?php echo isset($html_category_product) ? $html_category_product : ''; ?>
                      </ul>
                    </li> -->
                                                <?php echo isset($html_menu_main) ? $html_menu_main : ''; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header--top_user_items">
                    <div class="header--middle">
                        <div class="navigation-bar__section-search">
                            <button class="navigation-bar-btn navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbarResponsive">
                                <i class="fa fa-search"></i> <span class="sub-none">Tìm kiếm</span>
                            </button>
                            <div class="sub-search collapse navbar-collapse navbar-inner" id="navbarResponsive">
                                <ul class="sub-box-input">
                                    <form action="<?php echo site_url('search'); ?>" method="get" autocomplete="off">
                                        <div class="input-group">
                                            <div class="input-group-append search-panel">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-select dropdown-toggle size150" data-toggle="dropdown">
                                                        <span id="search_concept">All</span> <span class="caret"></span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu" id="search-dropdown-menu">
                                                        <?php echo isset($html_category_search) ? $html_category_search : ''; ?>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#all">Tất cả</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="search_param" value="<?php echo isset($get['search_param']) ? $get['search_param'] : 'all'; ?>" id="search_param">
                                            <input type="text" class="form-control" name="q" value="<?php echo isset($q) ? $q : ''; ?>" placeholder="Tìm kiếm ...">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary btn-search" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </ul>
                            </div>
                        </div>
                        <div class="header-top--account">
                            <ul>
                                <?php if ($logged_in): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown"><img class="align-self-start mr-2" width="18px" height="18px" src="<?php echo get_image(get_module_path('users') . $photo, get_module_path('users') . 'no-image.png'); ?>"> CHÀO <?php echo convert_to_uppercase($full_name); ?></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?php echo site_url('trang-ca-nhan'); ?>">TRANG CÁ NHÂN</a>
                                            <a class="dropdown-item" href="<?php echo site_url('doi-mat-khau'); ?>">ĐỔI MẬT KHẨU</a>
                                            <a class="dropdown-item" href="<?php echo site_url('vi-ca-nhan'); ?>">VÍ CÁ NHÂN</a>
                                            <a class="dropdown-item" href="<?php echo site_url('he-thong'); ?>">HỆ THỐNG</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="<?php echo site_url('dang-xuat'); ?>" onclick="if(!confirm('Bạn có muốn đăng xuất?')){return false;}">ĐĂNG XUẤT</a>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <a class="icon-user" href="<?php echo site_url('dang-nhap'); ?>"><i class="fas fa-user"></i></a>
                                    <a class="icon-write" href="<?php echo site_url('dang-ky'); ?>"><i class="fas fa-pen-square"></i></a>
                                <?php endif;?>
                            </ul>
                        </div>
                        <div class="header-middle--searchbox">
                            <div class="header-middle--minicart mini-cart">
                                <?php $this->load->view('partial/mini-cart');?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="header--middle-body" style="margin:10px">
          <div class="container">
            <div class="row">

            </div>
          </div>
        </div> -->

    </div>
</header>

