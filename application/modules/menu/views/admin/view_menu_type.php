<?php if ($type == 'categories'): ?>
    <label for="values" class="control-label">Chọn loại sản phẩm</label>
    <select class="form-control" name="values" id="values">
        <?php $catid = isset($values_selected) ? $values_selected : 0; ?>
        <?php echo multilevelSelectbox(0, $shops_cat_list, $shops_cat_input, 0, $catid); ?>
    </select>
<?php elseif ($type == 'product'): ?>
    <label for="values" class="control-label">Chọn một sản phẩm</label>
    <select class="form-control" name="values" id="values">
        <?php foreach ($data_rows_shops as $value) : ?>        
            <option value="<?php echo $value['id']; ?>"<?php if (isset($values_selected) && $values_selected == $value['id']) echo ' selected="selected"'; ?>><?php echo $value['title']; ?></option>        
        <?php endforeach; ?>
    </select>    
<?php elseif ($type == 'pages'): ?>
    <label for="values" class="control-label">Chọn một trang tĩnh</label>
    <select class="form-control" name="values" id="values">
        <?php foreach ($data_rows_pages as $value) : ?>        
            <option value="<?php echo $value['id']; ?>"<?php if (isset($values_selected) && $values_selected == $value['id']) echo ' selected="selected"'; ?>><?php echo $value['title']; ?></option>        
        <?php endforeach; ?>
    </select>

<?php elseif ($type == 'post_categories'): ?>
    <label for="values" class="control-label">Chọn một danh mục bài viết</label>
    <select class="form-control" name="values" id="values">
        <?php foreach ($data_cat_news as $value) : ?>        
            <option value="<?php echo $value['id']; ?>"<?php if (isset($values_selected) && $values_selected == $value['id']) echo ' selected="selected"'; ?>><?php echo $value['name']; ?></option>        
        <?php endforeach; ?>
    </select>

<?php elseif ($type == 'post'): ?>
    <label for="values" class="control-label">Chọn một bài viết</label>
    <select class="form-control" name="values" id="values">
        <?php foreach ($data_rows_news as $value) : ?>        
            <option value="<?php echo $value['id']; ?>"<?php if (isset($values_selected) && $values_selected == $value['id']) echo ' selected="selected"'; ?>><?php echo $value['title']; ?></option>        
        <?php endforeach; ?>
    </select>

<?php elseif ($type == 'link'): ?>
    <label for="values" class="control-label">Nhập liên kết</label>
    <input class="form-control" name="values" id="idvalues" type="text" value="<?php if (isset($values_selected)) echo $values_selected; ?>">
<?php elseif ($type == 'posts'): ?>
<?php endif; ?>