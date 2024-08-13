<?php


/*
 * Plugin Name: Our first plugin
 * Description: Bardzo fajny plugin
 * Version: 1.0
 * AuthorL Wad
 */


class WordCounterPlugin {

    // ta metoda odpala się w momęcie tworzenie instancji
    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
      }

    function adminPage(){
        add_options_page("Word Count Plugin", "Word count", "manage_options", "word-count-settings-page", array($this, 'ourHtml'));
    }   
    function settings(){
        add_settings_section('wpc_first_section', null , null , 'word-count-settings-page');
        // Word count location
        add_settings_field( 'word_count', 'Display location', array($this, 'locationHtml'), 'word-count-settings-page', 'wpc_first_section' );
        register_setting('wordcountplugin', 'word_count', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));
        // head line text

        add_settings_field( 'word_count_headline', 'Heading section', array($this, 'headLineHtml'), 'word-count-settings-page', 'wpc_first_section' );
        register_setting('wordcountplugin', 'word_count_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

        add_settings_field('word_count_info', 'Info sectin', array($this, 'infoHtml'), 'word-count-settings-page', 'wpc_first_section');
        register_setting('wordcountplugin', 'word_count_info', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Some information'));

    }
    function locationHtml() { ?>
        <select name="word_count">
          <option value="0" <?php selected(get_option('word_count'), 0)  ?>>Beginning of post</option>
          <option value="1" <?php selected(get_option('word_count'), 1)  ?>>End of post</option>
        </select>
      <?php }
    
    /**
     * To jest nasza funckcja co wyświetla html w admin panelu. 
     * Narazie z admin panelu
     */
    function ourHtml(){
        ?>
            To jest nasz plugin
            <form action="options.php" method="POST">
                <?php
                    settings_fields('wordcountplugin'); // Jak nie damy tej lini to kliknięcie submt_button() wywali nam kupe błędów
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        <?php
    }

    function headLineHtml() {
        ?>
           <input type="text" name="word_count_headline" value="<?php echo esc_attr(get_option('word_count_headline')) ?>">
        <?php
    }

    function infoHtml(){
        ?>
            <h2>To jest informacja o poście</h2>
            <input type="text" value="<?php echo esc_attr(get_option('word_count_info'))   ?>" name="word_count_info"/>
        <?php
    }
}

new WordCounterPlugin();