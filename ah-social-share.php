<?php
/*
Plugin Name: AH Social share
Plugin URI: https://dl.dropboxusercontent.com/u/94484242/ah-social-share/index.html
Description: AH Social share is help you to share your post in facebook, twitter, linkedin, goole+. The installation of AH Social share plugin is easy and even a beginner can deal with it, Just load Share Buttons plugin, activate. Make your website worldwide by the using AH Social share.
Version: 1.0
Author: KrishnA Paul
Author URI: https://in.linkedin.com/in/paulkrrish14
*/

//require_once( 'include/settings.php' );

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

/**
 * Register style sheet.
 */
function register_plugin_styles() {
	wp_register_style( 'ah-social-share', plugins_url( 'ah-social-share/css/plugin.css' ) );
	wp_enqueue_style( 'ah-social-share' );
}

add_filter ('the_content', 'ah_post_function');
function ah_post_function($content) {
    global $post;
    if(is_single()) {
        if(get_post_meta($post->ID, 'cpi_text_option', true) == "yes" || get_post_meta($post->ID, 'cpi_text_option', true) == "") {
            $content.= '<div id="ah-share-buttons">';
            $content.= '<a href="http://www.facebook.com/sharer.php?u='.get_post_permalink($post->ID).'" target="_blank"><img src="'.plugins_url( 'images/facebook.png', __FILE__ ).'" alt="Facebook" /></a>';
            $content.= '<a href="http://twitter.com/share?url='.get_post_permalink($post->ID).'&text='.get_the_title($post->ID).'" target="_blank"><img src="'.plugins_url( 'images/twitter.png', __FILE__ ).'" alt="Twitter" /></a>';
            $content.= '<a href="http://www.linkedin.com/shareArticle?mini=true&url='.get_post_permalink($post->ID).'" target="_blank"><img src="'.plugins_url( 'images/linkedin.png', __FILE__ ).'" alt="LinkedIn" /></a>';
            $content.= '<a href="https://plus.google.com/share?url='.get_post_permalink($post->ID).'" target="_blank"><img src="'.plugins_url( 'images/google+.png', __FILE__ ).'" alt="Google" /></a>';
            $content.= '</div>';
        }
    }
    return $content;
}

// ===================
// = POST OPTION BOX =
// ===================

add_action('admin_menu', 'ah_post_options_box');

function ah_post_options_box() {
add_meta_box('post_info', 'AH Social share', 'ah_post_info', 'post', 'side', 'high');
}

//Adds the actual option box
function ah_post_info() {
global $post;
?>
<fieldset id="mycustom-div">
    <div>
        <p>
            <b>Add share buttons on this page:</b><br/>
            <?php
            if(get_post_meta($post->ID, 'cpi_text_option', true) == "yes") {
            ?>
                <label><input type="radio" checked name="cpi_text_option" value="yes" />&nbsp;Yes </label>&nbsp;&nbsp;
                <label><input type="radio" name="cpi_text_option" value="no" />&nbsp;No</label>
            <?php
            }else if(get_post_meta($post->ID, 'cpi_text_option', true) == "no") {
            ?>
                <label><input type="radio" name="cpi_text_option" value="yes" />&nbsp;Yes </label>&nbsp;&nbsp;
                <label><input type="radio" checked name="cpi_text_option" value="no" />&nbsp;No</label>
            <?php
            }else {
            ?>
                <label><input type="radio" name="cpi_text_option" value="yes" />&nbsp;Yes </label>&nbsp;&nbsp;
                <label><input type="radio" name="cpi_text_option" value="no" />&nbsp;No</label>
            <?php
            }
            ?>
        </p>
    </div>
</fieldset>
<?php
}

add_action('save_post', 'custom_add_save');
function custom_add_save($postID){
    // called after a post or page is saved
    if($parent_id = wp_is_post_revision($postID)) {
        $postID = $parent_id;
    }
    if ($_POST['cpi_text_option']) {
        update_custom_meta($postID, $_POST['cpi_text_option'], 'cpi_text_option');
    }
}

function update_custom_meta($postID, $newvalue, $field_name) {
    // To create new meta
    if(!get_post_meta($postID, $field_name)){
        add_post_meta($postID, $field_name, $newvalue);
    }else{
        // or to update existing meta
        update_post_meta($postID, $field_name, $newvalue);
    }
}