<?php
/**
Plugin Name: Stewardship
Description: A plugin for churches to manage members, visitors, .
Version: 1.0
Author: James Hammack
Author URI: http://james.hammack.us
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: exp
Domain Path: languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

class Stewardship {

	/**
	 * Construct.
	 */
	public function __construct() {
		// Set the record straight
		define( 'STEWARD_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
		// Include Files
		add_action( 'plugins_loaded', array($this,'includes'), 0 );
		// Text Domain
		add_action( 'init', array($this,'steward_text_domain'));
		// Remove Connection Submenu
		//add_action( 'admin_menu', array($this,'steward_remove_submenus'), 999 );


		// Keep Updating Please
		$this->item_name	= 'Stewardship';
		$this->file			= __FILE__;
		$this->license_slug	= 'stewardship';
		$this->version		= '1.0';
		$this->author		= 'James Hammack';
		add_action( 'init', array( $this, 'load_updater' ), 0 );


	}


	/**
	 * Run the updater scripts from the Sidekick
	 * @return void
	 */
	public function load_updater() {
		// Check if sidekick is loaded
		if (class_exists('WPO_Updater')) {
			$this->updater = new WPO_Updater( $this->item_name, $this->file, $this->license_slug, $this->version, $this->author );
		}
	}

	function includes(){
		if( is_admin() ) {
			//require_once dirname( __FILE__ ) .'/assets/p2p/posts-to-posts.php';
		}
		// Custom Posts for People
		require_once dirname( __FILE__ ) . '/includes/cpt.php';
		// Require CMB2
		if ( file_exists(  __DIR__ . '/assets/CMB2/init.php' ) ) {
		  require_once  dirname( __FILE__ ) . '/assets/CMB2/init.php';
		  require_once  dirname( __FILE__ ) . '/assets/cmb2-attached-posts/cmb2-attached-posts-field.php';
		}
		// Metaboxes for People
		require_once dirname( __FILE__ ) . '/includes/metaboxes.php';
		require_once dirname( __FILE__ ) . '/includes/status_walker.php';
	}

	function steward_text_domain() {
		load_plugin_textdomain( 'steward' );
	}

	function steward_remove_submenus() {
		remove_submenu_page( 'tools.php', 'connection-types' );
	}


}
$Stewardship = new Stewardship();
