<?php

class Files extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    function resize_basic($source_image = '', $old_path = 'uploads/', $new_path = 'uploads/thumb/', $new_name = '', $options = array()) {
        $new_image = $source_image;
        if (trim($new_name) != '') {
            $new_image = $new_name;
        }
        $thumb_w = isset($options['width']) ? $options['width'] : 80;
        $thumb_h = isset($options['height']) ? $options['height'] : 100;
        $image_url = './' . $old_path . $source_image;
// CREATE THE THUMBNAIL IMAGE RESOURCE AND FILL IN TRANSPARENT
        $thumb = imagecreatetruecolor($thumb_w, $thumb_h);
        imagesavealpha($thumb, TRUE);
        $empty = imagecolorallocatealpha($thumb, 0x00, 0x00, 0x00, 127);
        imagefill($thumb, 0, 0, $empty);

// GET ORIGINAL IMAGE DIMENSIONS
        $array = getimagesize($image_url);
        if ($array) {
            list($image_w, $image_h) = $array;
        } else {
            return false;
            //die("NO IMAGE $image_url");
        }

        // ACQUIRE THE ORIGINAL IMAGE
        $image_ext = trim(strtoupper(end(explode('.', $image_url))));
        switch (strtoupper($image_ext)) {
            case 'JPG' :
            case 'JPEG' :
                $image = imagecreatefromjpeg($image_url);
                break;

            case 'PNG' :
                $image = imagecreatefrompng($image_url);
                break;

            default : return false; //die("UNKNOWN IMAGE TYPE: $image_url");
        }

        // GET THE LESSER OF THE RATIO OF THUMBNAIL H OR W DIMENSIONS
        $ratio_w = ($thumb_w / $image_w);
        $ratio_h = ($thumb_h / $image_h);
        $ratio = ($ratio_w < $ratio_h) ? $ratio_w : $ratio_h;

        // COMPUTE THUMBNAIL IMAGE DIMENSIONS
        $thumb_w_resize = $image_w * $ratio;
        $thumb_h_resize = $image_h * $ratio;

