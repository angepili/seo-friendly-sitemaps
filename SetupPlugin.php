<?php
namespace Lib\Setup;

class SparklingSitempaPlugin {
   
    public function __construct() {
        add_action('init', [&$this, 'rewrites']);
        add_filter('template_include', [&$this, 'template' ]);

    }

    public function rewrites() {
        add_rewrite_rule('^sitemap/(post|page).xml', 'index.php?sitemap=$matches[1]', 'top' );
        flush_rewrite_rules();
    }

    public function template( $template ) {
        if( get_query_var( 'sitemap' ) ) {
		    $template = plugin_dir_path(__FILE__) ."/sitemap.php";
        }
        return $template;
    }

}