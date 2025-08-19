<?php

if (!defined('ABSPATH')) {
    exit; // Sicherheit: Direkter Zugriff verhindern
}

/**
 * Entfernt WooCommerce-Standardgalerie-Funktionen.
 */
function dbw_customize_woocommerce_gallery() {
    remove_theme_support('wc-product-gallery-slider');
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
}
add_action('after_setup_theme', 'dbw_customize_woocommerce_gallery');

/**
 * Titelbild separat rendern (nur für Responsive)
 */
function dbw_render_product_title_image() {
    global $product;

    if (!$product) return;

    $main_image_id = get_post_thumbnail_id();

    if ($main_image_id) {
        echo '<div class="dbw-product-title-image">';
        echo wp_get_attachment_image($main_image_id, 'full', false, array(
            'class' => 'dbw-main-image',
            'data-main-image' => 'true'
        ));
        echo '</div>';
    }
}
add_action('woocommerce_before_single_product_summary', 'dbw_render_product_title_image', 10);

/**
 * Eigene WooCommerce-Bildergalerie: Hauptbild als erstes Bild der Galerie (Desktop) - Zurück zur ursprünglichen Struktur
 */
function dbw_custom_product_gallery() {
    global $product;

    if (!$product) return;

    $main_image_id = get_post_thumbnail_id();
    $attachment_ids = $product->get_gallery_image_ids();

    if (!$main_image_id && empty($attachment_ids)) return;

    // Einfache Struktur wie ursprünglich - OHNE WooCommerce-Klassen
    echo '<div class="dbw-product-gallery">';

    // Hauptbild
    if ($main_image_id) {
        echo '<div class="dbw-product-image">';
        echo wp_get_attachment_image($main_image_id, 'full', false, array(
            'class' => 'dbw-main-image',
            'data-main-image' => 'true'
        ));
        echo '</div>';
    }

    // Galerie-Bilder
    if (!empty($attachment_ids)) {
        foreach ($attachment_ids as $attachment_id) {
            echo '<div class="dbw-product-image">';
            echo wp_get_attachment_image($attachment_id, 'full');
            echo '</div>';
        }
    }

    echo '</div>';
}

/**
 * WooCommerce Galerie überschreiben
 */
function dbw_override_wc_gallery() {
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);
    add_action('woocommerce_before_single_product_summary', 'dbw_custom_product_gallery', 30);
}
add_action('wp', 'dbw_override_wc_gallery');

/**
 * Stellt sicher, dass WooCommerce Varianten-JavaScript geladen wird
 */
function dbw_enqueue_variation_scripts() {
    if (is_singular('product')) {
        // Korrekte Art das Produkt-Objekt zu erhalten
        $product = wc_get_product(get_the_ID());
        
        if ($product && $product->is_type('variable')) {
            // WooCommerce Varianten-Script laden
            wp_enqueue_script('wc-add-to-cart-variation');
            
            // Zusätzliche Varianten-Daten für JavaScript verfügbar machen
            wp_localize_script('wc-add-to-cart-variation', 'dbw_variation_params', array(
                'gallery_selector' => '.dbw-product-gallery',
                'main_image_selector' => '.dbw-main-image'
            ));
        }
    }
}
add_action('wp_enqueue_scripts', 'dbw_enqueue_variation_scripts');

/**
 * Custom JavaScript für Varianten-Bildwechsel - Vereinfacht für ursprüngliche Struktur
 */
