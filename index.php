<?php
/**
* Plugin Name: SEO Friendly sitemaps
* Plugin URI: https://github.com/angepili
* Description: Generate sitemap super Google friendly
* Version: 1.0
* Requires PHP: 7.4
* Author: Angelo Pili
* Author URI:  https://github.com/angepili
*/

array_map( fn( $file ) =>  
    include_once( __DIR__ . $file )
,[
    '/vendor/autoload.php',
    '/Lib/Hooks.php',
    '/Lib/SitemapConfigs.php',
    '/Lib/SitemapClass.php',
]);

use Lib\Hooks;
( new Hooks() );