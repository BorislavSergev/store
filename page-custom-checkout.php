<?php
/**
 * Template Name: Custom Checkout
 *
 * A custom checkout page template that collects data and sends it to WooCommerce
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

get_header();

// Check if cart is not empty
if (WC()->cart->is_empty()) {
    wc_add_notice(__('Your cart is currently empty.', 'woocommerce'), 'notice');
    wp_redirect(wc_get_page_permalink('shop'));
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_checkout_submit'])) {
    // Verify nonce
    if (!isset($_POST['custom_checkout_nonce']) || !wp_verify_nonce($_POST['custom_checkout_nonce'], 'custom_checkout')) {
        wc_add_notice(__('Security check failed. Please try again.', 'woocommerce'), 'error');
    } else {
        // Get form data
        $customer_data = array(
            'first_name' => sanitize_text_field($_POST['customer_first_name'] ?? ''),
            'last_name' => sanitize_text_field($_POST['customer_last_name'] ?? ''),
            'email' => sanitize_email($_POST['customer_email'] ?? ''),
            'phone' => sanitize_text_field($_POST['customer_phone'] ?? ''),
            'address' => sanitize_textarea_field($_POST['customer_address'] ?? ''),
            'city' => sanitize_text_field($_POST['customer_city'] ?? ''),
            'postcode' => sanitize_text_field($_POST['customer_postcode'] ?? ''),
            'order_notes' => sanitize_textarea_field($_POST['order_notes'] ?? '')
        );

        // Store customer data in session
        WC()->session->set('custom_checkout_data', $customer_data);
        
        // Create a new order
        $order = wc_create_order();
        
        // Add cart items to order
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $variation_id = $cart_item['variation_id'] ?? 0;
            $variation = $cart_item['variation'] ?? array();
            
            $order->add_product(wc_get_product($product_id), $quantity, array(
                'variation_id' => $variation_id,
                'variation' => $variation
            ));
        }
        
        // Add shipping if applicable
        if (isset($_POST['shipping_method']) && !empty($_POST['shipping_method'])) {
            $shipping_method = sanitize_text_field($_POST['shipping_method']);
            $shipping_methods = WC()->shipping->get_shipping_methods();
            
            if (isset($shipping_methods[$shipping_method])) {
                $method = $shipping_methods[$shipping_method];
                $rate = new WC_Shipping_Rate(
                    $method->id,
                    $method->method_title,
                    $method->cost,
                    array(),
                    $method->id
                );
                $order->add_shipping($rate);
            }
        }
        
        // Set address
        $address = array(
            'first_name' => $customer_data['first_name'],
            'last_name'  => $customer_data['last_name'],
            'email'      => $customer_data['email'],
            'phone'      => $customer_data['phone'],
            'address_1'  => $customer_data['address'],
            'city'       => $customer_data['city'],
            'postcode'   => $customer_data['postcode'],
            'country'    => 'BG' // Bulgaria
        );
        
        $order->set_address($address, 'billing');
        $order->set_address($address, 'shipping');
        
        // Set payment method
        $payment_method = sanitize_text_field($_POST['payment_method'] ?? 'cod');
        $order->set_payment_method($payment_method);
        
        // Add order note
        if (!empty($customer_data['order_notes'])) {
            $order->add_order_note($customer_data['order_notes'], 1, false);
        }
        
        // Set order status
        $order->set_status('pending');
        
        // Calculate totals
        $order->calculate_totals();
        
        // Store order ID in session
        WC()->session->set('custom_checkout_order_id', $order->get_id());
        
        // Clear cart
        WC()->cart->empty_cart();
        
        // Redirect to thank you page
        wp_redirect($order->get_checkout_order_received_url());
        exit;
    }
}

// Get customer data from session or set defaults
$customer_data = WC()->session->get('custom_checkout_data') ?? array(
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
    'city' => '',
    'postcode' => '',
    'order_notes' => ''
);
?>

<div class="custom-checkout-container">
    <div class="checkout-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="checkout-header">
                    <h1 class="checkout-title">Поръчка</h1>
                </div>
                
                <?php
                // Show notices
                wc_print_notices();
                ?>

                <form id="custom-checkout-form" method="post" class="custom-checkout-form">
                    <?php wp_nonce_field('custom_checkout', 'custom_checkout_nonce'); ?>
                    
                    <div class="row">
                        <!-- Left Column - Customer Details -->
                        <div class="col-lg-7">
                            <div class="checkout-form-wrapper">
                                <h3>Детайли за поръчката</h3>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_first_name">Име <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="customer_first_name" name="customer_first_name" value="<?php echo esc_attr($customer_data['first_name']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_last_name">Фамилия <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="customer_last_name" name="customer_last_name" value="<?php echo esc_attr($customer_data['last_name']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_email">Имейл адрес <span class="required">*</span></label>
                                            <input type="email" class="form-control" id="customer_email" name="customer_email" value="<?php echo esc_attr($customer_data['email']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_phone">Телефон <span class="required">*</span></label>
                                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" value="<?php echo esc_attr($customer_data['phone']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="customer_address">Адрес <span class="required">*</span></label>
                                    <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required><?php echo esc_textarea($customer_data['address']); ?></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_city">Град <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="customer_city" name="customer_city" value="<?php echo esc_attr($customer_data['city']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customer_postcode">Пощенски код <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="customer_postcode" name="customer_postcode" value="<?php echo esc_attr($customer_data['postcode']); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="order_notes">Бележки към поръчката</label>
                                    <textarea class="form-control" id="order_notes" name="order_notes" rows="3"><?php echo esc_textarea($customer_data['order_notes']); ?></textarea>
                                </div>
                                
                                <div class="shipping-method-section mb-4">
                                    <h3>Метод на доставка</h3>
                                    
                                    <div class="shipping-methods">
                                        <?php
                                        // Get available shipping methods
                                        $packages = WC()->shipping->get_packages();
                                        
                                        if (!empty($packages)) {
                                            foreach ($packages as $package_key => $package) {
                                                if (!empty($package['rates'])) {
                                                    foreach ($package['rates'] as $rate) {
                                                        ?>
                                                        <div class="shipping-method-option">
                                                            <input type="radio" name="shipping_method" id="shipping_method_<?php echo esc_attr($rate->id); ?>" value="<?php echo esc_attr($rate->id); ?>" <?php checked($rate->id, isset($_POST['shipping_method']) ? $_POST['shipping_method'] : ''); ?>>
                                                            <label for="shipping_method_<?php echo esc_attr($rate->id); ?>">
                                                                <?php echo wp_kses_post(wc_cart_totals_shipping_method_label($rate)); ?>
                                                            </label>
                                                        </div>
                                                        <?php
                                                    }
                                                } else {
                                                    echo '<p>Няма налични методи за доставка.</p>';
                                                }
                                            }
                                        } else {
                                            // Fallback shipping methods if packages are not available
                                            $shipping_methods = array(
                                                'econt' => 'Доставка с Еконт - Офис (Цената ще бъде калкулирана)',
                                                'speedy' => 'Доставка със Спиди (Цената ще бъде калкулирана)'
                                            );
                                            
                                            foreach ($shipping_methods as $method_id => $method_name) {
                                                ?>
                                                <div class="shipping-method-option">
                                                    <input type="radio" name="shipping_method" id="shipping_method_<?php echo esc_attr($method_id); ?>" value="<?php echo esc_attr($method_id); ?>" <?php checked($method_id, isset($_POST['shipping_method']) ? $_POST['shipping_method'] : ''); ?>>
                                                    <label for="shipping_method_<?php echo esc_attr($method_id); ?>">
                                                        <?php echo esc_html($method_name); ?>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    
                                    <!-- Econt Integration Hook -->
                                    <div id="econt-delivery-container" class="econt-delivery-container mt-4">
                                        <?php do_action('woocommerce_review_order_after_shipping'); ?>
                                    </div>
                                </div>
                                
                                <div class="payment-method-section">
                                    <h3>Метод на плащане</h3>
                                    
                                    <div class="payment-methods">
                                        <?php
                                        // Get available payment gateways
                                        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                                        
                                        if (!empty($available_gateways)) {
                                            foreach ($available_gateways as $gateway) {
                                                ?>
                                                <div class="payment-method-option">
                                                    <input type="radio" name="payment_method" id="payment_method_<?php echo esc_attr($gateway->id); ?>" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->id, isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cod'); ?>>
                                                    <label for="payment_method_<?php echo esc_attr($gateway->id); ?>">
                                                        <?php echo $gateway->get_title(); ?>
                                                        <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
                                                            <div class="payment-method-description">
                                                                <?php echo $gateway->get_description(); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            // Fallback payment methods if gateways are not available
                                            $payment_methods = array(
                                                'cod' => 'Наложен платеж',
                                                'bacs' => 'Банков превод'
                                            );
                                            
                                            foreach ($payment_methods as $method_id => $method_name) {
                                                ?>
                                                <div class="payment-method-option">
                                                    <input type="radio" name="payment_method" id="payment_method_<?php echo esc_attr($method_id); ?>" value="<?php echo esc_attr($method_id); ?>" <?php checked($method_id, isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cod'); ?>>
                                                    <label for="payment_method_<?php echo esc_attr($method_id); ?>">
                                                        <?php echo esc_html($method_name); ?>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Order Summary -->
                        <div class="col-lg-5">
                            <div class="order-summary-wrapper">
                                <h3>Вашата поръчка</h3>
                                
                                <div class="order-summary">
                                    <table class="order-table">
                                        <thead>
                                            <tr>
                                                <th>Продукт</th>
                                                <th>Общо</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                                $product = $cart_item['data'];
                                                $quantity = $cart_item['quantity'];
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php echo esc_html($product->get_name()); ?> &times; <?php echo esc_html($quantity); ?>
                                                        <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                                                    </td>
                                                    <td><?php echo WC()->cart->get_product_subtotal($product, $quantity); ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="cart-subtotal">
                                                <th>Междинна сума</th>
                                                <td><?php echo WC()->cart->get_cart_subtotal(); ?></td>
                                            </tr>
                                            
                                            <?php if (WC()->cart->needs_shipping()) : ?>
                                                <tr class="shipping">
                                                    <th>Доставка</th>
                                                    <td>
                                                        <?php
                                                        $packages = WC()->shipping->get_packages();
                                                        if (!empty($packages)) {
                                                            $chosen_method = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : '';
                                                            $found = false;
                                                            
                                                            foreach ($packages as $package_key => $package) {
                                                                if (!empty($package['rates'])) {
                                                                    foreach ($package['rates'] as $rate) {
                                                                        if ($rate->id === $chosen_method) {
                                                                            echo wc_price($rate->cost);
                                                                            $found = true;
                                                                            break 2;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            
                                                            if (!$found) {
                                                                echo 'Калкулира се при завършване на поръчката';
                                                            }
                                                        } else {
                                                            echo 'Калкулира се при завършване на поръчката';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            
                                            <tr class="order-total">
                                                <th>Общо</th>
                                                <td><?php echo WC()->cart->get_total(); ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                                <div class="checkout-actions mt-4">
                                    <button type="submit" name="custom_checkout_submit" id="place_order" class="button btn-place-order">
                                        Завърши поръчката
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Checkout Features -->
                            <div class="checkout-features">
                                <div class="checkout-feature">
                                    <i class="fas fa-truck-fast"></i>
                                    <span>Доставка с Econt</span>
                                </div>
                                <div class="checkout-feature">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Гаранция за Качество</span>
                                </div>
                                <div class="checkout-feature">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span>30 Дни за Връщане</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Custom Checkout Styles */
