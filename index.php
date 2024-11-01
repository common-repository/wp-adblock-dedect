<?php
/*
Plugin Name: WP Adblock Dedect
Plugin URI: https://getadmiral.com/
Description: Your for WordPress site Adblock Dedect Plugin with show ads!
Version: 3.5.2
Author: Admiral
Author URI: https://getadmiral.com/
License: GNU
*/

## WP Adblock Admin Assets ##
add_action('admin_enqueue_scripts', 'wpajansAdblock_admin_assets');
function wpajansAdblock_admin_assets(){
  wp_enqueue_style( 'wpajansAdblock', plugins_url( 'css/adblock.css', __FILE__ ));
}

## WP Adblock Front Assets ##
add_action('wp_footer','wpajansAdblock_front_assets');
function wpajansAdblock_front_assets(){
  wp_enqueue_script( 'dedect', plugins_url( 'js/dedect.js', __FILE__ ), array( 'jquery' ) );
  wp_enqueue_style( 'wpajans_dedect', plugins_url( 'css/wpajans_dedect.css', __FILE__ ));
}

## Default Options ##
register_activation_hook(__FILE__, 'NoAdblockPlusDefault');
function NoAdblockPlusDefault() {
    add_option('NoAdblockPlusTitle', 'Ops!');
    add_option('NoAdblockPlusDesc', 'Please, adblock close and refresh page!');
    add_option('NoAdblockPlusBtn', 'Continue');
    add_option('NoAdblockPlusRedirectPage', 'none');
}

## Metabox ##
function NoAdblockPlusMetaContent($object)
{
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$checkbox_value = (get_post_meta($object->ID, "NoAdblockPlusNoShow", true)=="true"?"checked":"");
    echo'<label for="NoAdblockPlusNoShow">Don\'t show Adblock Modal</label> <input name="NoAdblockPlusNoShow" type="checkbox" value="true" '.$checkbox_value.'>';
}

function NoAdblockPlusMetaBox()
{
    add_meta_box("NoAdblockPlusMeta-Box", "WpAJANS Adblock Settings", "NoAdblockPlusMetaContent", "post", "side", "high", null);
}

add_action("add_meta_boxes", "NoAdblockPlusMetaBox");

function NoAdblockPlusMetaBoxSave($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;
    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;
        $meta_box_checkbox_value = $_POST["NoAdblockPlusNoShow"];
        update_post_meta($post_id, "NoAdblockPlusNoShow", $meta_box_checkbox_value);
}

add_action("save_post", "NoAdblockPlusMetaBoxSave", 10, 3);



## Plugin Menu ##
add_action('admin_menu', 'NoAdblockPlusAdmin');
function NoAdblockPlusAdmin()
{
  add_menu_page('Adblock Dedect','Adblock Dedect','manage_options','adblock-dedect','wpajansAdblockAboutPage');
  add_submenu_page('adblock-dedect','Detect Settings','Adblock Dedect Settings','manage_options','adblock-dedect-settings','NoAdblockPlusSettings');
}

## About Page ##
function wpajansAdblockAboutPage(){
?>
	<div class="card pressthis" style="max-width:100% !important">
	<h2>Welcome to WPAJANS Adblock De(d)ect Plugin About Page!</h2>
	<p>No detect, because I posted wrong when publishing the plugin : ) anyway no problem!</p>
	</div>
	<div class="card pressthis" style="max-width:100% !important; background-color: #e74c3c; color:#fff">
	<h2 style="color:#fff;">Save 30% on WordPress Hosting Packages!</h2>
	<p>
	<img style="width: 100%" src="<?php echo plugins_url( 'wpsc.png', __FILE__ ) ?>">
	<br>
	WpAJANS addons specific save 30% on WordPress Hosting Packages! <br> Coupon code : <b>WPTR-2017</b> <a href="https://www.sunucucozumleri.com/wordpress-hosting" style="color:#fff;font-size: 15px;font-weight: bold;">WordPress Hosting Packages</a></p>
	</div>
	<div class="card pressthis" style="max-width:100% !important">
	<h2>Vote Plugin</h2>
	<p>If like plugin, please vote! <a href="https://wordpress.org/support/plugin/wp-adblock-dedect/reviews/#new-post">Vote plugin</a></p>
	</div>
<?php
}

