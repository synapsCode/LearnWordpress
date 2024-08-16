<?php


/*
 * Plugin Name: Our first plugin
 * Description: Bardzo fajny plugin
 * Version: 1.0
 * AuthorL Wad
 * Text Domain: word_count_domain
 * Domain Path: /languages
 */


class WordCounterPlugin {

    // ta metoda odpala się w momęcie tworzenie instancji
    
    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
    }

    // TODO: $content jako właściwość obiektu;

    function ifWrap($content){
        if((is_main_query() AND is_single()) AND (get_option('word_count_info', '1') OR get_option('character_count_info', '1') OR get_option('read_time', '1'))){
            return $this->createHTML($content);
        }
        return $content;
    }

    function word_counting($content){
        $statistics_info = '';
        $count_word = '';
        if(get_option('word_count_info', '1') OR get_option('read_time' , '1')){
            $count_word = str_word_count(strip_tags($content));
        }

        if(get_option('word_count_info', '1')){
            $statistics_info = '<strong>'.  __('Liczba słów:', 'word_count_domain') . ': ' . $count_word . '</strong>';
        }

        if(get_option('read_time' , '1')){
            $statistics_info = '<strong> przybliżony czas czytania: ' .'20 min' . '</strong>';
        }

        return $statistics_info;
    }

    function char_counting($content){
        if(get_option('character_count_info', '1')){
            $count_char = strlen(strip_tags($content));
            return '</h3><p>Characters:'  . $count_char;
        }
        return '';
    }

    function createHTML($content){
            //Policz słowa w naszej apce
        $starTime = microtime(true);
        $count_word  = '';


        $html = '<h3>'. get_option('word_count_headline', 'Post statystics: ') . '</h3><p>' . $this->word_counting($content) . $this->char_counting($content) . '</p>';

        $endTime = microtime(true);

        echo ( $endTime - $starTime );
                // W zależności od ustawień modyfikujemy ustawinei od początku lub końca
        if(get_option('word_count', '0') == '0'){
            return $html . $content . 'to jest mirek';
        }
        return $content . $html;
    }

    function adminPage(){
        add_options_page("Word Count Plugin", __("Word count", 'word_count_domain'), "manage_options", "word-count-settings-page", array($this, 'ourHtml'));
    }

    function settings(){
        add_settings_section('wpc_first_section', null , null , 'word-count-settings-page');
        // Word count location
        add_settings_field( 'word_count', 'Display location', array($this, 'locationHtml'), 'word-count-settings-page', 'wpc_first_section' );
        register_setting('wordcountplugin', 'word_count', array('sanitize_callback' => array($this, 'sanitizenLocation'), 'default' => '1'));
        // head line text

        add_settings_field( 'word_count_headline', 'Heading section', array($this, 'headLineHtml'), 'word-count-settings-page', 'wpc_first_section' );
        register_setting('wordcountplugin', 'word_count_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

        //To nasze ile jest slow w naszym blog poscie
        add_settings_field('word_count_info', 'Word count info', array($this, 'checkBOX'), 'word-count-settings-page', 'wpc_first_section', array('theName' => 'word_count_info'));
        register_setting('wordcountplugin', 'word_count_info', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        //Charakter couinter
        add_settings_field('character_count_info', 'Character count info', array($this, 'checkBOX'), 'word-count-settings-page', 'wpc_first_section', array('theName' => 'character_count_info'));
        register_setting('wordcountplugin', 'character_count_info', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        //Read time
        add_settings_field('read_time', 'Read time info', array($this, 'checkBOX'), 'word-count-settings-page', 'wpc_first_section', array('theName' => 'read_time' ) );
        register_setting('wordcountplugin', 'read_time', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    // Sprawdzanie naszych danych
    function sanitizenLocation($input){
        if($input != 0 AND $input != 1){
            add_settings_error('word_count', 'word_count_error', 'Display location must be the beginning or end');
            return get_option('word_count');
        };
        return $input;
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

    function checkBOX($args){
        ?>
            <input type="checkbox" value="1" name="<?php echo $args['theName'] ?>"  <?php checked(get_option($args['theName']), '1') ?> />
        <?php
    }
}

new WordCounterPlugin();