.custom-checkout-container {
    background-color: #f9f9f9;
    padding: 40px 0;
    font-family: 'Inter', sans-serif;
    overflow-x: hidden; /* Prevent horizontal scrolling */
    position: relative;
}

/* Floating Shapes similar to homepage */
.checkout-shapes {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 0;
}

.checkout-shapes .shape {
    position: absolute;
    opacity: 0.05;
    z-index: -1;
}

.checkout-shapes .shape-1 {
    top: -10%;
    right: -5%;
    width: 500px;
    height: 500px;
    background: #8e44ad;
    border-radius: 50%;
    animation: floatAnimation 10s ease-in-out infinite;
}

.checkout-shapes .shape-2 {
    bottom: -15%;
    left: -10%;
    width: 600px;
    height: 600px;
    background: #e74c3c;
    border-radius: 50%;
    animation: floatAnimation 12s ease-in-out infinite 1s;
}

.checkout-shapes .shape-3 {
    top: 40%;
    right: 15%;
    width: 300px;
    height: 300px;
    background: #3498db;
    border-radius: 50%;
    animation: floatAnimation 8s ease-in-out infinite 0.5s;
}

@keyframes floatAnimation {
    0%, 100% {
        transform: translateY(0) scale(1);
    }
    50% {
        transform: translateY(20px) scale(1.05);
    }
}

