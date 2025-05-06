<div class="content-wrapper">
    <!-- Start Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php
            if (strlen($breadcrumbs_module_name) > 0) {
                echo $breadcrumbs_module_name;
            }
            if (strlen($breadcrumbs_module_func) > 0) {
                echo '<small>' . $breadcrumbs_module_func . '</small>';
            }
            ?>
        </h1>
        <ol class="breadcrumb">
            <?php
            $end_breadcrumbs = count($breadcrumbs_admin);
            $start_breadcrumbs = 1;
            foreach ($breadcrumbs_admin as $value) {
                if ($start_breadcrumbs != $end_breadcrumbs) {
                    echo "<li><a href=\"" . get_admin_url($value['url']) . "\">" . $value['name'] . "</a></li>";
                } else {
                    echo "<li class=\"active\">" . $value['name'] . "</li>";
                }
                $start_breadcrumbs++;
            }
            ?>            
        </ol>
    </section>
    <!-- End Content Header (Page header) -->

    <!-- Start Main content -->
    <section class="content">
        <div class="row">
            <div id="notify" class="col-lg-12">
                <?php $this->load->view('view_notify'); ?>
            </div>
        </div>
        <?php $this->load->view($main_content); ?>
    </section>
    <!-- End Main content -->
</div>