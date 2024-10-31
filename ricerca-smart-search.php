<?php
/**
 * Plugin Name:       Ricerca smart and advanced search
 * Plugin URI:        https://www.myricerca.com/
 * Description:       Advanced & fastest search engine. let your visitor simple find every thing the need.
 * Version:           1.1.8
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * Author:            R2K TEAM
 * Author URI:        https://www.r2k.co.il/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ric
 * Domain Path:       /languages
 */
defined( 'ABSPATH' ) || exit;
 
define('RIC_VER','1.1.8');
define('RIC_DB_VER','1.0.20');
define('RIC_MIN_PHP','7.4');
define('RIC_MIN_WP','5.6');
define('RIC_NONCE_KEY', 'ricnonce'.str_replace('.', '', RIC_VER));
if(!defined('RIC_SLUG')){
    define('RIC_SLUG','RIC');
}
if(!defined('RIC_SETTINGS_KEY')){
    define('RIC_SETTINGS_KEY','ric_global_settings');
}
if(!defined('RIC_TABLE_PREFIX')){
    define('RIC_TABLE_PREFIX','ric_');
}
define('RIC_URL',plugin_dir_url( __FILE__ ));
if ( ! defined( 'RIC_FILE' ) ) {
	define( 'RIC_FILE', __FILE__ );
}
if ( ! defined( 'RIC_DIR' ) ) {
	define( 'RIC_DIR', dirname(__FILE__).'/' );
}
 

 
//load main pro file
require RIC_DIR.'main.php';
Ric\RicPlugin::init();