.checkout-header {
    margin-bottom: 30px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding-bottom: 15px;
    position: relative;
}

.checkout-title {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin-bottom: 0;
    position: relative;
    display: inline-block;
}

.checkout-title::after {
    content: '';
    position: absolute;
    bottom: -16px;
    left: 0;
    width: 80px;
    height: 3px;
    background: #8e44ad;
    border-radius: 3px;
}

.checkout-form-wrapper,
.order-summary-wrapper {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    padding: 30px;
    margin-bottom: 30px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.checkout-form-wrapper:hover,
.order-summary-wrapper:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.checkout-form-wrapper h3,
.order-summary-wrapper h3 {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    color: #333;
}

/* Form Styles */
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 14px;
    color: #555;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s;
    background-color: #f9f9f9;
}

.form-control:focus {
    border-color: #8e44ad;
    box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.1);
    outline: none;
    background-color: #fff;
}

.required {
    color: #e74c3c;
}

/* Shipping & Payment Methods */
.shipping-method-section,
.payment-method-section {
    margin-top: 30px;
}

.shipping-methods,
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.shipping-method-option,
.payment-method-option {
    display: flex;
    align-items: flex-start;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #eee;
    transition: all 0.3s;
}

.shipping-method-option:hover,
.payment-method-option:hover {
    border-color: #8e44ad;
    background-color: #f8f4fa;
    transform: translateY(-2px);
}

