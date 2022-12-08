<?php

namespace Lib\SparklingSitemaps;
// use Symfony\Component\Yaml\Yaml;

class SparklingSitemaps {
    
    // $value = Yaml::parseFile(  __DIR__.'/test.yaml' );

    const POST_TYPE_PAGE        = 'page';
    const POST_TYPE_NEWS        = 'news';
    const POST_TYPE_LANDING     = 'post';
    const POST_TYPE_LIFESTYLE   = 'lifestyle';
    const POST_TYPE_DESIGNER    = 'designer';
    const POST_TYPE_PRODUCTS    = 'products';
    const POST_TYPE_CONTRACTS   = 'contract';

    const TAX_COLLECTIONS       = 'rooms';
    const TAX_TYPOLOGIES        = 'design';

    function __construct( $querVars ) {

    $this->node       = $querVars['nodo'] ?: null;
    $this->tipologia  = $querVars['tipologia'] ?: null;

    $this->acceptTypes = ['tax','post'];

    $this->isWpml = defined('ICL_LANGUAGE_CODE');
    }

    public function matchQueryString() {
    
    if( !$this->node ) return;

    switch( $this->node ) {
    
        case 'contracts' :

            $this->post_type = self::POST_TYPE_CONTRACTS;
            $this->type = 'post';
            $this->changefreq = 'monthly';
            $this->priority = 0.4;

            $this->data = [
                'post_type'       => $this->post_type,
                'post_status'     => 'publish',
                'posts_per_page'  => -1,
                'post_parent'     => 0,
                'order'           => 'DESC',
            ];

            break;

        case 'designer' :

            $this->post_type = self::POST_TYPE_DESIGNER;
            $this->type = 'post';
            $this->changefreq = 'monthly';
            $this->priority = 0.2;

            $this->data = [
                'post_type'       => $this->post_type,
                'post_status'     => 'publish',
                'posts_per_page'  => -1,
                'post_parent'     => 0,
                'order'           => 'DESC',
            ];

            break;

        case 'lifestyle' :

            $this->post_type = self::POST_TYPE_LIFESTYLE;
            $this->type = 'post';
            $this->changefreq = 'monthly';
            $this->priority = 0.4;

            $this->data = [
                'post_type'       => $this->post_type,
                'post_status'     => 'publish',
                'posts_per_page'  => -1,
                'post_parent'     => 0,
                'order'           => 'DESC',
            ];

            break;

        case 'news' :

            $this->post_type = self::POST_TYPE_NEWS;
            $this->type = 'post';
            $this->changefreq = 'weekly';
            $this->priority = 0.6;

            $this->data = [
                'post_type'       => $this->post_type,
                'post_status'     => 'publish',
                'posts_per_page'  => -1,
                'post_parent'     => 0,
                'order'           => 'DESC',
            ];

            break;
        
        case 'landing' :

            $this->post_type = self::POST_TYPE_LANDING;
            $this->type = 'post';
            $this->changefreq = 'weekly';
            $this->priority = 0.8;

            $this->data = [
                'post_type'       => $this->post_type,
                'post_status'     => 'publish',
                'posts_per_page'  => -1,
                'post_parent'     => 0,
                'order'           => 'DESC',
            ];

            break;
        
        case 'prodotti' :

            $this->post_type = self::POST_TYPE_PRODUCTS;
            $this->type = 'post';
            $this->changefreq = 'weekly';
            $this->priority = 1;

            $this->data = [
                'post_type'       => $this->post_type,
                'post_status'     => 'publish',
                'posts_per_page'  => -1,
                'post_parent'     => 0,
                'order'           => 'DESC',
            ];


            break;
        
        case 'pagine' :

            $this->post_type = self::POST_TYPE_PAGE;
            $this->type = 'post';
            $this->changefreq = 'weekly';
            $this->priority = 0.6;

            $this->data = [
                'post_type'       => $this->post_type,
                'post_status'     => 'publish',
                'posts_per_page'  => -1,
                'post_parent'     => 0,
                'order'           => 'DESC',
            ];

            break;

        case 'collections' :

            $this->post_type = self::TAX_COLLECTIONS;
            $this->type = 'tax';
            $this->changefreq = 'monthly';
            $this->priority = 0.8;

            $this->data = [
                'taxonomy'       => $this->post_type,
                'hide_empty'     => true
            ];

            break;

        case 'typologies' :

            $this->post_type = self::TAX_TYPOLOGIES;
            $this->type = 'tax';
            $this->changefreq = 'monthly';
            $this->priority = 0.8;

            $this->data = [
                'taxonomy'       => $this->post_type,
                'hide_empty'     => true
            ];

            break;

        }

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

        $query = new WP_Query( $this->data );

        if( $query->have_posts() ) :
            while($query->have_posts()) :
                $query->the_post();
                global $post;

                $hasPrefix = in_array( $post_type, [ self::POST_TYPE_PRODUCTS, self::POST_TYPE_NEWS, self::POST_TYPE_LIFESTYLE, self::POST_TYPE_DESIGNER ] );
                $prefix = getPostTypeSlug( $this->post_type );
                $url = $hasPrefix ? get_bloginfo('url').'/'.$prefix.'/'.$post->post_name : get_bloginfo('url').$post->post_name;

                $lastmod = get_the_modified_date('Y-m-d\Th:m:s+00:00', $post->ID );

                $urlTranslated = [];

                if( $this->isWpml ) :
                $post_type = 'post_' . get_post_type($post->ID);
                $translations = apply_filters('wpml_get_element_translations', [], apply_filters('wpml_element_trid', false, $post->ID, $post_type), $post_type);      

                unset( $translations[ ICL_LANGUAGE_CODE ] );
                if( $this->data['post_type'] == 'news' ) unset( $translations[ 'zh-hans' ] ); # TODO: da migliorare
                
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

        $xml = '<url>';
            $xml .= '<loc>'.$item['loc'].'</loc>';
            if( $item['lastmod'] ) $xml .= '<lastmod>'.$item['lastmod'].'</lastmod>';
            $xml .= '<changefreq>'.$this->changefreq.'</changefreq>';
            $xml .= '<priority>'.$this->priority.'</priority>';

            if( $item['alternate'] && count( $item['alternate'] ) >= 1 ) {
            foreach( $item['alternate']  as $lang => $link ) {
                $xml .= '<xhtml:link rel="alternate" hreflang="'.$lang.'" href="'.$link.'" />';
            }
            }

        $xml .= '</url>';

        return $xml;

    }


    public function getSitemap() {
    
        $this->matchQueryString();

        if( !$this->type || $this->type && !in_array( $this->type, $this->acceptTypes  ) ) return;

        $this->data = $this->type === 'tax' ? $this->getTaxData() : $this->getPostData();

        $xml = '
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns:xhtml="http://www.w3.org/1999/xhtml"
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        foreach( $this->data as $item ) {
            $xml .= $this->xmlBody( $item );
        }

        $xml .= '</urlset>';

        return $xml;
    
    }

}
