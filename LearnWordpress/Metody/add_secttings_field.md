
### Składnia
```php
add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() );
```

### Parametry

- **`$id`** (string) – Unikalny identyfikator pola. Będzie używany do identyfikacji pola w kodzie oraz do przypisania wartości w bazie danych.
  
- **`$title`** (string) – Etykieta pola, która będzie wyświetlana na stronie ustawień, obok pola formularza.

- **`$callback`** (callable) – Funkcja zwrotna, która generuje HTML pola ustawień. Ta funkcja jest odpowiedzialna za wyświetlenie formularza (np. input, checkbox, select).

- **`$page`** (string) – Identyfikator strony ustawień, do której pole ma zostać dodane. To powinien być taki sam identyfikator, jaki został użyty przy rejestrowaniu strony za pomocą `add_menu_page()` lub `add_submenu_page()`.

- **`$section`** (opcjonalny, string) – Identyfikator sekcji, do której pole ma zostać dodane. Powinien odpowiadać identyfikatorowi sekcji zdefiniowanemu w `add_settings_section()`. Domyślnie ustawione na `'default'`.

- **`$args`** (opcjonalny, array) – Tablica dodatkowych argumentów, które mogą być przekazane do funkcji zwrotnej `callback`. Można tu przekazać dowolne dane, które pomogą w generowaniu pola.

### Przykład użycia

Załóżmy, że chcemy stworzyć stronę ustawień, na której administrator będzie mógł włączyć lub wyłączyć pewną funkcjonalność wtyczki za pomocą pola checkbox.

#### 1. **Rejestracja ustawień**
```php
function myplugin_register_settings() {
    register_setting( 'myplugin_options_group', 'myplugin_option_checkbox' );
}
add_action( 'admin_init', 'myplugin_register_settings' );
```

#### 2. **Dodanie sekcji i pola ustawień**
```php
function myplugin_settings_init() {
    // Dodanie sekcji ustawień
    add_settings_section(
        'myplugin_main_section',              // ID sekcji
        'Główne Ustawienia Mojej Wtyczki',    // Tytuł sekcji
        'myplugin_section_callback',          // Funkcja wyświetlająca opis sekcji
        'myplugin'                            // Identyfikator strony ustawień
    );
    
    // Dodanie pola ustawień
    add_settings_field(
        'myplugin_option_checkbox',           // ID pola
        'Włącz Funkcję',                      // Etykieta pola
        'myplugin_checkbox_callback',         // Funkcja renderująca pole
        'myplugin',                           // Strona ustawień
        'myplugin_main_section',              // Sekcja ustawień
        array(                                // Dodatkowe argumenty
            'label_for' => 'myplugin_option_checkbox',
            'class' => 'myplugin_row',
        )
    );
}
add_action( 'admin_init', 'myplugin_settings_init' );
```

#### 3. **Callbacki do renderowania pola i opisu sekcji**
```php
// Callback dla sekcji (opis sekcji)
function myplugin_section_callback() {
    echo '<p>Główne ustawienia dla mojej wtyczki.</p>';
}

// Callback dla pola checkbox
function myplugin_checkbox_callback( $args ) {
    // Pobieramy wartość zapisaną w bazie danych
    $option = get_option( 'myplugin_option_checkbox' );

    // Generujemy HTML pola checkbox
    echo '<input type="checkbox" id="' . esc_attr( $args['label_for'] ) . '" name="myplugin_option_checkbox" value="1"' . checked( 1, $option, false ) . '/>';
    echo '<label for="' . esc_attr( $args['label_for'] ) . '"> ' . esc_html( 'Włącz tę funkcję' ) . '</label>';
}
```

#### 4. **Tworzenie strony ustawień**
```php
function myplugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Ustawienia Wtyczki</h1>
        <form method="post" action="options.php">
            <?php
                // Powiązanie formularza z grupą opcji
                settings_fields( 'myplugin_options_group' );

                // Renderowanie sekcji i pól
                do_settings_sections( 'myplugin' );

                // Przyciski do zapisania ustawień
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

function myplugin_add_menu() {
    add_options_page( 'Ustawienia Wtyczki', 'Moja Wtyczka', 'manage_options', 'myplugin', 'myplugin_settings_page' );
}
add_action( 'admin_menu', 'myplugin_add_menu' );
```

### Podsumowanie
Funkcja `add_settings_field()` jest niezbędna do tworzenia własnych pól ustawień na stronach konfiguracji w WordPressie. Pozwala na precyzyjne dodawanie pól formularza, takich jak pola tekstowe, checkboxy, listy rozwijane itp., które mogą być edytowane przez użytkowników z odpowiednimi uprawnieniami w panelu administracyjnym. Dzięki temu możesz tworzyć bardziej złożone i funkcjonalne interfejsy zarządzania dla swoich wtyczek lub motywów.