.shipping-method-option input,
.payment-method-option input {
    margin-right: 10px;
    margin-top: 3px;
}

.shipping-method-option label,
.payment-method-option label {
    margin-bottom: 0;
    font-weight: 500;
    cursor: pointer;
    flex: 1;
}

.payment-method-description {
    margin-top: 10px;
    font-size: 13px;
    color: #666;
}

/* Order Summary */
.order-table {
    width: 100%;
    border-collapse: collapse;
}

.order-table th,
.order-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.order-table thead th {
    background-color: #f9f9f9;
    font-weight: 600;
    color: #333;
}

.order-table tfoot th,
.order-table tfoot td {
    font-weight: 600;
}

.order-table tfoot tr.order-total {
    border-top: 2px solid #8e44ad;
    color: #8e44ad;
    font-size: 18px;
}

/* Place Order Button */
.btn-place-order {
    display: block;
    width: 100%;
    padding: 15px;
    background-color: #8e44ad;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3);
}

.btn-place-order:hover {
    background-color: #7d3c98;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(142, 68, 173, 0.4);
}

/* Checkout Features */
.checkout-features {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.checkout-feature {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #fff;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s;
}

.checkout-feature:hover {
    transform: translateX(5px);
}

.checkout-feature i {
    font-size: 20px;
    color: #8e44ad;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(142, 68, 173, 0.1);
    border-radius: 50%;
}

.checkout-feature span {
    font-weight: 500;
    color: #333;
}

/* Econt Integration Styles */
.econt-delivery-container {
    margin-top: 20px;
    padding: 15px;
    background-color: #f8f4fa;
    border-radius: 8px;
    border: 1px solid rgba(142, 68, 173, 0.2);
}

/* Responsive Styles */
@media (max-width: 991px) {
    .checkout-form-wrapper,
    .order-summary-wrapper {
        margin-bottom: 30px;
        padding: 20px;
    }
    
    .col-lg-7,
    .col-lg-5 {
        padding: 0 15px;
    }
    
    .checkout-title {
        font-size: 28px;
    }
    
    .checkout-features {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    
    .checkout-feature {
        flex: 0 0 calc(50% - 10px);
    }
}

@media (max-width: 576px) {
    .checkout-title {
        font-size: 24px;
    }
    
    .checkout-form-wrapper h3,
    .order-summary-wrapper h3 {
        font-size: 18px;
    }
    
    .checkout-features {
        flex-direction: column;
    }
    
    .checkout-feature {
        flex: 0 0 100%;
    }
    
    .form-group label {
        font-size: 13px;
    }
    
    .form-control {
        padding: 10px 12px;
        font-size: 14px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Show loading indicator on form submit
    $('#custom-checkout-form').on('submit', function() {
        $(this).addClass('processing');
        $('#place_order').html('<i class="fas fa-spinner fa-spin"></i> Обработка...');
    });
    
    // Real-time form validation
    $('.form-control').on('blur', function() {
        const $input = $(this);
        
        if ($input.prop('required') && $input.val() === '') {
            $input.addClass('is-invalid').removeClass('is-valid');
        } else {
            $input.removeClass('is-invalid').addClass('is-valid');
        }
    });
    
    // Add active class to selected shipping and payment methods
    $('input[name="shipping_method"], input[name="payment_method"]').on('change', function() {
        const $parent = $(this).closest('.shipping-method-option, .payment-method-option');
        
        $parent.addClass('selected').siblings().removeClass('selected');
    });
    
    // Initialize with default selected values
    $('input[name="shipping_method"]:checked, input[name="payment_method"]:checked').each(function() {
        $(this).closest('.shipping-method-option, .payment-method-option').addClass('selected');
    });
    
    // Compatibility with Econt plugin
    // Move the Econt delivery form to our container
    $('.woocommerce-checkout-review-order .woocommerce-shipping-fields').appendTo('#econt-delivery-container');
    
    // Ensure proper styling for dynamically loaded Econt elements
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                styleEcontElements();
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    function styleEcontElements() {
        // Style delivery type selectors
        $('.econt-delivery-type-radio').parent().addClass('econt-delivery-type-option');
        
        // Add icon to delivery options based on type
        $('.econt-delivery-type-option').each(function() {
            const optionText = $(this).text().toLowerCase();
            let icon = "";
            
            if (optionText.indexOf('офис') > -1) {
                icon = '<i class="fas fa-building"></i>';
            } else if (optionText.indexOf('автомат') > -1) {
                icon = '<i class="fas fa-box"></i>';
            } else {
                icon = '<i class="fas fa-home"></i>';
            }
            
            if (!$(this).find('i').length) {
                $(this).prepend(icon);
            }
        });
        
        // Style select dropdowns
        $('.econt-select').addClass('form-control');
    }
    
    // Run initially
    setTimeout(styleEcontElements, 500);
    
    // Also run on shipping method change
    $('input[name="shipping_method"]').on('change', function() {
        setTimeout(styleEcontElements, 500);
    });
});
</script>

<?php
get_footer();
</rewritten_file>