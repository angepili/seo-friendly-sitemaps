
# SEO Friendly Sitemap for Wordpress

A Plugin that help to create a Sitemap Google SEO Friendly, with a simple yaml file for configurations.

To initialize:
~~~bash
$ cd wp-content/plugins
$ git clone https://github.com/angepili/seo-friendly-sitemaps
$ composer install
~~~

Features:
- simple yaml where add post type and taxonomy
- WPML compatible whit href alternate attribute for Multilingual

YAML Config example
~~~yml
nodes:
  notizie: # a beauty slug for URLs
    type: post_type
    post_type: post,
    posts_per_page: -1
    post_parent: 0,
    order: DESC
    changefreq: weekly
    priority: 0.6
  pagine:
    type: post_type
    post_type: page
    multilang: true
    changefreq: daily
    priority: 1
  tag:
    type: tax
    taxonomy: post_tag
    hide_empty: false
    changefreq: monthly
    priority: 0.8
~~~

Just add a beauty slug for URL, priority and changefreq, and all data that you want as args for WP_Query