function dbw_add_variation_image_script() {
    if (is_singular('product')) {
        $product = wc_get_product(get_the_ID());
        if ($product && $product->is_type('variable')) {
            ?>
            <script>
            jQuery(document).ready(function($) {
                console.log('DBW Variation Script loaded - Simple Version');
                
                // Alle relevanten Bild-Elemente finden (nur unsere eigenen Klassen)
                var $mainImages = $('.dbw-main-image'); // Hauptbilder
                var $titleImages = $('.dbw-product-title-image img'); // Mobile Titelbild
                
                // Backup der ursprünglichen Bild-URLs
                var originalMainSrc = $mainImages.first().attr('src');
                var originalSrcset = $mainImages.first().attr('srcset') || '';
                var originalSizes = $mainImages.first().attr('sizes') || '';
                
                console.log('Original image:', originalMainSrc);
                
                // Event Listener für Varianten-Änderungen
                $('form.variations_form').on('found_variation', function(event, variation) {
                    console.log('Variation found:', variation);
                    
                    if (variation.image && variation.image.src && variation.image.src !== '') {
                        console.log('Changing to variation image:', variation.image.src);
                        
                        // Alle Hauptbilder ändern (Desktop + Mobile)
                        $mainImages.each(function() {
                            $(this).attr('src', variation.image.src);
                            if (variation.image.srcset) {
                                $(this).attr('srcset', variation.image.srcset);
                            }
                            if (variation.image.sizes) {
                                $(this).attr('sizes', variation.image.sizes);
                            }
                            if (variation.image.alt) {
                                $(this).attr('alt', variation.image.alt);
                            }
                        });
                        
                        // Auch das separate Titelbild ändern (Mobile)
                        $titleImages.each(function() {
                            $(this).attr('src', variation.image.src);
                            if (variation.image.srcset) {
                                $(this).attr('srcset', variation.image.srcset);
                            }
                            if (variation.image.alt) {
                                $(this).attr('alt', variation.image.alt);
                            }
                        });
                        
                    } else {
                        console.log('No variation image found, keeping original');
                    }
                });
                
                // Zurücksetzen wenn keine Variante ausgewählt oder cleared
                $('form.variations_form').on('reset_data', function() {
                    console.log('Resetting to original image');
                    
                    // Zurück zum ursprünglichen Bild
                    $mainImages.each(function() {
                        $(this).attr('src', originalMainSrc);
                        $(this).attr('srcset', originalSrcset);
                        $(this).attr('sizes', originalSizes);
                    });
                    
                    $titleImages.each(function() {
                        $(this).attr('src', originalMainSrc);
                        $(this).attr('srcset', originalSrcset);
                    });
                });
                
                // Zusätzlicher Event-Listener für Varianten-Clear
                $('.reset_variations').on('click', function() {
                    setTimeout(function() {
                        $mainImages.each(function() {
                            $(this).attr('src', originalMainSrc);
                            $(this).attr('srcset', originalSrcset);
                            $(this).attr('sizes', originalSizes);
                        });
                        
                        $titleImages.each(function() {
                            $(this).attr('src', originalMainSrc);
                            $(this).attr('srcset', originalSrcset);
                        });
                    }, 100);
                });
            });
            </script>
            <?php
        }
    }
}
add_action('wp_footer', 'dbw_add_variation_image_script');

/**
 * Fügt dem <article>-Container auf Produktseiten eine individuelle Klasse hinzu.
 */
function dbw_add_custom_product_class($classes, $class, $post_id) {
    if (is_singular('product')) {
        $classes[] = 'dbw-custom-product-container';
    }
    return $classes;
}
add_filter('post_class', 'dbw_add_custom_product_class', 10, 3);

/**
 * Entfernt die zusätzliche Klasse aus WooCommerce-eigenen Containern.
 */
function dbw_remove_extra_product_class($classes) {
    if (is_singular('product')) {
        $classes = array_diff($classes, ['dbw-custom-product-container']);
    }
    return $classes;
}
add_filter('woocommerce_post_class', 'dbw_remove_extra_product_class');

/**
 * Entfernt die "Ähnlichen Produkte" auf der Produktseite.
 */
function dbw_remove_related_products_section() {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}
add_action('wp', 'dbw_remove_related_products_section');

/**
 * Entfernt das WooCommerce-Tab-Element vollständig.
 */
function dbw_remove_woocommerce_tabs($tabs) {
    return array(); // Gibt ein leeres Array zurück, entfernt ALLE Tabs
}
add_filter('woocommerce_product_tabs', 'dbw_remove_woocommerce_tabs', 98);

/**
 * Fügt die Produktbeschreibung & Attribute direkt in den Hauptcontainer ein.
 */
function dbw_display_product_description_in_main_container() {
    global $post, $product;

    // Beschreibung abrufen
    $description = apply_filters('the_content', get_the_content());

    // ACF-Felder abrufen
    $custom_text = get_field('custom_product_text', $post->ID);
    $custom_image = get_field('custom_product_image', $post->ID);

    // Produktattribute abrufen
    $attributes = $product->get_attributes();

    // Container öffnen
    echo '<div class="dbw-product-info-container">';

    // Produktbeschreibung (falls vorhanden)
    if (!empty($description)) {
        echo '<div class="dbw-product-description">';
        echo '<h3>Produktbeschreibung</h3>';
        echo $description;
        echo '</div>';
    }

    // Produktattribute (Maße, Material, Farbe)
    if (!empty($attributes)) {
        echo '<div class="dbw-product-attributes">';
        echo '<h3>Produktdetails</h3>';
        echo '<ul>';
        foreach ($attributes as $attribute) {
            $name = wc_attribute_label($attribute->get_name());
            $value = implode(', ', $attribute->get_options());
            echo '<li><strong>' . esc_html($name) . ':</strong> ' . esc_html($value) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }

    // Zusätzliche ACF-Felder (falls gepflegt)
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

    // Container schließen
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'dbw_display_product_description_in_main_container', 20);

/**
 * Fügt das Label "Menge" direkt vor das Mengenfeld hinzu.
 */
function dbw_add_quantity_label_before_input() {
    echo '<label class="dbw-quantity-label" for="quantity">' . __('Menge', 'woocommerce') . '</label>';
}
add_action('woocommerce_before_add_to_cart_quantity', 'dbw_add_quantity_label_before_input');

/**
 * Entfernt WooCommerce-Breadcrumbs (optional).
 */
function dbw_remove_woocommerce_breadcrumbs() {
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
}
add_action('init', 'dbw_remove_woocommerce_breadcrumbs');

?>