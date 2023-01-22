<?php
header('Content-type: application/xml; charset=utf-8');
header('Cache-Control: max-age=21600');
header('HTTP/1.1 200 OK');

use Lib\SitemapClass;

$sitemap = ( new SitemapClass( [
    'node' => get_query_var('sitemap')
  ] ) )->getSitemap();

print $sitemap;

// $dom = new \DOMDocument();
// $dom->preserveWhiteSpace = true;
// $dom->formatOutput = true;
// $dom->loadXML( $sitemap );
// print $dom->saveXML();