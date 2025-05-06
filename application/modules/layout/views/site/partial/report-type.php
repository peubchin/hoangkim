<?php if($type_date == 'YEAR'): ?>
    <?php
    $range = range(1970,2021);
    arsort($range);
    $years = array_combine($range, $range);
    ?>
    <div class="form-group search_title">
        Chọn năm
    </div>
    <div class="form-group search_input">
        <select class="form-control input-sm" name="year">
            <?php echo get_option_select($years, isset($get['year']) ? $get['year'] : ''); ?>
        </select>
    </div>
<?php elseif($type_date == 'QUARTER'): ?>
    <?php
    $range = range(1970,2021);
    arsort($range);
    $years = array_combine($range, $range);
    ?>
    <div class="form-group search_title">
        Chọn năm
    </div>
    <div class="form-group search_input">
        <select class="form-control input-sm" name="year">
            <?php echo get_option_select($years, isset($get['year']) ? $get['year'] : ''); ?>
        </select>
    </div>

    <div class="form-group search_title">
        Chọn quý
    </div>
    <div class="form-group search_input">
        <select class="form-control input-sm" name="quarter">
            <?php echo get_option_select($this->config->item('report_type_quarter'), isset($get['quarter']) ? $get['quarter'] : ''); ?>
        </select>
    </div>
<?php elseif($type_date == 'MONTH'): ?>
    <?php
    $range = range(1970,2021);
    arsort($range);
    $years = array_combine($range, $range);
    ?>
    <div class="form-group search_title">
        Chọn năm
    </div>
    <div class="form-group search_input">
        <select class="form-control input-sm" name="year">
            <?php echo get_option_select($years, isset($get['year']) ? $get['year'] : ''); ?>
        </select>
    </div>

    <div class="form-group search_title">
        Chọn tháng
    </div>
    <div class="form-group search_input">
        <select class="form-control input-sm" name="month">
            <?php echo get_option_select($this->config->item('report_type_month'), isset($get['month']) ? $get['month'] : ''); ?>
        </select>
    </div>
<?php elseif($type_date == 'DAY'): ?>
    <div class="form-group search_title">
        Ngày
    </div>
    <div class="form-group search_input">
        <div class="input-group input-append date" id="datePickerFromday">
            <input type="text" class="form-control input-sm" name="fromday" value="<?php echo isset($get['fromday']) ? $get['fromday'] : ''; ?>" readonly />
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
    </div>
    <div class="form-group search_title">
        Đến ngày
    </div>
    <div class="form-group search_input">
        <div class="input-group input-append date" id="datePickerToday">
            <input type="text" class="form-control input-sm" name="today" value="<?php echo isset($get['today']) ? $get['today'] : ''; ?>" readonly />
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
    </div>
<?php endif; ?>