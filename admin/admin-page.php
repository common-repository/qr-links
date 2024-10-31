<?php 
$qrlinks = 'qrlinks.php';  

function qrlinks_options() {
	global $qrlinks;
	add_options_page( 'QR Links', 'QR Links', 'manage_options', $qrlinks, 'qrlinks_option_page');  
}
add_action('admin_menu', 'qrlinks_options');
 
function qrlinks_option_page(){
	global $qrlinks;
	?><div class="wrap">
		<h2><?php echo __('QR Link Settings', 'qr-links') ?></h2>
		<form method="post" enctype="multipart/form-data" action="options.php">
			<?php 
			settings_fields('qrlinks_options');
			do_settings_sections($qrlinks);
			?>
			<p class="submit">  
				<input type="submit" class="button-primary" value="<?php echo __('Save Changes', 'qr-links') ?>" />  
			</p>
		</form>
		<div class="result">
			<?php do_action('qrlinks_after'); ?>
		</div>
	</div><?php
}
 
function qrlinks_option_settings() {
	global $qrlinks;
	register_setting( 'qrlinks_options', 'qrlinks_options', 'qrlinks_validate_settings' ); // qrlinks_options
	
	add_settings_section( 'qrlinks_section_1', __('Links', 'qr-links'), '', $qrlinks );
	
	$qrlinks_field_params = array(
		'type'      => 'url',
		'id'        => 'url_default',
		'desc'      => __('Default link', 'qr-links'), 
		'label_for' => 'url_default' 
	);
	add_settings_field( 'url_default_field', __('Default link', 'qr-links'), 'qrlinks_option_display_settings', $qrlinks, 'qrlinks_section_1', $qrlinks_field_params );
	
	$qrlinks_field_params = array(
		'type'      => 'url',
		'id'        => 'url_appstore',
		'desc'      => __('Link to the AppStore (for iOS devices)', 'qr-links'), 
		'label_for' => 'url_appstore' 
	);
	add_settings_field( 'url_appstore_field', __('Link for iOS', 'qr-links'), 'qrlinks_option_display_settings', $qrlinks, 'qrlinks_section_1', $qrlinks_field_params );
	
	$qrlinks_field_params = array(
		'type'      => 'url',
		'id'        => 'url_googleplay',
		'desc'      => __('Link to Google Play (for Android devices)', 'qr-links'), 
		'label_for' => 'url_googleplay' 
	);
	add_settings_field( 'url_googleplay_field', __('Link for Android', 'qr-links'), 'qrlinks_option_display_settings', $qrlinks, 'qrlinks_section_1', $qrlinks_field_params );
	
	$qrlinks_field_params = array(
		'type'      => 'url',
		'id'        => 'url_microsoftstore',
		'desc'      => __('Link to the Microsoft Store (for Windows Mobile devices)', 'qr-links'), 
		'label_for' => 'url_microsoftstore' 
	);
	add_settings_field( 'url_microsoftstore_field', __('Link for Windows Mobile', 'qr-links'), 'qrlinks_option_display_settings', $qrlinks, 'qrlinks_section_1', $qrlinks_field_params );
 
}
add_action( 'admin_init', 'qrlinks_option_settings' );
 
function qrlinks_option_display_settings($args) {
	extract( $args );
 
	$option_name = 'qrlinks_options';
 
	$o = get_option( $option_name );
 
	switch ( $type ) { 
		case 'url':  
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo "<input class='regular-text' type='url' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' />";  
			echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
		break;
		case 'text':  
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo "<input class='regular-text' type='text' id='$id' name='" . $option_name . "[$id]' value='$o[$id]' />";  
			echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
		break;
		case 'textarea':  
			$o[$id] = esc_attr( stripslashes($o[$id]) );
			echo "<textarea class='code large-text' cols='50' rows='10' type='text' id='$id' name='" . $option_name . "[$id]'>$o[$id]</textarea>";  
			echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
		break;
		case 'checkbox':
			$checked = ($o[$id] == 'on') ? " checked='checked'" :  '';  
			echo "<label><input type='checkbox' id='$id' name='" . $option_name . "[$id]' $checked /> ";  
			echo ($desc != '') ? $desc : "";
			echo "</label>";  
		break;
		case 'select':
			echo "<select id='$id' name='" . $option_name . "[$id]'>";
			foreach($vals as $v=>$l){
				$selected = ($o[$id] == $v) ? "selected='selected'" : '';  
				echo "<option value='$v' $selected>$l</option>";
			}
			echo ($desc != '') ? $desc : "";
			echo "</select>";  
		break;
		case 'radio':
			echo "<fieldset>";
			foreach($vals as $v=>$l){
				$checked = ($o[$id] == $v) ? "checked='checked'" : '';  
				echo "<label><input type='radio' name='" . $option_name . "[$id]' value='$v' $checked />$l</label><br />";
			}
			echo "</fieldset>";  
		break; 
	}
}
 
function qrlinks_validate_settings($input) {
	foreach($input as $k => $v) {
		if (filter_var($v, FILTER_VALIDATE_URL) === FALSE) {
			$valid_input[$k] = '';
		}
	}
	
	return $valid_input;
}