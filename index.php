<?php
/**
* Plugin Name: Sparkling Sitemaps
* Plugin URI: https://www.socialidea.it
* Description: Generate sitemap super Google friendly
* Version: 1.0
* Requires PHP: 7.4
* Author: Socialidea
* Author URI:  https://www.socialidea.it
*/

array_map( fn( $file ) =>  
    include_once( __DIR__ . $file )
,[
    '/vendor/autoload.php',
    '/SparklingSitemaps.php',
    '/SetupPlugin.php',
]);

use Lib\Setup\SparklingSitempaPlugin;
( new SparklingSitempaPlugin() );