<?php

namespace Lib;

use Lib\SitemapConfigs;

class Hooks {
   
    public function __construct() {
        add_filter('query_vars', [&$this, 'add_query_vars'] );
        add_filter('template_include', [&$this, 'template' ]);
        add_action('init', [&$this, 'rewrites']);
        $this->url_names = ( new SitemapConfigs )->getUrlNames();
    }

    public function rewrites() {
        add_rewrite_rule("sitemap/($this->url_names).xml", 'index.php?sitemap=$matches[1]', 'top' );
        flush_rewrite_rules();
    }

    public function add_query_vars( $query_vars ) {
        $query_vars[] = 'sitemap';
        return $query_vars;
    }
    
    public function template( $template ) {
        if( get_query_var( 'sitemap' ) ) {
		    $template = plugin_dir_path(__FILE__) ."./../sitemap-xml.php";
        }
        return $template;
    }

}