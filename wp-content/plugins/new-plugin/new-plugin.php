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
        add_action('admin_init', array($this, 'settings'));
    }


    function adminPage(){
        add_options_page("Word Count Plugin", "Word count", "manage_options", "word-count-settings-page", array($this, 'ourHtml'));
    }
    
    /**
     * To jest nasza funckcja co wyświetla html w admin panelu. 
     * Narazie z admin panelu
     */
    function ourHtml(){
        ?>
            To jest nasz plugin
            <form action="options.php">
                <?php
                    settings_fields('wordcountplugin'); // Jak nie damy tej lini to kliknięcie submt_button() wywali nam kupe błędów
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        <?php
    }

    function settings(){
        add_settings_section('wpc_first_section', null , null , 'word-count-settings-page');
        // Dowiedzieć się co to jest 
        add_settings_field(
            'word_count', 
            'Display location', 
            array($this, 'locationHtml'), 
            'word-count-settings-page', 
            'wpc_first_section' );
        register_setting('wordcountplugin', 'word_count', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '0'
        ));
    }

    function locationHtml(){
        ?>
           <select name="word_count" id="word_count">
            <option value="0">Beginign of post </option>
            <option value="1">End of post</option>
           </select>
        <?php

    }



}

new WordCounterPlugin();