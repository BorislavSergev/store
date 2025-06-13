<?php
/**
 * Review order table
 *
 * This template overrides the WooCommerce checkout review order
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;
?>
<div class="woocommerce-checkout-review-order-table-wrapper">
    <table class="shop_table woocommerce-checkout-review-order-table">
        <thead>
            <tr>
                <th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                <th class="product-total"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            do_action('woocommerce_review_order_before_cart_contents');

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    ?>
                    <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                        <td class="product-name">
                            <div class="product-info">
                                <?php if (has_post_thumbnail($_product->get_id())) : ?>
                                    <div class="product-thumbnail">
                                        <?php echo $_product->get_image('thumbnail'); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="product-thumbnail placeholder">
                                        <i class="fas fa-shoe-prints"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="product-details">
                                    <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?>
                                    <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            </div>
                        </td>
                        <td class="product-total">
                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </td>
                    </tr>
                    <?php
                }
            }

            do_action('woocommerce_review_order_after_cart_contents');
            ?>
        </tbody>
        <tfoot>
            <tr class="cart-subtotal">
                <th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
                <td><?php wc_cart_totals_subtotal_html(); ?></td>
            </tr>

            <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                    <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                    <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                <?php do_action('woocommerce_review_order_before_shipping'); ?>

                <tr class="shipping">
                    <th><?php esc_html_e('Shipping', 'woocommerce'); ?></th>
                    <td><?php wc_cart_totals_shipping_html(); ?></td>
                </tr>

                <?php do_action('woocommerce_review_order_after_shipping'); ?>
            <?php endif; ?>

            <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                <tr class="fee">
                    <th><?php echo esc_html($fee->name); ?></th>
                    <td><?php wc_cart_totals_fee_html($fee); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                    <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                        <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                            <th><?php echo esc_html($tax->label); ?></th>
                            <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="tax-total">
                        <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                        <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>

            <?php do_action('woocommerce_review_order_before_order_total'); ?>

            <tr class="order-total">
                <th><?php esc_html_e('Total', 'woocommerce'); ?></th>
                <td><?php wc_cart_totals_order_total_html(); ?></td>
            </tr>

            <?php do_action('woocommerce_review_order_after_order_total'); ?>
        </tfoot>
    </table>
</div>

<style>
/* Enhanced Order Review Table */
.woocommerce-checkout-review-order-table-wrapper {
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    transition: transform 0.3s, box-shadow 0.3s;
}

.woocommerce-checkout-review-order-table-wrapper:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
}

.woocommerce-checkout-review-order-table {
    width: 100%;
    border-collapse: collapse;
}

.woocommerce-checkout-review-order-table thead th {
    background-color: #f8f4fa;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 15px;
    border-bottom: 1px solid #eee;
    color: #333;
}

.woocommerce-checkout-review-order-table .product-total {
    text-align: right;
}

.woocommerce-checkout-review-order-table tbody td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.woocommerce-checkout-review-order-table .product-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.woocommerce-checkout-review-order-table .product-thumbnail {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
}

.woocommerce-checkout-review-order-table .product-thumbnail:hover {
    transform: scale(1.05);
}

.woocommerce-checkout-review-order-table .product-thumbnail img {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 6px;
}

.woocommerce-checkout-review-order-table .product-thumbnail.placeholder {
    background-color: #f8f4fa;
    color: #8e44ad;
    font-size: 24px;
}

.woocommerce-checkout-review-order-table .product-details {
    flex: 1;
}

.woocommerce-checkout-review-order-table .product-quantity {
    display: inline-block;
    background-color: #f8f4fa;
    color: #8e44ad;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 12px;
    margin-left: 8px;
}

.woocommerce-checkout-review-order-table tfoot th {
    text-align: left;
    padding: 12px 15px;
    font-weight: 500;
    color: #555;
    background-color: #f9f9f9;
}

.woocommerce-checkout-review-order-table tfoot td {
    text-align: right;
    padding: 12px 15px;
    background-color: #f9f9f9;
    font-weight: 500;
}

.woocommerce-checkout-review-order-table .order-total th,
.woocommerce-checkout-review-order-table .order-total td {
    font-size: 18px;
    font-weight: 700;
    padding-top: 20px;
    padding-bottom: 20px;
    color: #333;
    background-color: #f8f4fa;
    border-top: 2px solid rgba(142, 68, 173, 0.2);
}

.woocommerce-checkout-review-order-table .order-total td .amount {
    color: #8e44ad;
    font-size: 20px;
}

.woocommerce-checkout-review-order-table .cart-subtotal {
    background-color: #f9f9f9;
}

.woocommerce-checkout-review-order-table .cart-discount {
    color: #2ecc71;
}

.woocommerce-checkout-review-order-table .cart-discount td .amount {
    color: #2ecc71;
    font-weight: 600;
}

.woocommerce-checkout-review-order-table .cart-discount td .woocommerce-remove-coupon {
    color: #e74c3c;
    margin-left: 5px;
    font-size: 13px;
}

@media (max-width: 768px) {
    .woocommerce-checkout-review-order-table thead th {
        padding: 12px 10px;
        font-size: 14px;
    }
    
    .woocommerce-checkout-review-order-table tbody td {
        padding: 12px 10px;
    }
    
    .woocommerce-checkout-review-order-table .product-thumbnail {
        width: 50px;
        height: 50px;
    }
    
    .woocommerce-checkout-review-order-table .product-info {
        gap: 10px;
    }
    
    .woocommerce-checkout-review-order-table tfoot th,
    .woocommerce-checkout-review-order-table tfoot td {
        padding: 10px;
        font-size: 14px;
    }
    
    .woocommerce-checkout-review-order-table .order-total th,
    .woocommerce-checkout-review-order-table .order-total td {
        font-size: 16px;
        padding: 15px 10px;
    }
    
    .woocommerce-checkout-review-order-table .order-total td .amount {
        font-size: 18px;
    }
}
</style> 