<?php
header('Content-type: application/xml; charset=utf-8');
header('Cache-Control: max-age=21600');
header('HTTP/1.1 200 OK');

use Lib\SparklingSitemaps\SparklingSitemaps;

$sitemap = ( new SparklingSitemaps( [
    'node' => get_query_var('sitemap')
  ] ) )->getSitemap();

  print '<pre>';
  var_dump( $sitemap );
  print '</pre>';
// $dom = new \DOMDocument();
// $dom->preserveWhiteSpace = true;
// $dom->formatOutput = true;
// $dom->loadXML( $sitemap );
// print $dom->saveXML();