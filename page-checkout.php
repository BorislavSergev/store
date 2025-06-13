<?php
/**
 * Template Name: Checkout Page
 * 
 * The template for displaying the checkout page.
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

get_header();

// Check if WooCommerce is active
if (class_exists('WooCommerce')) {
    // Get the WooCommerce checkout page ID
    $checkout_page_id = wc_get_page_id('checkout');
    
    // If the checkout page exists, display it
    if ($checkout_page_id > 0) {
        echo do_shortcode('[woocommerce_checkout]');
    } else {
        echo '<div class="container py-5"><div class="alert alert-warning">Checkout page not found. Please set up your WooCommerce pages.</div></div>';
    }
} else {
    echo '<div class="container py-5"><div class="alert alert-warning">WooCommerce is not active. Please activate WooCommerce to use this template.</div></div>';
}

get_footer();
?> 