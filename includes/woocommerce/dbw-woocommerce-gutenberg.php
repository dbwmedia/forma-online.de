<?php
/**
 * WooCommerce Gutenberg Integration
 * 
 * Aktiviert Gutenberg für Produkte und erlaubt dynamische Daten.
 */

// Gutenberg für WooCommerce-Produkte aktivieren
function dbw_activate_gutenberg_for_products( $can_edit, $post_type ) {
    if ( $post_type === 'product' ) {
        $can_edit = true;
    }
    return $can_edit;
}
add_filter( 'use_block_editor_for_post_type', 'dbw_activate_gutenberg_for_products', 10, 2 );

// REST-API für WooCommerce-Taxonomien aktivieren (Produktkategorien und -Tags)
function dbw_enable_woocommerce_taxonomy_rest( $args ) {
    $args['show_in_rest'] = true;
    return $args;
}
add_filter( 'woocommerce_taxonomy_args_product_cat', 'dbw_enable_woocommerce_taxonomy_rest' );
add_filter( 'woocommerce_taxonomy_args_product_tag', 'dbw_enable_woocommerce_taxonomy_rest' );

// ACF-Unterstützung für WooCommerce-Produkte aktivieren
function dbw_register_acf_fields() {
    if( function_exists('acf_add_local_field_group') ) {
        acf_add_local_field_group(array(
            'key' => 'group_woocommerce_custom_fields',
            'title' => 'Produkt Zusatzinfos',
            'fields' => array(
                array(
                    'key' => 'field_custom_text',
                    'label' => 'Zusätzlicher Text',
                    'name' => 'custom_product_text',
                    'type' => 'text',
                    'instructions' => 'Gib eine zusätzliche Produktinfo ein.',
                ),
                array(
                    'key' => 'field_custom_image',
                    'label' => 'Zusätzliches Bild',
                    'name' => 'custom_product_image',
                    'type' => 'image',
                    'return_format' => 'url',
                    'instructions' => 'Lade ein zusätzliches Produktbild hoch.',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'product',
                    ),
                ),
            ),
        ));
    }
}
add_action('acf/init', 'dbw_register_acf_fields');

// Funktion zum Anzeigen der ACF-Felder im Produkt-Template
function dbw_display_custom_product_fields() {
    global $post;
    
    // Daten abrufen
    $custom_text = get_field('custom_product_text', $post->ID);
    $custom_image = get_field('custom_product_image', $post->ID);

    // Ausgabe nur anzeigen, wenn Werte vorhanden sind
    if ($custom_text || $custom_image) {
        echo '<div class="dbw-custom-product-fields">';
        if ($custom_text) {
            echo '<p class="custom-product-text">' . esc_html($custom_text) . '</p>';
        }
        if ($custom_image) {
            echo '<img src="' . esc_url($custom_image) . '" alt="Zusätzliches Produktbild" class="custom-product-image">';
        }
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'dbw_display_custom_product_fields', 25);