<?php
/**
 * DBW – WooCommerce Produktdetailseite (sauber, ohne Doppeldeklarationen)
 */

if (!defined('ABSPATH')) {
    exit;
}

/* -------------------------------------------------
 *  Galerie-Features von WooCommerce deaktivieren
 * ------------------------------------------------- */
if (!function_exists('dbw_customize_woocommerce_gallery')) {
    function dbw_customize_woocommerce_gallery() {
        remove_theme_support('wc-product-gallery-slider');
        remove_theme_support('wc-product-gallery-zoom');
        remove_theme_support('wc-product-gallery-lightbox');
    }
    add_action('after_setup_theme', 'dbw_customize_woocommerce_gallery');
}

/* -------------------------------------------------
 *  Titelbild (separat – z. B. für Mobile)
 * ------------------------------------------------- */
if (!function_exists('dbw_render_product_title_image')) {
    function dbw_render_product_title_image() {
        if (!is_singular('product')) return;

        $main_image_id = get_post_thumbnail_id();
        if ($main_image_id) {
            echo '<div class="dbw-product-title-image">';
            echo wp_get_attachment_image($main_image_id, 'full', false, array(
                'class'          => 'dbw-main-image',
                'data-main-image'=> 'true'
            ));
            echo '</div>';
        }
    }
    add_action('woocommerce_before_single_product_summary', 'dbw_render_product_title_image', 10);
}

/* -------------------------------------------------
 *  Eigene, sehr einfache Galerie
 * ------------------------------------------------- */
if (!function_exists('dbw_custom_product_gallery')) {
    function dbw_custom_product_gallery() {
        if (!is_singular('product')) return;

        $product        = wc_get_product(get_the_ID());
        if (!$product) return;

        $main_image_id  = get_post_thumbnail_id();
        $attachment_ids = $product->get_gallery_image_ids();

        if (!$main_image_id && empty($attachment_ids)) return;

        echo '<div class="dbw-product-gallery">';

        // Hauptbild (erstes Element)
        if ($main_image_id) {
            echo '<div class="dbw-product-image">';
            echo wp_get_attachment_image($main_image_id, 'full', false, array(
                'class'          => 'dbw-main-image',
                'data-main-image'=> 'true'
            ));
            echo '</div>';
        }

        // Weitere Bilder
        if (!empty($attachment_ids)) {
            foreach ($attachment_ids as $attachment_id) {
                echo '<div class="dbw-product-image">';
                echo wp_get_attachment_image($attachment_id, 'full');
                echo '</div>';
            }
        }

        echo '</div>';
    }
}

/* -------------------------------------------------
 *  WooCommerce-Standard-Galerie ersetzen
 * ------------------------------------------------- */
if (!function_exists('dbw_override_wc_gallery')) {
    function dbw_override_wc_gallery() {
        if (!is_singular('product')) return;

        remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
        remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);
        add_action('woocommerce_before_single_product_summary', 'dbw_custom_product_gallery', 30);
    }
    add_action('wp', 'dbw_override_wc_gallery');
}

/* -------------------------------------------------
 *  Varianten-Skripte laden (nur bei variablem Produkt)
 * ------------------------------------------------- */
if (!function_exists('dbw_enqueue_variation_scripts')) {
    function dbw_enqueue_variation_scripts() {
        if (!is_singular('product')) return;

        $product = wc_get_product(get_the_ID());
        if ($product && $product->is_type('variable')) {
            wp_enqueue_script('wc-add-to-cart-variation');
        }
    }
    add_action('wp_enqueue_scripts', 'dbw_enqueue_variation_scripts');
}

/* -------------------------------------------------
 *  Produktbeschreibung + Attribute in den Hauptcontainer
 * ------------------------------------------------- */
if (!function_exists('dbw_display_product_description_in_main_container')) {
    function dbw_display_product_description_in_main_container() {
        if (!is_singular('product')) return;

        global $post, $product;

        $description  = apply_filters('the_content', get_the_content());
        $attributes   = $product ? $product->get_attributes() : array();
        $custom_text  = function_exists('get_field') ? get_field('custom_product_text', $post->ID) : '';
        $custom_image = function_exists('get_field') ? get_field('custom_product_image', $post->ID) : '';

        echo '<div class="dbw-product-info-container">';

        if (!empty($description)) {
            echo '<div class="dbw-product-description">';
            echo '<h3>Produktbeschreibung</h3>';
            echo $description;
            echo '</div>';
        }

        if (!empty($attributes)) {
            echo '<div class="dbw-product-attributes">';
            echo '<h3>Produktdetails</h3><ul>';
            foreach ($attributes as $attribute) {
                if (is_object($attribute)) {
                    $name  = wc_attribute_label($attribute->get_name());
                    $value = implode(', ', $attribute->get_options());
                    echo '<li><strong>' . esc_html($name) . ':</strong> ' . esc_html($value) . '</li>';
                }
            }
            echo '</ul></div>';
        }

        if ($custom_text || $custom_image) {
            echo '<div class="dbw-custom-product-fields">';
            if ($custom_text) {
                echo '<p class="custom-product-text">' . esc_html($custom_text) . '</p>';
            }
            if ($custom_image) {
                echo '<img src="' . esc_url($custom_image) . '" alt="" class="custom-product-image">';
            }
            echo '</div>';
        }

        echo '</div>';
    }
    add_action('woocommerce_single_product_summary', 'dbw_display_product_description_in_main_container', 20);
}

/* -------------------------------------------------
 *  Menge-Label vor Eingabefeld
 * ------------------------------------------------- */
if (!function_exists('dbw_add_quantity_label_before_input')) {
    function dbw_add_quantity_label_before_input() {
        echo '<label class="dbw-quantity-label" for="quantity">' . esc_html__('Menge', 'woocommerce') . '</label>';
    }
    add_action('woocommerce_before_add_to_cart_quantity', 'dbw_add_quantity_label_before_input');
}

