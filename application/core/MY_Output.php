<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of My_Output
 *
 * @author Truong Chuong Duong
 * @email: truong@chuongduong.net
 */
class MY_Output extends CI_Output {

    function __construct() {
        parent::__construct();
    }

    public function cache($n) {
        if (!empty($n))
            $this->cache_expiration = 1 / 60;
        else
            $this->cache_expiration = 0;
    }

    public function clearCache() {
        global $CFG, $URI;
        $cache_path = ($CFG->item('cache_path') == '') ? APPPATH . 'cache/' : $CFG->item('cache_path');
        $checkfile = $cache_path . 'CACHE_LAST_UPDATE_DATA.check';
        @file_put_contents($checkfile, $checkTime);
    }

    /**
     * Update/serve a cached file
     *
     * @access    public
     * @param     object    config class
     * @param     object    uri class
     * @return    void
     */
    function _display_cache(&$CFG, &$URI) {
        $cache_path = ($CFG->item('cache_path') == '') ? APPPATH . 'cache/' : $CFG->item('cache_path');

        // Build the file path.  The file name is an MD5 hash of the full URI
        $uri = $CFG->item('base_url') .
                $CFG->item('index_page') .
                $URI->uri_string;

        $filepath = $cache_path . md5($uri);

        if (!@file_exists($filepath)) {
            return FALSE;
        }

        if (!$fp = @fopen($filepath, FOPEN_READ)) {
            return FALSE;
        }

        flock($fp, LOCK_SH);

        $cache = '';
        if (filesize($filepath) > 0) {
            $cache = fread($fp, filesize($filepath));
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        // Strip out the embedded timestamp
        if (!preg_match("/(\d+TS--->)/", $cache, $match)) {
            return FALSE;
        }

        //Đọc thời điểm dữ liệu bị thay đổi
        $checkfile = $cache_path . 'CACHE_LAST_UPDATE_DATA.check';
        $checkTime = @file_get_contents($checkfile);

        //Nếu không có => giả định thời điểm thay đổi là ngay bây giờ
        if (!$checkTime) {
            $checkTime = time();
            //Lưu lại thời điểm dữ liệu có thay đổi
            @file_put_contents($checkfile, $checkTime);
        }
        $checkTime = intval($checkTime);

        //Lấy thời điểm file cache được tạo ra
        $cacheCreatedTime = intval(trim(str_replace('TS--->', '', $match['1'])));

        // Has the file expired? If so we'll delete it.
        if ($cacheCreatedTime <= $checkTime) {//Nếu thời điểm tạo file cache trước thời điểm dữ liệu có thay đổi => cache timeout
            if (is_really_writable($cache_path)) {
                @unlink($filepath);
                log_message('debug', "Cache file has expired. File deleted");
                return FALSE;
            }
        }

        // Display the cache
        $this->_display(str_replace($match['0'], '', $cache));
        log_message('debug', "Cache file is current. Sending it to browser.");
        return TRUE;
    }

}