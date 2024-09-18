<?php

/*
 * Plugin Name: Our Word filter plugin
 * Description: Replace list of words
 * Version: 1.0
 * AuthorL Wad 2
 * Text Domain: word_count_domain
 * Domain Path: /languages
 */

if(!defined('ABSPATH')) exit;




class OurWordFilter {

  function __construct(){
    add_action('admin_menu', array($this, 'ourMenu'));
  }

  function ourMenu(){
    add_menu_page(
      'Words To filter', 
      'Word Filter', 
      'manage_options', 
      'our_word_filter', 
      array($this, 'wordFilterRage'),
      'dashicons-smiley',
      100
    );
    add_submenu_page('our_word_filter','Word filter options', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionSubPage'));
    add_submenu_page('our_word_filter','Word filter options', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionSubPage'));
  }

  function wordFilterRage(){?>
      echo 'to jest elo';
    <?php
  }

  function optionSubPage() {
    return 'to jest sub page';
  }

}


$filterElement = new OurWordFilter();