## Settings Page ##
function NoAdblockPlusSettings() {
	if ($_POST['NoAdblockPlusValuesSubmit'] == 'Yeah') {
	 if (!isset($_POST['wpajans_adblock_update']) || ! wp_verify_nonce( $_POST['wpajans_adblock_update'], 'wpajans_adblock_update' ) ) {
	 	 print 'Sorry, your nonce did not verify.';
	   exit;
	 }else{
		$NoAdblockPlusTitle = wp_kses_post($_POST['NoAdblockPlusTitle']);
		update_option('NoAdblockPlusTitle', $NoAdblockPlusTitle);
		$NoAdblockPlusDesc = wp_kses_post($_POST['NoAdblockPlusDesc']);
		update_option('NoAdblockPlusDesc', $NoAdblockPlusDesc);
		$required = sanitize_text_field($_POST["required"]);
		update_option('required', $required);
		$NoAdblockPlusBtn = sanitize_text_field($_POST["NoAdblockPlusBtn"]);
		update_option('NoAdblockPlusBtn', $NoAdblockPlusBtn);
		$NoAdblockPlusRedirectPage = sanitize_text_field($_POST["NoAdblockPlusRedirectPage"]);
		update_option('NoAdblockPlusRedirectPage', $NoAdblockPlusRedirectPage);
		$NoAdblockPlusTheme = sanitize_text_field($_POST["NoAdblockPlusTheme"]);
		update_option('NoAdblockPlusTheme', $NoAdblockPlusTheme);
		$NoAdblockPlusMainClass = sanitize_text_field($_POST["NoAdblockPlusMainClass"]);
		update_option('NoAdblockPlusMainClass', $NoAdblockPlusMainClass);
        echo'<div class="updated"><p><strong>Options Saved.</strong></p></div>';
	}}
	$getNoAdblockPlusTheme = get_option('NoAdblockPlusTheme');
	?>
  <div class='wrap'>
    <div id="wpnlh_navbar"><span> WP Adblock Dedect <small>3.5.2</small></span></div>
    <div id="wpnlh_content">
      <div class="wpnlh_content_block">
	<form method="post">
	<input type="text" class="NoAdblockPlusInput" placeholder="Popup Title" name="NoAdblockPlusTitle" value="<?php echo get_option('NoAdblockPlusTitle'); ?>" style="box-shadow:0 8px 24px rgba(0,0,0,0.15);border-radius:4px;margin:10px 0"/>
	<br />
	<input type="text" class="NoAdblockPlusInput" placeholder="Button Text" name="NoAdblockPlusBtn" value="<?php echo get_option('NoAdblockPlusBtn'); ?>" style="box-shadow:0 8px 24px rgba(0,0,0,0.15);border-radius:4px;margin:10px 0" />
	<br />
	<?php wp_nonce_field( 'wpajans_adblock_update', 'wpajans_adblock_update' ); ?>
	<textarea name="NoAdblockPlusDesc" placeholder="Popup Desc" class="NoAdblockPlusTextArea" rows="8" cols="40"><?php echo get_option('NoAdblockPlusDesc');?></textarea>
	<br />
	<div id="NoAdblockPlusNotice">If you want redirect please choose</div>
	<select name="NoAdblockPlusRedirectPage" id="NoAdblockPlusSelect">
	<option value="none">Select Page</option>
	<?php
	$pages = get_pages();
	foreach ( $pages as $page) {
		$selected = (get_option("NoAdblockPlusRedirectPage")==$page->ID?"SELECTED":"");
		$option = '<option '.$selected.' value="'.$page->ID.'">';
		$option .= $page->post_title;
		$option .= '</option>';
		echo $option;
	}
	?>
	</select>
	<br>
	<div id="NoAdblockPlusNotice">Select Theme</div>
	<select name="NoAdblockPlusTheme" id="NoAdblockPlusSelect">
		<option value="1" <?php echo($getNoAdblockPlusTheme==1?'SELECTED':''); ?>>Light Theme</option>
		<option value="2" <?php echo($getNoAdblockPlusTheme==2?'SELECTED':''); ?>>Dark Theme</option>
		<option value="3" <?php echo($getNoAdblockPlusTheme==3?'SELECTED':''); ?>>Red Theme</option>
		<option value="4" <?php echo($getNoAdblockPlusTheme==4?'SELECTED':''); ?>>Blue Theme</option>
	</select>
	<br>
	<div id="NoAdblockPlusNotice">main class or id for blur effect</div>
	<input type="text" class="NoAdblockPlusInput" placeholder="#main or .main" name="NoAdblockPlusMainClass" value="<?php echo get_option('NoAdblockPlusMainClass'); ?>" style="box-shadow:0 8px 24px rgba(0,0,0,0.15);border-radius:4px;margin:10px 0"/>
	<br />
	<br>
	<label><input type="checkbox" <?php if(get_option("required")=="Required"){echo"checked";}?> name="required" value="Required"/> Required</label>
	<br>
	<input type="hidden" id="NoAdblockPlusValuesSubmit" name="NoAdblockPlusValuesSubmit" value="Yeah"/><br />
	<input type="submit" id="submit" name="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
	</form>
     </div>
  </div>
<?php }
add_action("wp_footer","NoAdblockPlus");
function NoAdblockPlus(){
	$getNoAdblockPlusNoShow = (is_single()?get_post_meta(get_the_ID(),'NoAdblockPlusNoShow',true):"false");
	$getNoAdblockPlusTheme = get_option('NoAdblockPlusTheme');
	$getNoAdblockPlusMainClass = get_option('NoAdblockPlusMainClass');
	switch($getNoAdblockPlusTheme)
	{
		case '1': // Light Theme
		$themeColor = array("background" => "#fff", "icon" => "#f8bb86", "title" => "#595959", "content" => "#000", "button" => "#3085d6", "buttonColor" => "#ffffff");
		break;
		case '2': // Dark Theme
		$themeColor = array("background" => "#2b2b2b", "icon" => "#cccccc", "title" => "#cccccc", "content" => "#cccccc", "button" => "#000000", "buttonColor" => "#cccccc");
		break;			
		case '3': // Red Theme
		$themeColor = array("background" => "#e14853", "icon" => "#820c00", "title" => "#820c10", "content" => "#820c10", "button" => "#820c00", "buttonColor" => "#cccccc");
		break;		
		case '4': // Blue Theme
		$themeColor = array("background" => "#345cb5", "icon" => "#103382", "title" => "#fff", "content" => "#fff", "button" => "#1a397d", "buttonColor" => "#fff");
		break;		
	}
if(get_option("NoAdblockPlusRedirectPage")=="none"){
	echo'<div class="swal2-container swal2-fade swal2-in" style="overflow-y: auto;display:none">
   <div class="swal2-modal adBlockDetectModal swal2-show" style="display: block; width: 500px; padding: 20px; background: '.$themeColor["background"].';" tabindex="-1">
    <div class="swal2-icon swal2-warning pulse-warning" style="color:'.$themeColor["icon"].';border-color:'.$themeColor["icon"].'">!</div>
    <h2 class="swal2-title" style="color:'.$themeColor["title"].'">'.get_option("NoAdblockPlusTitle").'</h2>
    <div class="swal2-content" style="display: block;color:'.$themeColor["title"].'">
    '.get_option("NoAdblockPlusDesc").'
    </div>';
    if(get_option("required")=="Required"){}else{
    echo'<hr class="swal2-spacer" style="display: block;background:'.$themeColor["icon"].'">
    <button type="button" class="swal2-confirm swal2-styled wpajansAdblockCloseBTN" style="background-color: '.$themeColor["button"].'; color:'.$themeColor["buttonColor"].'">'.get_option("NoAdblockPlusBtn").'</button>
    <span class="swal2-close wpajansAdblockCloseBTN" style="display: block;color:'.$themeColor["icon"].'">×</span>';
}
   echo'</div>
 </div>';}else{}
?>
<script>
<?php
if(get_option("NoAdblockPlusRedirectPage")!="none" and !is_page(get_option("NoAdblockPlusRedirectPage"))){ 
	echo'window.location = "'.get_page_link(get_option("NoAdblockPlusRedirectPage")).'"';
}
?>

function adBlockNotDetected() {
}

function adBlockDetected() {
<?php if($getNoAdblockPlusNoShow!="true" && get_option("NoAdblockPlusRedirectPage")=="none"){ ?>
jQuery(window).load(function() {
var modal = jQuery(".swal2-container"),
yesBtn = jQuery(".wpajansAdblockCloseBTN");
jQuery("<?php echo $getNoAdblockPlusMainClass; ?>").css({"filter":"blur(10px)"});
modal.fadeIn(250);
yesBtn.on("click", function() {
jQuery("<?php echo $getNoAdblockPlusMainClass; ?>").css({"filter":"blur(0)"});
modal.fadeOut(150);
});
}())
<?php } ?>
}

jQuery(document).ready(function(){
var fuckAdBlock = new FuckAdBlock({
checkOnLoad: true,
resetOnEnd: true
});
fuckAdBlock.onDetected(adBlockDetected);
fuckAdBlock.onNotDetected(adBlockNotDetected);
});

</script>
<?php
}
?>
