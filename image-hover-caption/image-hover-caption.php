<?php

/**
 * Plugin Name:       Image Hover Caption
 * Plugin URI:        http://www.xheggs.com/plugins/image-hover-caption
 * Description:       Simple jQuery plugin based on HCaptions that enables you to display caption overlays with cool hover effects on images anywhere on your Wordpress site using shortcodes.
 * Version:           1.0.0
 * Author:            Oluwasegun Peter Jegede
 * Author URI:        http://www.xheggs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       image-hover-caption
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
 
 /**
 * Admin Section
 */

 /*Global Variables*/
 
$hcaption_options = array();
//$hcaption_options[src] = (get_option('op_src') != '') ? get_option('op_src') : 'http://placehold.it/470x300';
	

add_action( 'admin_menu', 'image_hover_hcaptions_menu' );
function image_hover_hcaptions_menu() {
	add_options_page( 'Image Hover Caption Options', 'Image HCaption', 'manage_options', 'image-hover-hcaptions', 'image_hover_hcaptions_options' );
}
function image_hover_hcaptions_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	//global $hcaption_options;
	$hcaption_options[src] = (get_option('op_src') != '') ? get_option('op_src') : 'http://placehold.it/470x300';
	
	// basic validation checks whether form has been submitted from the options page before updating db
	
	if(isset($_POST['image_hover_hcaptions_form_submitted'])){
		
		$hidden = esc_html($_POST['image_hover_hcaptions_form_submitted']);
 
			if($hidden == 'b23t'){ // validates with the value from the hidden field
				
				// adds or update db entry
				if ( ! update_post_meta (68, 'src_key', $hcaption_options[src]) ) { 
					add_post_meta( 68, 'src_key', $hcaption_options[src], true );	
					}; 
				}
			}
	$output.= '	
	<div class="wrap">
	
    <h2>Image Hover Caption Default Settings</h2>
	<br>
    <div id="poststuff">
 
        <div id="post-body" class="metabox-holder columns-2">
 
            <!-- main content -->
            <div id="post-body-content">
 
                <div class="meta-box-sortables ui-sortable">
 
                    <div class="postbox">
 
                        <h3><span>Main Content Header</span></h3>
                        <div class="inside">
                            <form name="image_hover_hcaption_options_form" method="post" action="">
								<table class="form-table" width="100%" cellpadding="10"><tbody>
									<tr><td scope="row" align="left">Default Image</td><td scope="row" align="left"><input size="100" type="text" name="op_src" value="' . get_post_meta(68, 'src_key', true) . '" readonly/><br><i>Set image with short code [image-hcaption src=" "]</i></td></tr>
									
									<tr><td scope="row" align="left"><input class="button-primary" name="image_hover_hcaption_form_submit" type="submit" value="Set Default Values" /></td><td scope="row" align="left"></td></tr>
								</tbody></table>
								
								<input type="hidden" name="image_hover_hcaptions_form_submitted" value="b23t"> 
							</form>
                        </div> <!-- .inside -->
 
                    </div> <!-- .postbox -->
 
                </div> <!-- .meta-box-sortables .ui-sortable -->
 
            </div> <!-- post-body-content -->
 
            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
 
                <div class="meta-box-sortables">
 
                    <div class="postbox">
 
                        <h3><span>Sidebar Content Header</span></h3>
                        <div class="inside">
                            <h3><span>About this plugin</span></h3>
                    <div class="inside">
 
                        <p>This plugin allows business owners &amp; webmasters to dynamically control how the phone number, email address &amp; street address appear on the frontend of the website.
                     </p>
                 </div> <!-- .inside -->
                   <h3>How to use this plugin</h3>
                 <div class="inside">
 
                   <p>Developers, simply copy and paste the code associated with each field into your website.</p>      
 
                    </div> <!-- .inside -->
 
                  <h3>Author</h3>
                 <div class="inside">
 
                   <p>This plugin was created by <a href="http://www.mash-webdesign.co.uk" title="Mash Web Design" target="_blank">Mash Web Design</a></p>      
 
                    </div> <!-- .inside -->
                        </div> <!-- .inside -->
 
                    </div> <!-- .postbox -->
 
                </div> <!-- .meta-box-sortables -->
 
            </div> <!-- #postbox-container-1 .postbox-container -->
 
        </div> <!-- #post-body .metabox-holder .columns-2 -->
 
        <br class="clear">
    </div> <!-- #poststuff -->
 
</div> <!-- .wrap -->';
echo html_entity_decode($output);
}

 
 
 /**
 * Load HCaption js file and ensure jQuery is loaded
 */
 function image_hover_hcaptions_main_files() {
	wp_enqueue_script(
		'image-hover-hcaptions-js',
		plugins_url( '/js/jquery.hcaptions.js' , __FILE__ ),
		array( 'jquery' )
	);
	wp_enqueue_script(
		'image-hover-load-hcaptions-js',
		plugins_url( '/js/load.hcaption.js' , __FILE__ )
	);
	wp_enqueue_style(
		'image-hover-hcaptions-css',
		plugins_url( '/css/hcaptions.css' , __FILE__ ));
}

add_action( 'wp_enqueue_scripts', 'image_hover_hcaptions_main_files' );



// Add Shortcode
function image_hover_hcaptions_shortcode( $atts , $content = null ) {
	
	//global $hcaption_options;
	$hcaption_options[src] = get_post_meta(68, 'src_key', true);
	// Attributes
	extract( shortcode_atts(
		array(
				'src' => $hcaption_options[src],
				'effect' => 'slide',
				'direction' => 'top',
				'speed' => '400',
				'opacity' => '1',
		), $atts )
	);
	
	// Code
	$output .= '
					<a href="#" 
						class="hcaption" 
						data-target="#myToggle1" 
						cap-effect='.$effect.'
						cap-direction='.$direction.'
						cap-speed='.$speed.'
						cap-opacity='.$opacity.'
					>
						<img src='.$src.' />
					</a>
					<div id="myToggle" class="cap-overlay">
						' . do_shortcode( $content ) . '
					</div>
				';
	return $output;
}
add_shortcode( 'image-hcaption', 'image_hover_hcaptions_shortcode' );
