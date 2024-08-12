<?php


/*
 * Plugin Name: Our first plugin
 * Description: Bardzo fajny plugin
 * Version: 1.0
 * AuthorL Wad
 */

add_filter('the_content', 'addToEndOfPosts');

function  addToEndOfPosts($content)
{
    if(is_single() && is_main_query()){
        return $content . "<h2>wad jest the best</h2>";
    }

}

