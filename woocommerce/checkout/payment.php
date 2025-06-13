<?php
/**
 * Checkout Payment Section
 *
 * This template overrides the WooCommerce checkout payment section
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

if (!is_ajax()) {
    do_action('woocommerce_review_order_before_payment');
}
?>
<div id="payment" class="woocommerce-checkout-payment">
    <?php if (WC()->cart->needs_payment()) : ?>
        <h3 class="payment-methods-title"><?php esc_html_e('Payment method', 'woocommerce'); ?></h3>
        <ul class="wc_payment_methods payment_methods methods">
            <?php
            if (!empty($available_gateways)) {
                foreach ($available_gateways as $gateway) {
                    wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
                }
            } else {
                echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')) . '</li>'; // @codingStandardsIgnoreLine
            }
            ?>
        </ul>
    <?php endif; ?>
    
    <div class="form-row place-order">
        <noscript>
            <?php
            /* translators: $1 and $2 opening and closing emphasis tags respectively */
            printf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'), '<em>', '</em>');
            ?>
            <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
        </noscript>

        <?php wc_get_template('checkout/terms.php'); ?>

        <?php do_action('woocommerce_review_order_before_submit'); ?>

        <?php echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '"><i class="fas fa-lock"></i> ' . esc_html($order_button_text) . '</button>'); // @codingStandardsIgnoreLine ?>

        <?php do_action('woocommerce_review_order_after_submit'); ?>

        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
    </div>
    
    <div class="secure-checkout-badge">
        <i class="fas fa-shield-alt"></i>
        <span>Сигурно и защитено плащане</span>
    </div>
</div>

<style>
/* Enhanced Payment Methods Styling */
.woocommerce-checkout-payment {
    margin-top: 20px;
}

.payment-methods-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: #333;
}

.wc_payment_methods {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
}

.wc_payment_methods li {
    margin-bottom: 15px;
    border: 1px solid #eee;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
    background-color: #fff;
}

.wc_payment_methods li:hover {
    border-color: #8e44ad;
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.08);
}

.wc_payment_methods li.payment_method_paypal img {
    max-height: 30px;
    vertical-align: middle;
    margin-left: 10px;
}

.wc_payment_method > label {
    display: block;
    padding: 15px;
    cursor: pointer;
    position: relative;
    margin: 0;
    font-weight: 500;
    transition: background-color 0.3s;
    color: #333;
}

.wc_payment_method > label:hover {
    background-color: #f9f9f9;
}

.wc_payment_method > input[type="radio"] {
    margin-right: 10px;
}

.wc_payment_method > input[type="radio"]:checked + label {
    background-color: #f8f4fa;
    border-left: 3px solid #8e44ad;
    font-weight: 600;
}

.payment_box {
    padding: 20px;
    background-color: #f9f9f9;
    border-top: 1px solid #eee;
    font-size: 14px;
    line-height: 1.6;
    color: #555;
}

.payment_box p:last-child {
    margin-bottom: 0;
}

/* Place Order Button */
#place_order {
    width: 100%;
    padding: 15px 20px;
    font-size: 16px;
    font-weight: 600;
    background-color: #8e44ad;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

#place_order i {
    font-size: 14px;
}

#place_order::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.7s;
}

#place_order:hover {
    background-color: #7d3c98;
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(142, 68, 173, 0.2);
}

#place_order:hover::before {
    left: 100%;
}

#place_order:active {
    transform: translateY(0);
    box-shadow: 0 4px 8px rgba(142, 68, 173, 0.2);
}

/* Terms and Conditions */
.woocommerce-terms-and-conditions-wrapper {
    margin-bottom: 20px;
    font-size: 14px;
    color: #555;
    line-height: 1.5;
}

.woocommerce-privacy-policy-text {
    margin-bottom: 15px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #eee;
}

.woocommerce-terms-and-conditions-checkbox-text {
    padding-left: 5px;
}

/* Secure Checkout Badge */
.secure-checkout-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px 0 10px;
    color: #666;
    font-size: 14px;
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #eee;
    gap: 10px;
}

.secure-checkout-badge i {
    color: #8e44ad;
    font-size: 16px;
}

@media (max-width: 768px) {
    .wc_payment_methods li {
        margin-bottom: 10px;
    }
    
    .wc_payment_method > label {
        padding: 12px;
        font-size: 14px;
    }
    
    .payment_box {
        padding: 15px;
        font-size: 13px;
    }
    
    #place_order {
        padding: 12px 15px;
        font-size: 15px;
    }
}
</style>

<?php
if (!is_ajax()) {
    do_action('woocommerce_review_order_after_payment');
}
?> 