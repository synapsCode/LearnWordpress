<?php


/*
 * Plugin Name: Our first plugin
 * Description: Bardzo fajny plugin
 * Version: 1.0
 * AuthorL Wad
 */


class WordCounterPlugin {

    // ta metoda odpala się w momęcie tworzenie instancji
    function __construct(){
        add_action('admin_menu', array($this, 'adminPage'));
    }


    function adminPage(){
        add_options_page("Word Count Plugin", "Word count", "manage_options", "word-count-settings-page", array($this, 'outSettingsPageHtml'));
    }
    
    /**
     * To jest nasza funckcja co wyświetla html w admin panelu. 
     * Narazie z admin panelu
     */
    function outSettingsPageHtml(){
        ?>
            To jest nasz plugin
        <?php
    }
}

new WordCounterPlugin();