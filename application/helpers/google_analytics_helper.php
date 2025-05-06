<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Requirements:
  - You must have a Google Analytics account and have the project registered there
  - Get the UA code from Google Analytics

  Install:

  1- Put the google_analytics_helper.php file in /applications/helpers/ directory
  2- On your template insert this code just before page body colsing tag (</body>):

  <?php if(ENVIRONMENT=='production' or true) echo google_analytics('UA-xxxxxxxx-x', [[USER_ID]]); ?>

  Where:
  UA-xxxxxxxx-x: is the Google Analytics code
  [[USER_ID]]: is the user id on your application. This parameter is optional.
 */
if (!function_exists('google_analytics')) {

    function google_analytics($account, $USER_ID = null) {
        $code = "\n<script type=\"text/javascript\">\n";
        $code.= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){";
        $code.= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),";
        $code.= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)";
        $code.= "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');";

        $code.= "ga('create', '" . $account . "', 'auto');";
        if (isset($USER_ID))
            $code.= "ga('set', '&uid', '" . $USER_ID . "');"; // Set the user ID using signed-in user_id.
        $code.= "ga('send', 'pageview');";


        $code.= "\n</script>";

        return $code;
    }

}

/**
 * Helper to generate a Google Analytics trackingcode
 * @param  string $analytics_id The Google Analytics ID
 * @return string            Tracking Code for Google
 */
function analytics_trackingcode($analytics_id = '') {
    $analytics_id = ($analytics_id) ? $analytics_id : config_item('analytics_id');
    $trackingcode = "<script type=\"text/javascript\">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '" . $analytics_id . "']);
	_gaq.push(['_trackPageview']);
	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>";
    return $trackingcode;
}
