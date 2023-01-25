<?php
/**
* Plugin Name: Elxr Suggestics
* Plugin URI: https://elxr.life
* Description: integration of suggestics
* Author: Hosni Colina
* Author URI: https://portl.com
* Version: 1.0.0
*/

defined( 'ABSPATH' ) || die( "Can't access directly" );

define('SG_TOKEN','23ecb740706d0d904c9eeec1a97922a01dc6aced');

require_once(plugin_dir_path(__FILE__) . '/src/Model/Schema.php');
require_once(plugin_dir_path(__FILE__) . '/src/Graphql/ElxrGraphql.php');
require_once(plugin_dir_path(__FILE__) . '/src/Api/ElxrApi.php');
require_once(plugin_dir_path(__FILE__) . '/inc/function.php');
require_once(plugin_dir_path(__FILE__) . '/inc/filter.php');
require_once(plugin_dir_path(__FILE__) . '/vendor/autoload.php');


new App\ElxrApi\ElxrApi();


// TODO:
/*

	Find a way to show the meal plans on the dashboard per day. 
	Find a way to attribute EATEN, SKIPPED to each meal plan
	Find a way to track the fat, calories and other nutritional data per eat meal
	find a way to query that tracked data.
	Deliver to Juan.
*/