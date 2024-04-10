<?php

/**
 * @wordpress-plugin
 * Plugin Name:       PLUBO Checks
 * Plugin URI:        https://sirvelia.com/
 * Description:       Check your envs, constants, functions and classes easily.
 * Version:           1.0.0
 * Author:            Albert Tarrés - Sirvelia
 * Author URI:        https://sirvelia.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       plubo-checks
 * Domain Path:       /languages
 */

define('PLUBO_CHECKS_PLUGIN_DIR', plugin_dir_path(__FILE__));
require_once PLUBO_CHECKS_PLUGIN_DIR . 'vendor/autoload.php';

// use PluboChecks\Check;
// PluboChecks\ChecksProcessor::init( 'test' );
// add_filter('plubo/checks', function( $checks ) {
//     $checks['test'][] = new Check(
//         'EXAMPLE_ENV_VARIABLE',
//         Check::$TYPE_ENV,
//         Check::$WARNING,
//         'Check for testing'
//     );
//     return $checks;
// });