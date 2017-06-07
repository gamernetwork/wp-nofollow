<?php
/*
      Plugin Name: Ultimate Nofollow
      Plugin URI: https://github.com/gamernetwork/wp-nofollow/
      Description: A suite of tools that gives you complete control over the rel=nofollow tag on an individual link basis.
      Version: 0.1
      Author: Gamer Network
      Author URI: https://github.com/gamernetwork/
	
*/

/***********************
* OPTIONS PAGE SECTION *
************************/

/* add plugin's options to white list / defaults */
function ultnofo_options_init() { 
	register_setting( 'ultnofo_options_options', 'ultnofo_item', 'ultnofo_options_validate' );

	// if option doesn't exist, set defaults
	if( !get_option( 'ultnofo_item' ) ) add_option( 'ultnofo_item', array( 'nofollow_comments' => 1, 'nofollow_blogroll' => 0, 'nofollow_default' => 0, 'new_tab_default' => 0), '', 'no' ); 
}

/* add link to plugin's settings page under 'settings' on the admin menu */
function ultnofo_options_add_page() { 
	add_options_page( 'Ultimate Nofollow Settings', 'Nofollow', 'manage_options', 'ultimate-nofollow', 'ultnofo_options_do_page' );
}

/* sanitize and validate input. 
accepts an array, returns a sanitized array. */
function ultnofo_options_validate( $input ) { 
	$input[ 'nofollow_default' ] = ( $input[ 'nofollow_default' ] == 1 ? 1 : 0 ); // (checkbox) if 1 then 1, else 0
	$input[ 'new_tab_default' ] = ( $input[ 'new_tab_default' ] == 1 ? 1 : 0 ); // (checkbox) if 1 then 1, else 0
	return $input;
}

/* draw the settings page itself */
function ultnofo_options_do_page() { 
	?>
	<div class="wrap">
    <div class="icon32" id="icon-options-general"><br /></div>
		<h2>Ultimate Nofollow Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'ultnofo_options_options' ); // nonce settings page ?>
			<?php $options = get_option( 'ultnofo_item' ); // populate $options array from database ?>
			<table class="form-table">
				
                
                <tr valign="top">
					<th scope="row">Set nofollow as default when creating new links?</th>
					<td><input name="ultnofo_item[nofollow_default]" type="checkbox" value="1" <?php checked( $options[ 'nofollow_default' ] ); ?> />
                </tr>
                <tr valign="top">
					<th scope="row">Set 'Open in new tab' as true by default when creating new links?</th>
					<td><input name="ultnofo_item[new_tab_default]" type="checkbox" value="1" <?php checked( $options[ 'new_tab_default' ] ); ?> />
                </tr>
           		
           
				<!-- <tr valign="top"><th scope="row">Text:</th>
					<td>
                    	UA-<input type="text" name="ssga_item[sometext1]" value="<?php // echo $options[ 'test_text_1']; ?>" style="width:90px;" maxlength="8" />
					</td>
				</tr> -->
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php
}

/* define additional plugin meta links */
function set_plugin_meta_ultnofo( $links, $file ) { 
	$plugin = plugin_basename( __FILE__ ); // '/nofollow/nofollow.php' by default
    	if ( $file == $plugin ) { // if called for THIS plugin then:
		$newlinks = array( 
			'<a href="options-general.php?page=ultimate-nofollow">Settings</a>',
			'<a href="http://shinraholdings.com/plugins/nofollow/help">Help Page</a>' 
		); // array of links to add
		return array_merge( $links, $newlinks ); // merge new links into existing $links
	}
	return $links; // return the $links (merged or otherwise)
}

/* add hooks/filters */
// add meta links to plugin's section on 'plugins' page (10=priority, 2=num of args)
add_filter( 'plugin_row_meta', 'set_plugin_meta_ultnofo', 10, 2 ); 

// add plugin's options to white list on admin initialization
add_action('admin_init', 'ultnofo_options_init' ); 

// add link to plugin's settings page in 'settings' menu on admin menu initilization
add_action('admin_menu', 'ultnofo_options_add_page'); 

function nofollow_admin_head() {
	$options = get_option( 'ultnofo_item' );
	echo '<script>NOFOLLOW_DEFAULT = "'. $options['nofollow_default'] . '";</script>';
	echo '<script>NEW_TAB_DEFAULT = "'. $options['new_tab_default'] . '";</script>';
}
add_action( 'admin_head', 'nofollow_admin_head' );

function nofollow_redo_wplink() {
        wp_deregister_script( 'wplink' );
       
        wp_register_script( 'wplink', plugins_url( 'wplink.js', __FILE__), array( 'jquery', 'wpdialogs' ), false, 1 );
       
        wp_localize_script( 'wplink', 'wpLinkL10n', array(
                'title' => __('Insert/edit link'),
                'update' => __('Update'),
                'save' => __('Add Link'),
                'noTitle' => __('(no title)'),
                'noMatchesFound' => __('No matches found.')
        ) );
}
add_action( 'admin_enqueue_scripts', 'nofollow_redo_wplink', 999 );

?>
