<?php
$temp = explode('/', $src);
unset($temp[0]);
$src = implode('/', $temp);
?>
<img src="<?php echo base_url($src); ?>" />