/* -------------------------------------------------
 *  Breadcrumbs + Tabs + Related entfernen
 * ------------------------------------------------- */
if (!function_exists('dbw_remove_woocommerce_breadcrumbs')) {
    function dbw_remove_woocommerce_breadcrumbs() {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    }
    add_action('init', 'dbw_remove_woocommerce_breadcrumbs');
}

if (!function_exists('dbw_remove_related_products_section')) {
    function dbw_remove_related_products_section() {
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    }
    add_action('wp', 'dbw_remove_related_products_section');
}

if (!function_exists('dbw_remove_woocommerce_tabs')) {
    function dbw_remove_woocommerce_tabs($tabs) {
        return array();
    }
    add_filter('woocommerce_product_tabs', 'dbw_remove_woocommerce_tabs', 98);
}

/* -------------------------------------------------
 *  Frontend-Assets: kleines CSS + JS (Variantenbild + Scroll)
 * ------------------------------------------------- */
if (!function_exists('dbw_output_product_assets')) {
    function dbw_output_product_assets() {
        if (!is_singular('product')) return;

        // CSS lieber hier inline ausgeben, da Theme-Handle unbekannt
        ?>
        <style>
            .dbw-product-gallery {
                scroll-behavior: smooth;
                overscroll-behavior: contain;
            }
            .dbw-product-gallery { scroll-snap-type: y proximity; }
            .dbw-product-gallery .dbw-product-image { scroll-snap-align: start; }
        </style>
        <?php
    }
    add_action('wp_head', 'dbw_output_product_assets', 99);
}

/* -------------------------------------------------
 *  JS: Variantenwechsel + Auto-Scroll
 *  Achtung: neuer Funktionsname → keine Kollisionen
 * ------------------------------------------------- */
if (!function_exists('dbw_output_variation_script')) {
    function dbw_output_variation_script() {
        if (!is_singular('product')) return;

        $product = wc_get_product(get_the_ID());
        if (!$product || !$product->is_type('variable')) return;
        ?>
        <script>
        jQuery(document).ready(function($) {
            var $gallery        = $('.dbw-product-gallery');
            var $mainImgs       = $('.dbw-main-image'); // Desktop + Titelbild
            var $titleImgs      = $('.dbw-product-title-image img');
            var $firstImageWrap = $gallery.find('.dbw-product-image').first();

            // Ursprungswerte sichern, falls Reset/Clear
            var defaults = {
                src:   $mainImgs.first().attr('src')    || '',
                srcset:$mainImgs.first().attr('srcset') || '',
                sizes: $mainImgs.first().attr('sizes')  || ''
            };

            function scrollGalleryTop() {
                if ($gallery.length) {
                    try {
                        $gallery[0].scrollTo({ top: 0, behavior: 'smooth' });
                    } catch (e) {
                        $gallery.stop(true).animate({ scrollTop: 0 }, 250);
                    }
                    if ($firstImageWrap.length) {
                        var containerScrollable = $gallery[0].scrollHeight > $gallery[0].clientHeight;
                        if (!containerScrollable) {
                            $('html, body').stop(true).animate({
                                scrollTop: $firstImageWrap.offset().top - 24
                            }, 250);
                        }
                    }
                }
            }

            function swapToVariationImage(img) {
                if (!img || !img.src) return;

                $mainImgs.each(function() {
                    $(this).attr('src', img.src);
                    if (img.srcset) $(this).attr('srcset', img.srcset);
                    if (img.sizes)  $(this).attr('sizes',  img.sizes);
                    if (img.alt)    $(this).attr('alt',    img.alt);
                });

                $titleImgs.each(function() {
                    $(this).attr('src', img.src);
                    if (img.srcset) $(this).attr('srcset', img.srcset);
                    if (img.alt)    $(this).attr('alt',    img.alt);
                });
            }

            function resetToDefaults() {
                $mainImgs.each(function() {
                    $(this).attr('src', defaults.src)
                           .attr('srcset', defaults.srcset)
                           .attr('sizes', defaults.sizes);
                });
                $titleImgs.each(function() {
                    $(this).attr('src', defaults.src)
                           .attr('srcset', defaults.srcset);
                });
            }

            // Varianten-Events
            var $form = $('form.variations_form');

            $form.on('found_variation', function(e, variation) {
                if (variation && variation.image) {
                    swapToVariationImage(variation.image);
                }
                requestAnimationFrame(scrollGalleryTop);
            });

            $form.on('reset_data', function() {
                resetToDefaults();
                requestAnimationFrame(scrollGalleryTop);
            });

            $('.reset_variations').on('click', function() {
                setTimeout(function() {
                    resetToDefaults();
                    scrollGalleryTop();
                }, 120);
            });
        });
        </script>
        <?php
    }
    add_action('wp_footer', 'dbw_output_variation_script', 99);
}

/* -------------------------------------------------
 *  Klassen anpassen (optionale kosmetik)
 * ------------------------------------------------- */
if (!function_exists('dbw_add_custom_product_class')) {
    function dbw_add_custom_product_class($classes, $class, $post_id) {
        if (is_singular('product')) {
            $classes[] = 'dbw-custom-product-container';
        }
        return $classes;
    }
    add_filter('post_class', 'dbw_add_custom_product_class', 10, 3);
}

if (!function_exists('dbw_remove_extra_product_class')) {
    function dbw_remove_extra_product_class($classes) {
        if (is_singular('product')) {
            $classes = array_diff($classes, array('dbw-custom-product-container'));
        }
        return $classes;
    }
    add_filter('woocommerce_post_class', 'dbw_remove_extra_product_class');
}
