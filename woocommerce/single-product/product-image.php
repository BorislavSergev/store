<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

global $product;

$columns           = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
        'woocommerce-product-gallery--columns-' . absint($columns),
        'images',
    )
);
?>

<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>">
    <?php if ($product->is_on_sale()) : ?>
        <span class="onsale-badge"><?php echo esc_html__('Sale!', 'shoes-store'); ?></span>
    <?php endif; ?>
    
    <button type="button" class="product-wishlist-button">
        <i class="far fa-heart"></i>
    </button>
    
    <div class="product-image-main">
        <div class="woocommerce-product-gallery__image">
            <?php
            if ($post_thumbnail_id) {
                $html = wc_get_gallery_image_html($post_thumbnail_id, true);
            } else {
                $html = '<img src="' . esc_url(wc_placeholder_img_src('woocommerce_single')) . '" alt="' . esc_attr__('Placeholder', 'shoes-store') . '" />';
            }
            
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id);
            ?>
        </div>
    </div>
    
    <?php
    // Get gallery images
    $attachment_ids = $product->get_gallery_image_ids();
    
    if ($attachment_ids && $post_thumbnail_id) {
        // Prepend main image to gallery
        array_unshift($attachment_ids, $post_thumbnail_id);
        
        echo '<div class="product-thumbnails">';
        echo '<div class="thumbnails-inner">';
        
        foreach ($attachment_ids as $index => $attachment_id) {
            $full_size_image_url = wp_get_attachment_url($attachment_id);
            $thumbnail = wp_get_attachment_image_src($attachment_id, 'thumbnail');
            
            if (!$thumbnail) {
                continue;
            }
            
            $active_class = ($index === 0) ? ' active' : '';
            
            echo '<div class="thumbnail' . esc_attr($active_class) . '" data-full-image="' . esc_url($full_size_image_url) . '">';
            echo wp_get_attachment_image($attachment_id, 'thumbnail');
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>

<script>
    // Simple image gallery script
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.thumbnail');
        const mainImage = document.querySelector('.woocommerce-product-gallery__image img');
        
        if (thumbnails.length > 0 && mainImage) {
            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Update main image
                    mainImage.src = this.getAttribute('data-full-image');
                    
                    // Update active class
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }
    });
</script> 