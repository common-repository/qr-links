<?php
/*
Plugin name: QR Links
Description: QR code generator and redirection of mobile devices to their own application sites (Libraries used: <a href="http://phpqrcode.sourceforge.net/index.php" target="_blank">PHP QR Code</a>, <a href="http://mobiledetect.net" target="_blank">Mobile Detect</a>)
Author: Promo-Z
Author URI: https://promo-z.ru/
Text Domain: qr-links
Domain Path: /languages/
Version: 1.0
*/
require_once('admin/admin-page.php');
require_once('lib/qrlib/qrlib.php');
require_once('lib/mobile_detect/Mobile_Detect.php');

function qrlinks_add_result(){
	$link = get_site_url().'?qrlinks=true';
	$uploadDir = wp_get_upload_dir();	
	QRcode::png($link, $uploadDir['basedir'].'/qrcode.png', QR_ECLEVEL_H, 10);
	$url_img = $uploadDir['baseurl'].'/qrcode.png';
	
	echo '<p><b>'.__('URL Image', 'qr-links').':</b> '.$url_img.'</p>';
	echo '<img src="'.$url_img.'" />';
} 
add_action( 'qrlinks_after', 'qrlinks_add_result', 1 );

function qrlinks_redirect(){
	if(isset($_GET['qrlinks'])){
		$qrlinks_options = get_option('qrlinks_options');
		$default_url = (isset($qrlinks_options['url_default']) && $qrlinks_options['url_default']!='') ? $qrlinks_options['url_default'] : get_site_url();
		$iOS_url = (isset($qrlinks_options['url_appstore']) && $qrlinks_options['url_appstore']!='') ? $qrlinks_options['url_appstore'] : $default_url;
		$android_url = (isset($qrlinks_options['url_googleplay']) && $qrlinks_options['url_googleplay']!='') ? $qrlinks_options['url_googleplay'] : $default_url;
		$windowsM_url = (isset($qrlinks_options['url_microsoftstore']) && $qrlinks_options['url_microsoftstore']!='') ? $qrlinks_options['url_microsoftstore'] : $default_url;
		$detect = new Mobile_Detect;
		$url = $default_url;
		if( $detect->isiOS() ){
			$url = $iOS_url;			
		}
		if( $detect->isAndroidOS() ){
			$url = $android_url;     
		}
		if( $detect->isWindowsMobileOS() ){
			$url = $windowsM_url;      
		}		
		header('Location: '.$url);     
  }
}
add_action( 'get_header', 'qrlinks_redirect');

?>