<?php
/*
Plugin Name: USSD Manager
Description:
Version: 1
Author: KAMARO Lambert
Author URI: http://kamaroly.com
*/

/*Directories that contain classes*/


//menu items
add_action('admin_menu','kasha_ussd_modifymenu');
function kasha_ussd_modifymenu() {

	////////////////////////// ADDRESSES SECTION  //////////////////////////
	//this is the main item for the menu
	add_menu_page('USSD MANAGER', //page title
	'USSD MANAGER', //menu title
	'manage_options', //capabilities
	'kasha_ussd_list', //menu slug
	'kasha_ussd_list' //function
	);
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update address', //page title
	'Update address', //menu title
	'manage_options', //capability
	'kasha_ussd_address_update', //menu slug
	'kashaUssdAddressUpdate'); //function

	//this is a submenu
	add_submenu_page(null, //parent slug
	'Add address', //page title
	'Add address', //menu title
	'manage_options', //capability
	'kasha_ussd_address_create', //menu slug
	'kashaUssdAddressCreate'); //function
	////////////////////////////////// END OF THE ADDRESS SECTION //////////////////////////////////
	

	////////////////////////// MENU ITEMS  //////////////////////////
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page('kasha_ussd_list', //parent slug
	'Ussd menu items', //page title
	'Ussd menu items', //menu title
	'manage_options', //capability
	'kasha_ussd_menu_item_list', //menu slug
	'kashaUssdMenuItems'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Add items', //page title
	'Add items', //menu title
	'manage_options', //capability
	'kasha_ussd_menu_item_create', //menu slug
	'kashaUssdMenuItemCreate'); //function

	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update items', //page title
	'Update items', //menu title
	'manage_options', //capability
	'kasha_ussd_menu_item_update', //menu slug
	'kashaUssdMenuItemUpdate'); //function
	////////////////////////// END MENU ITEMS  //////////////////////////

}


define('ROOTDIR', plugin_dir_path(__FILE__));
ini_set('display_errors', '1');
require_once(ROOTDIR . '/database/models/UssdManagerModel.php');
require_once(ROOTDIR . '/database/models/KashaUssdAddress.php');
require_once(ROOTDIR . '/database/models/kashaUssdMenuItem.php');

// LOAD HELPERS
require_once(ROOTDIR . '/app/helpers/functions.php');

// ADDRESSES PART
require_once(ROOTDIR . '/app/addresses/list.php');
require_once(ROOTDIR . '/app/addresses/create.php');
require_once(ROOTDIR . '/app/addresses/update.php');

// MENU ITEMS PART
require_once(ROOTDIR . '/app/menu-items/list.php');
require_once(ROOTDIR . '/app/menu-items/create.php');
require_once(ROOTDIR . '/app/menu-items/update.php');
