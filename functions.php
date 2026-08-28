<?php

//------------------------------------------------
// # custom directory paths
// ------------------------------------------------
// Core Includes
require_once get_stylesheet_directory() . '/includes/shortcodes.php';
require_once get_stylesheet_directory() . '/includes/actions.php';
require_once get_stylesheet_directory() . '/includes/filter.php';
require_once get_stylesheet_directory() . '/includes/directories.php';

// dbw media  Specific Includes
require_once get_stylesheet_directory() . '/includes/dbw/login/dbw-login.php';
require_once get_stylesheet_directory() . '/includes/dbw/login/dbw-login-style.php';
require_once get_stylesheet_directory() . '/includes/dbw/dbw-head.php';
//require_once get_stylesheet_directory() . '/includes/cookie.php'; 

// Font Awesome
require_once get_stylesheet_directory() . '/includes/fontawesome-enqueue.php';
 
// WooCommerce Erweiterung
//require_once get_stylesheet_directory() . '/includes/woocommerce/dbw-woocommerce-gutenberg.php';
require_once get_stylesheet_directory() . '/includes/woocommerce/dbw-woocommerce-detailpage.php';
require_once get_stylesheet_directory() . '/includes/woocommerce/dbw-woocommerce-coming-soon.php';

//------------------------------------------------
// # returns meta and favicon
// ------------------------------------------------
function dbw_favicon_meta() {
    ob_start();
    echo '<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">';
    echo '<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">';
    echo '<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">';
    echo '<link rel="manifest" href="/sites.webmanifest">';
    echo '<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#f2354e">';
    echo '<meta name="msapplication-TileColor" content="#333333">';
    echo '<meta name="theme-color" content="#ffffff">';
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}

//------------------------------------------------
// # CF7 
// ------------------------------------------------
function custom_math_captcha_validation($result, $tag) {
    $field_name = $tag->name;

    if ($field_name === 'math-captcha') { 
        $user_input = isset($_POST[$field_name]) ? sanitize_text_field($_POST[$field_name]) : '';
        
        // Debugging-Logik
        if ($user_input !== '7') { 
            $result->invalidate($tag, "Bitte geben Sie die richtige Antwort auf die Rechenaufgabe ein.");
        } else {
            error_log("Math-Captcha ist korrekt: " . $user_input);
        }
    }
    return $result;
}
add_filter('wpcf7_validate_text*', 'custom_math_captcha_validation', 20, 2);


//------------------------------------------------
// # Backende Style
// ------------------------------------------------
function mein_backend_custom_css() {
    echo '<style>
        #adminmenumain table.wp-list-table .column-taxonomy-product_brand {
            width: auto;
        }
        table.wp-list-table .column-taxonomy-product_brand {
            width: auto;
        }
    </style>';
}
add_action('admin_head', 'mein_backend_custom_css');

//------------------------------------------------
// # Maintenance Mode (logged-in admins bypass)
// ------------------------------------------------
function dbw_maintenance_mode() {
    if (current_user_can('manage_options')) return;
    if (is_admin()) return;
    if (defined('DOING_AJAX') && DOING_AJAX) return;
    if (defined('DOING_CRON') && DOING_CRON) return;
    if (strpos($_SERVER['REQUEST_URI'], '/wp-login') !== false) return;

    $file = get_stylesheet_directory() . '/coming-soon.html';
    if (file_exists($file)) {
        http_response_code(503);
        header('Retry-After: 3600');
        readfile($file);
        exit;
    }
}
add_action('template_redirect', 'dbw_maintenance_mode');

