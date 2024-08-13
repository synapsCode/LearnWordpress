Funkcja `register_setting()` w WordPressie jest używana do rejestrowania ustawień w panelu administracyjnym. Umożliwia ona przechowywanie i zarządzanie danymi konfiguracyjnymi w bazie danych, które mogą być następnie edytowane przez administratora z poziomu strony ustawień w panelu administracyjnym.

### Podstawowe informacje o `register_setting()`

#### Składnia
```php
register_setting( $option_group, $option_name, $args );
```

#### Argumenty
- **`$option_group`** (string) – Nazwa grupy opcji. Jest to identyfikator, który łączy polecenie `register_setting()` z odpowiednim formularzem ustawień za pomocą funkcji [[settings_fields()]] w HTML.
- **`$option_name`** (string) – Nazwa opcji, która zostanie zapisana w bazie danych w tabeli `wp_options`. To identyfikator, pod którym dane będą przechowywane.
- **`$args`** (opcjonalny, array lub string) – Tablica z dodatkowymi argumentami, które mogą definiować np. walidację, sanitację danych, typ danych itp. Może być również funkcją zwrotną.

#### Najważniejsze kluczowe elementy w `$args`:
- **`type`** – Typ danych, które będą przechowywane. Domyślnie jest to `string`. Inne opcje to `boolean`, `integer`, `number`, `array`.
- **`description`** – Opis opcji.
- **`sanitize_callback`** – Funkcja zwrotna, która zostanie użyta do przetworzenia lub walidacji danych przed zapisaniem.
- **`show_in_rest`** – Ustawienie, czy opcja ma być dostępna przez REST API. Może przyjmować wartości `true`, `false`, lub bardziej szczegółowe parametry konfiguracyjne.

### Przykład użycia `register_setting()`

Załóżmy, że tworzymy stronę ustawień dla wtyczki, która ma opcję przechowywania wiadomości powitalnej wyświetlanej na stronie głównej.

#### 1. **Rejestrowanie ustawienia**
```php
function myplugin_register_settings() {
    register_setting( 'myplugin_options_group', 'myplugin_welcome_message', array(
        'type' => 'string',
        'description' => 'Wiadomość powitalna wyświetlana na stronie głównej.',
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest' => true,
    ) );
}
add_action( 'admin_init', 'myplugin_register_settings' );
```

- **Grupa opcji**: `myplugin_options_group` - to identyfikator grupy, który zostanie później użyty do powiązania formularza HTML z tym ustawieniem.
- **Nazwa opcji**: `myplugin_welcome_message` - to klucz, pod którym wartość będzie zapisywana w bazie danych.
- **Sanitacja**: `sanitize_text_field` - funkcja, która usuwa z danych niebezpieczne znaki przed zapisaniem ich do bazy danych.
- **REST API**: Ustawienie `show_in_rest` na `true` umożliwia dostęp do tego ustawienia za pośrednictwem WordPress REST API.

#### 2. **Tworzenie strony ustawień**
Następnie musimy stworzyć stronę ustawień, gdzie administrator będzie mógł edytować wartość tej opcji.

```php
function myplugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Ustawienia Wtyczki</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields( 'myplugin_options_group' );
                do_settings_sections( 'myplugin' );
                ?>
            <table class="form-table">
                <tr valign="top">
                <th scope="row">Wiadomość powitalna:</th>
                <td><input type="text" name="myplugin_welcome_message" value="<?php echo esc_attr( get_option('myplugin_welcome_message') ); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function myplugin_add_menu() {
    add_options_page( 'Ustawienia Wtyczki', 'Moja Wtyczka', 'manage_options', 'myplugin', 'myplugin_settings_page' );
}
add_action( 'admin_menu', 'myplugin_add_menu' );
```

### Kroki:
1. **settings_fields( 'myplugin_options_group' )** – Generuje pola ukryte dla zabezpieczeń oraz łączy formularz z grupą opcji `myplugin_options_group`.
2. **do_settings_sections( 'myplugin' )** – Dodaje sekcje i pola, jeśli zostały zdefiniowane (w tym przykładzie nie są).
3. **submit_button()** – Generuje przycisk "Zapisz zmiany".

### Podsumowanie
`register_setting()` jest kluczową funkcją dla tworzenia stron ustawień w WordPressie. Dzięki niej możesz rejestrować opcje, które zostaną przechowywane w bazie danych, a następnie edytowane przez administratorów w panelu administracyjnym. Umożliwia również zastosowanie walidacji i sanitacji danych oraz integrację z REST API, co jest bardzo przydatne w nowoczesnych aplikacjach opartych na WordPressie.