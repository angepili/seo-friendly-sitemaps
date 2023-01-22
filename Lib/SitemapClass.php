<?php

namespace Lib;
use Lib\SitemapCongigs;

class SitemapClass {
    
    function __construct( $querVars = null ) {
        $this->node        = $querVars['node'] ?: null;
        $this->acceptTypes = ['tax','post_type'];

        $this->isWpml = defined('ICL_LANGUAGE_CODE');
    }

    public function matchQueryString() {
    
        if( !$this->node ) return;

        $nodes = ( new SitemapConfigs )->getData();

        $this->type = $nodes[ $this->node ]['type'];
        $this->changefreq = $nodes[ $this->node ]['changefreq'];
        $this->priority = $nodes[ $this->node ]['priority'];
        
        unset( $nodes[ $this->node ]['changefreq'] );
        unset( $nodes[ $this->node ]['priority'] );

        $this->data = $nodes[ $this->node ];

    }


    public function getTaxData() {
    
        if( $this->isWpml ) $langs =  array_keys( apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0&orderby=id&order=desc' ) );

        $tax_name = $this->data['taxonomy'];
        $tax = get_taxonomy( $tax_name );
        $terms = get_terms( $this->data );

        $data = [];

        foreach( $terms as $term ) {

            $url = get_term_link( $term );
            
            $urlTranslated = [];
            if( $this->isWpml ) :
            foreach( $langs as $lang ) {
                $term_id = apply_filters( 'wpml_object_id', $term->term_id,  'category' , FALSE, $lang );
                $term_url = get_term_link( $term_id );
                $urlTranslate[ $lang ] = $term_url;
            }
            unset( $urlTranslate[ ICL_LANGUAGE_CODE ] );
            endif;


            array_push( $data, [
                'loc' => $url,
                'alternate' => $urlTranslate
            ]);

        }

        return $data;

    }

    public function getPostData() {

        $data = [];

        $query = new \WP_Query( $this->data );

        if( $query->have_posts() ) :
            while($query->have_posts()) :
                $query->the_post();
                global $post;

                $url = get_permalink( $post->ID );
                $lastmod = get_the_modified_date('Y-m-d\Th:m:s+00:00', $post->ID );

                $urlTranslated = [];

                if( $this->isWpml ) :
                $post_type = 'post_' . get_post_type($post->ID);
                $translations = apply_filters('wpml_get_element_translations', [], apply_filters('wpml_element_trid', false, $post->ID, $post_type), $post_type);      

                unset( $translations[ ICL_LANGUAGE_CODE ] );
                
                foreach( $translations as $lang => $trans ) {
                    $urlTranslated[$lang] = get_permalink($trans->element_id);
                }
                endif;

                array_push( $data, [
                    'loc' => $url,
                    'lastmod' => $lastmod,
                    'alternate' => $urlTranslated
                ]);

                wp_reset_postdata();
            endwhile;
        endif;

        return $data;

    }

    public function xmlBody( $item ) {

        $xml = '<url>'."\n";
            $xml .= '<loc>'.$item['loc'].'</loc>'."\n";
            if( $item['lastmod'] ) $xml .= '<lastmod>'.$item['lastmod'].'</lastmod>'."\n";
            $xml .= '<changefreq>'.$this->changefreq.'</changefreq>'."\n";
            $xml .= '<priority>'.$this->priority.'</priority>'."\n";

            if( $item['alternate'] && count( $item['alternate'] ) >= 1 ) {
                foreach( $item['alternate']  as $lang => $link ) {
                    $xml .= '<xhtml:link rel="alternate" hreflang="'.$lang.'" href="'.$link.'" />'."\n";
                }
            }

        $xml .= '</url>'."\n";

        return $xml;

    }


    public function getSitemap() {
    
        $this->matchQueryString();
        
        if( !$this->type || $this->type && !in_array( $this->type, $this->acceptTypes  ) ) return;
        
        $this->data = $this->type === 'tax' ? $this->getTaxData() : $this->getPostData();

        $xml = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns:xhtml="http://www.w3.org/1999/xhtml"
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";

        foreach( $this->data as $item ) {
            $xml .= $this->xmlBody( $item );
        }

        $xml .= '</urlset>';

        return $xml;
    
    }

}
