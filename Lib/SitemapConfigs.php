<?php

namespace Lib;
use Symfony\Component\Yaml\Yaml;

class SitemapConfigs {

    public function __construct() {
        $this->config = Yaml::parseFile(  __DIR__.'/../config.yaml' );
    }

    public function getData() {
        return $this->config['nodes'];
    }

    public function getUrlNames() {
        return join('|', array_keys( $this->getData() ) );
    }

}