        // COMPUTE THUMBNAIL IMAGE CENTERING OFFSETS
        $thumb_w_offset = ($thumb_w - $thumb_w_resize) / 2.0;
        $thumb_h_offset = ($thumb_h - $thumb_h_resize) / 2.0;

// COPY THE IMAGE TO THE CENTER OF THE THUMBNAIL
        imagecopyresampled
                ($thumb
                , $image
                , $thumb_w_offset
                , $thumb_h_offset
                , 0
                , 0
                , $thumb_w_resize
                , $thumb_h_resize
                , $image_w
                , $image_h
        );

// SHOW THE NEW THUMB IMAGE
        $save_path = './' . $new_path . $new_image;
        ob_start();
        ob_clean();
        header("Content-type: image/png");
        //imagepng($thumb); // show brown
        imagepng($thumb, $save_path); // save folder path
// RELEASE THE MEMORY USED BY THE RESOURCES
        imagedestroy($thumb);
        imagedestroy($image);
        return true;
    }

    function resize_image($method, $image_loc, $new_loc, $width, $height) {
        if (!is_array(@$GLOBALS['errors'])) {
            $GLOBALS['errors'] = array();
        }

        if (!in_array($method, array('force', 'max', 'crop'))) {
            $GLOBALS['errors'][] = 'Invalid method selected.';
        }

        if (!$image_loc) {
            $GLOBALS['errors'][] = 'No source image location specified.';
        } else {
            if ((substr(strtolower($image_loc), 0, 7) == 'http://') || (substr(strtolower($image_loc), 0, 7) == 'https://')) { /* don't check to see if file exists since it's not local */
            } elseif (!file_exists($image_loc)) {
                $GLOBALS['errors'][] = 'Image source file does not exist.';
            }
            $extension = strtolower(substr($image_loc, strrpos($image_loc, '.')));
            if (!in_array($extension, array('.jpg', '.jpeg', '.png', '.gif', '.bmp'))) {
                $GLOBALS['errors'][] = 'Invalid source file extension!';
            }
        }

        if (!$new_loc) {
            $GLOBALS['errors'][] = 'No destination image location specified.';
        } else {
            $new_extension = strtolower(substr($new_loc, strrpos($new_loc, '.')));
            if (!in_array($new_extension, array('.jpg', '.jpeg', '.png', '.gif', '.bmp'))) {
                $GLOBALS['errors'][] = 'Invalid destination file extension!';
            }
        }

        $width = abs(intval($width));
        if (!$width) {
            $GLOBALS['errors'][] = 'No width specified!';
        }

        $height = abs(intval($height));
        if (!$height) {
            $GLOBALS['errors'][] = 'No height specified!';
        }

        if (count($GLOBALS['errors']) > 0) {
            $this->echo_errors();
            return false;
        }

        if (in_array($extension, array('.jpg', '.jpeg'))) {
            $image = @imagecreatefromjpeg($image_loc);
        } elseif ($extension == '.png') {
            $image = @imagecreatefrompng($image_loc);
        } elseif ($extension == '.gif') {
            $image = @imagecreatefromgif($image_loc);
        } elseif ($extension == '.bmp') {
            $image = @imagecreatefromwbmp($image_loc);
        }

        if (!$image) {
            $GLOBALS['errors'][] = 'Image could not be generated!';
        } else {
            $current_width = imagesx($image);
            $current_height = imagesy($image);
            if ((!$current_width) || (!$current_height)) {
                $GLOBALS['errors'][] = 'Generated image has invalid dimensions!';
            }
        }
        if (count($GLOBALS['errors']) > 0) {
            @imagedestroy($image);
            $this->echo_errors();
            return false;
        }

        if ($method == 'force') {
            $new_image = resize_image_force($image, $width, $height);
        } elseif ($method == 'max') {
            $new_image = resize_image_max($image, $width, $height);
        } elseif ($method == 'crop') {
            $new_image = resize_image_crop($image, $width, $height);
        }

        if ((!$new_image) && (count($GLOBALS['errors'] == 0))) {
            $GLOBALS['errors'][] = 'New image could not be generated!';
        }
        if (count($GLOBALS['errors']) > 0) {
            @imagedestroy($image);
            $this->echo_errors();
            return false;
        }

        $save_error = false;
        if (in_array($extension, array('.jpg', '.jpeg'))) {
            imagejpeg($new_image, $new_loc) or ( $save_error = true);
        } elseif ($extension == '.png') {
            imagepng($new_image, $new_loc) or ( $save_error = true);
        } elseif ($extension == '.gif') {
            imagegif($new_image, $new_loc) or ( $save_error = true);
        } elseif ($extension == '.bmp') {
            imagewbmp($new_image, $new_loc) or ( $save_error = true);
        }
        if ($save_error) {
            $GLOBALS['errors'][] = 'New image could not be saved!';
        }
        if (count($GLOBALS['errors']) > 0) {
            @imagedestroy($image);
            @imagedestroy($new_image);
            $this->echo_errors();
            return false;
        }

        imagedestroy($image);
        imagedestroy($new_image);

        return true;
    }

    function echo_errors() {
        if (!is_array(@$GLOBALS['errors'])) {
            $GLOBALS['errors'] = array('Unknown error!');
        }
        foreach ($GLOBALS['errors'] as $error) {
            echo '<p style="color:red;font-weight:bold;">Error: ' . $error . '</p>';
        }
    }

    function upload_ajax() {
//Các Mimes quản lý định dạng file
        $mimes = array(
            'image/jpeg', 'image/png', 'image/gif'
        );
        sleep(2);
        if (isset($_FILES['myfile'])) {
            $fileName = $_FILES['myfile']['name'];
            $fileType = $_FILES['myfile']['type'];
            $fileError = $_FILES['myfile']['error'];
            $fileStatus = array(
                'status' => 0,
                'message' => '',
                'data' => null,
            );
            if ($fileError == 1) { //Lỗi vượt dung lượng
                $fileStatus['message'] = 'Dung lượng quá giới hạn cho phép';
            } elseif (!in_array($fileType, $mimes)) { //Kiểm tra định dạng file
                $fileStatus['message'] = 'Không cho phép định dạng này';
            } else { //Không có lỗi nào
                move_uploaded_file($_FILES['myfile']['tmp_name'], './uploads/' . $fileName);
                $fileStatus['status'] = 1;
                $fileStatus['message'] = "Bạn đã upload $fileName thành công";
                $fileStatus['data'] = array(
                    'src' => base_url('uploads/' . $fileName),
                );
            }
            echo json_encode($fileStatus);
        }
    }

    function crop() {
        $this->load->library('image_lib');

        //set source file path and destination of file path
        $file_name = $this->input->post('file_name');
        $name = $this->__get_file_name($file_name);
        $ext = $this->__get_file_extension($file_name);

        $src_path = './uploads/' . $name . '.' . $ext;
        $des_path = './assets/images/' . $name . '_' . time() . '.' . $ext;

        $x = $this->input->post('x');
        $y = $this->input->post('y');
        $width = $this->input->post('w');
        $height = $this->input->post('h');

        //set image library configuration
        $config['image_library'] = 'gd2';
        $config['source_image'] = $src_path;
        $config['new_image'] = $des_path;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;
        $this->image_lib->initialize($config);
        $this->image_lib->crop();

        $data['src'] = $des_path;
        $this->load->view('files/crop_image', $data);
    }

    public function resize($source_image = '', $old_path = 'uploads/', $new_path = 'uploads/thumb/', $new_name = '', $options = array()) {
        $this->load->library('image_lib');
        $this->image_lib->clear();
        $new_image = $source_image;
        if (trim($new_name) != '') {
            $new_image = $new_name;
        }
        $width = isset($options['width']) ? $options['width'] : 75;
        $height = isset($options['height']) ? $options['height'] : 50;
        $maintain_ratio = isset($options['maintain_ratio']) ? $options['maintain_ratio'] : TRUE;

        $config['image_library'] = 'gd2';
        $config['source_image'] = './' . $old_path . $source_image;
        $config['new_image'] = './' . $new_path . $new_image;
        $config['maintain_ratio'] = $maintain_ratio;
        $config['width'] = $width;
        $config['height'] = $height;

        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }

    public function index($name_input = 'file', $dir = 'uploads/', $options = array()) {
        $data = array();
        $info = NULL;

        if ($this->input->post()) {
            $number_of_files = sizeof($_FILES[$name_input]['tmp_name']);
            $files = $_FILES[$name_input];
            $errors = array();

            for ($i = 0; $i < $number_of_files; $i++) {
                if ($_FILES[$name_input]['error'][$i] != 0)
                    $errors[$i][] = 'Couldn\'t upload file ' . $_FILES[$name_input]['name'][$i];
            }
            if (sizeof($errors) == 0) {
                $this->load->library('upload');
                $config['upload_path'] = FCPATH . $dir;
                if (isset($options['allowed_types'])) {
                    if ($options['allowed_types'] != '') {
                        $config['allowed_types'] = $options['allowed_types'];
                    }
                } else {
                    $config['allowed_types'] = 'gif|jpg|png'; //default
                }
                for ($i = 0; $i < $number_of_files; $i++) {
                    $_FILES['uploadedimage']['name'] = $files['name'][$i];
                    $_FILES['uploadedimage']['type'] = $files['type'][$i];
                    $_FILES['uploadedimage']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['uploadedimage']['error'] = $files['error'][$i];
                    $_FILES['uploadedimage']['size'] = $files['size'][$i];
                    $config['file_name'] = alias(pathinfo($files['name'][$i], PATHINFO_FILENAME));
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('uploadedimage')) {
                        $data['uploads'][$i] = $this->upload->data();
                    } else {
                        $data['upload_errors'][$i] = $this->upload->display_errors();
                    }
                }
                $info = $data;
            } else {
                $info = $errors;
            }
        }
        return $info;
    }

    public function upload_systems($name_input = 'file', $dir = '/', $options = array()) {
        $data = array();
        $info = NULL;

        if ($this->input->post()) {
            $number_of_files = sizeof($_FILES[$name_input]['tmp_name']);
            $files = $_FILES[$name_input];
            $errors = array();

            for ($i = 0; $i < $number_of_files; $i++) {
                if ($_FILES[$name_input]['error'][$i] != 0)
                    $errors[$i][] = 'Couldn\'t upload file ' . $_FILES[$name_input]['name'][$i];
            }
            if (sizeof($errors) == 0) {
                $this->load->library('upload');
                $config['upload_path'] = FCPATH . $dir;
                $config['overwrite'] = TRUE;
                if (isset($options['allowed_types'])) {
                    if ($options['allowed_types'] != '') {
                        $config['allowed_types'] = $options['allowed_types'];
                    }
                } else {
                    $config['allowed_types'] = 'xml|txt|html'; //default
                }
                for ($i = 0; $i < $number_of_files; $i++) {
                    $_FILES['uploadedimage']['name'] = $files['name'][$i];
                    $_FILES['uploadedimage']['type'] = $files['type'][$i];
                    $_FILES['uploadedimage']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['uploadedimage']['error'] = $files['error'][$i];
                    $_FILES['uploadedimage']['size'] = $files['size'][$i];
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('uploadedimage')) {
                        $data['uploads'][$i] = $this->upload->data();
                    } else {
                        $data['upload_errors'][$i] = $this->upload->display_errors();
                    }
                }
                $info = $data;
            } else {
                $info = $errors;
            }
        }
        return $info;
    }

    public function watermark_text($source_image = '', $text = '', $option = array()) {
        $wm_font_size = isset($option['wm_font_size']) ? $option['wm_font_size'] : '30';
        $wm_font_color = isset($option['wm_font_color']) ? $option['wm_font_color'] : 'ffffff';
        $wm_vrt_alignment = isset($option['wm_vrt_alignment']) ? $option['wm_vrt_alignment'] : 'bottom';
        $wm_hor_alignment = isset($option['wm_hor_alignment']) ? $option['wm_hor_alignment'] : 'left';
        $wm_padding = isset($option['wm_padding']) ? $option['wm_padding'] : '0';

        $this->load->library('image_lib');
        $config['source_image'] = $source_image; // './uploads/blog-img3.jpg'; //The image path,which you would like to watermarking
        $config['wm_text'] = $text; // 'arjunphp.com';
        $config['wm_type'] = 'text';
        $config['wm_font_path'] = './system/fonts/texb.ttf';
        $config['wm_font_size'] = $wm_font_size;
        $config['wm_font_color'] = $wm_font_color;
        $config['wm_vrt_alignment'] = $wm_vrt_alignment;
        $config['wm_hor_alignment'] = $wm_hor_alignment;
        $config['wm_padding'] = $wm_padding;
        $this->image_lib->initialize($config);

        if (!$this->image_lib->watermark()) {
            echo $this->image_lib->display_errors();
        }
    }

    public function watermark_overlay($source_image = '', $overlay_image = '', $option = array()) {
        $wm_vrt_alignment = isset($option['wm_vrt_alignment']) ? $option['wm_vrt_alignment'] : 'bottom';
        $wm_hor_alignment = isset($option['wm_hor_alignment']) ? $option['wm_hor_alignment'] : 'left';
        $wm_opacity = isset($option['wm_opacity']) ? $option['wm_opacity'] : 50;

        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source_image; // './uploads/blog-img1.jpg';
        $config['wm_type'] = 'overlay';
        $config['wm_overlay_path'] = $overlay_image; // './uploads/watermark.png'; //the overlay image
        $config['wm_opacity'] = $wm_opacity;
        $config['wm_vrt_alignment'] = $wm_vrt_alignment; // 'middle';
        $config['wm_hor_alignment'] = $wm_hor_alignment; // 'right';

        $this->image_lib->initialize($config);

        if (!$this->image_lib->watermark()) {
            echo $this->image_lib->display_errors();
        }
    }

    function copy_file($from_file, $to_file, $delete = false) {
        $file = FCPATH . $from_file;
        $newfile = FCPATH . $to_file;
        copy($file, $newfile);
        if ($delete) {
            @unlink($file);
        }
    }

    function __get_file_names($path) {
        $this->load->helper('file');
        //$files = get_file_info(realpath(APPPATH. '../uploads/shops/home/abc.jpg'));
        $files = get_file_info(realpath(APPPATH . $path));
        return ((isset($files['name']) && $files['name'] != FALSE) ? $files['name'] : '');
    }

    function __get_file_name($path) {
        $ext = explode(".", $path);
        return $ext[0];
    }

    function __get_file_extension($path) {
        $ext = explode(".", $path);
        return $ext[count($ext) - 1];
    }

}
