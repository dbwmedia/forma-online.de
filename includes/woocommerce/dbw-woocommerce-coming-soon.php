<?php
/**
 * Coming Soon Funktionalität für WooCommerce
 * Zeigt alle Varianten an, ersetzt aber den Warenkorb-Button mit einem Coming Soon-Hinweis
 */

/**
 * Prüft, ob ein Produkt in der "Coming Soon"-Kategorie ist
 */
function dbw_is_product_coming_soon($product_id = null) {
    // Wenn keine produkt_id übergeben wurde, versuche die globale zu verwenden
    if ($product_id === null) {
        global $product;
        
        if (!$product || !is_object($product)) {
            return false;
        }
        
        $product_id = $product->get_id();
    }
    
    // Prüfen, ob das Produkt in der Coming Soon-Kategorie ist
    return has_term('coming-soon', 'product_cat', $product_id);
}

/**
 * Fügt das Coming Soon Label zu Produkten in der Übersicht hinzu
 */
function add_coming_soon_label_to_products() {
    global $product;
    if ($product && dbw_is_product_coming_soon($product->get_id())) {
        echo '<span class="coming-soon-label">Coming Soon</span>';
    }
}
// Auf der Produktübersicht
add_action('woocommerce_before_shop_loop_item_title', 'add_coming_soon_label_to_products', 10);

/**
 * Fügt das Coming Soon Label zu Produkten auf der Detailseite hinzu
 */
function add_coming_soon_label_to_single_product() {
    global $product;
    if ($product && dbw_is_product_coming_soon($product->get_id())) {
        echo '<span class="coming-soon-label">Coming Soon</span>';
    }
}
// Auf der Produktdetailseite über dem Titel
add_action('woocommerce_single_product_summary', 'add_coming_soon_label_to_single_product', 4);

/**
 * Ersetzt den "In den Warenkorb"-Button durch einen "Coming Soon"-Hinweis
 */
function dbw_modify_add_to_cart_button() {
    global $product;
    
    if (!$product || !is_object($product)) {
        return;
    }
    
    // Prüfen, ob das Produkt als "Coming Soon" markiert ist
    if (dbw_is_product_coming_soon($product->get_id())) {
        // Entferne standardmäßigen "In den Warenkorb"-Button
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        
        // Füge Coming Soon-Hinweise hinzu
        add_action('woocommerce_single_product_summary', 'dbw_display_coming_soon_text', 30);
        add_action('woocommerce_single_product_summary', 'dbw_display_coming_soon_contact', 31);
        
        // Falls es ein variables Produkt ist, zeigen wir die Varianten an
        if ($product->is_type('variable')) {
            add_action('woocommerce_single_product_summary', 'dbw_display_product_variations', 32);
        }
    }
}
// Dieser Hook wird ausgeführt, wenn die Produktseite geladen wird
add_action('woocommerce_before_single_product_summary', 'dbw_modify_add_to_cart_button', 5);

/**
 * Zeigt den ersten Coming Soon-Text an
 */
function dbw_display_coming_soon_text() {
    echo '<p class="wc-coming-soon-notice">Dieses Produkt ist bald verfügbar.</p>';
}

/**
 * Zeigt den Coming Soon Kontakt-Text mit Popup-Trigger an
 */
function dbw_display_coming_soon_contact() {
    echo '<p class="coming-soon-contact">Für Vorbestellungen melde dich gerne <span class="popup-trigger product-contact-trigger"><a href="#" title="data-popup="kontakt"">hier.</a></span></p>';
}

/**
 * Zeigt die Produktvarianten an
 */
function dbw_display_product_variations() {
    global $product;
    
    if (!$product || !is_object($product)) {
        return;
    }
    
    $variations = $product->get_available_variations();
    
    if (!empty($variations)) {
        echo '<div class="dbw-variations-info">';
        echo '<h3>' . __('Verfügbare Varianten', 'woocommerce') . '</h3>';
        
        // Attribute holen, die für Variationen verwendet werden
        $attributes = $product->get_variation_attributes();
        
        if (!empty($attributes)) {
            echo '<ul class="dbw-variations-list">';
            foreach ($attributes as $attribute_name => $options) {
                // Attributname formatieren
                $attribute_label = wc_attribute_label($attribute_name);
                
                echo '<li class="dbw-variation-attribute">';
                echo '<strong>' . esc_html($attribute_label) . ': </strong>';
                echo '<span>' . esc_html(implode(', ', $options)) . '</span>';
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '</div>';
    }
}


/**
 * Fügt JavaScript hinzu, um auch die Coming Soon Popup-Trigger zu unterstützen
 */
function dbw_add_coming_soon_popup_script() {
    if (is_product() && dbw_is_product_coming_soon()) {
?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Alle Popup-Trigger selektieren, nicht nur den ersten
    const popupTriggers = document.querySelectorAll(".popup-trigger");
    const popup = document.getElementById("contact-popup");
    const closeBtn = document.querySelector(".close-popup");

    if (!popupTriggers.length || !popup || !closeBtn) {
        return;
    }

    // Für jeden Trigger einen Event-Listener hinzufügen
    popupTriggers.forEach(trigger => {
        trigger.addEventListener("click", (event) => {
            event.preventDefault();
            popup.classList.add("open");
        });
    });

    // Schließen des Popups bei Klick auf den Schließen-Button oder außerhalb des Popups
    popup.addEventListener("click", (event) => {
        if (event.target === popup || event.target.closest(".close-popup")) {
            popup.classList.remove("open");
        }
    });
});
</script>
<?php
    }
}
add_action('wp_footer', 'dbw_add_coming_soon_popup_script');