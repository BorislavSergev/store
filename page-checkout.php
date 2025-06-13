<?php
/**
 * Template Name: Checkout Page
 *
 * This template overrides the WooCommerce checkout page and is now updated
 * to support the "Checkout Field Editor for WooCommerce" by ThemeHigh
 * and a new color scheme.
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

// Get the global checkout object. This is essential for the hooks to work.
$checkout = WC()->checkout();

get_header('shop');

do_action('woocommerce_before_main_content');
?>

<!-- ========================================================= -->
<!-- === START: CRITICAL FIX FOR THEMEHIGH & ECONT PLUGINS === -->
<!-- ========================================================= -->
<style>
    /*
     * This CSS is crucial. The Econt plugin often hides the entire address
     * section. This rule forces the container for your custom ThemeHigh fields
     * to be visible, overriding any 'display: none' from other plugins.
    */
    .woocommerce-billing-fields {
        display: block !important;
        height: auto !important;
        overflow: visible !important;
        opacity: 1 !important;
        margin-bottom: 30px; /* Add some space below the billing form */
    }
</style>
<!-- ======================================================= -->
<!-- === END: CRITICAL FIX === -->
<!-- ======================================================= -->


<div class="modern-checkout-container">
    <div class="checkout-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="checkout-header">
                    <h1 class="checkout-title"><?php the_title(); ?></h1>
                </div>

                <?php do_action('woocommerce_before_checkout_form', $checkout); ?>

                <?php
                // If checkout registration is disabled and not logged in, the user cannot checkout.
                if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
                    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
                    return;
                }
                ?>

                <!-- Econt Information Notice - UPDATED FOR CLARITY -->
                <div class="econt-notice">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span>Моля, попълнете данните за фактуриране. Адресът за <strong>доставка</strong> ще бъде избран/въведен от формата на Econt по-долу.</span>
                    </div>
                </div>

                <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Left Column - Order Notes & Customer Details -->
                        <div class="col-lg-7">
                            <div class="checkout-form-wrapper">
                                <?php do_action('woocommerce_checkout_before_customer_details', $checkout); ?>

                                <div class="customer-details" id="customer_details">

                                    <!-- =========================================================== -->
                                    <!-- THIS HOOK NOW TRIGGERS THE THEMEHIGH PLUGIN TO SHOW YOUR CUSTOM FIELDS -->
                                    <!-- =========================================================== -->
                                    <?php do_action('woocommerce_checkout_billing', $checkout); ?>
                                    <!-- =========================================================== -->

                                    <?php if (WC()->cart->needs_shipping()) : ?>
                                        <div class="shipping-method-section">
                                            <h3>Метод на доставка</h3>
                                            <?php do_action('woocommerce_checkout_shipping', $checkout); ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- This is the critical hook for Econt delivery options -->
                                    <?php do_action('woocommerce_review_order_after_shipping', $checkout); ?>

                                    <!-- Load your custom template for additional fields (Order Notes) -->
                                    <?php get_template_part('woocommerce/checkout/form-additional-fields'); ?>

                                </div>

                                <?php do_action('woocommerce_checkout_after_customer_details', $checkout); ?>
                            </div>
                        </div>

                        <!-- Right Column - Order Summary -->
                        <div class="col-lg-5">
                            <div class="order-summary-wrapper">
                                <h3 id="order_review_heading">Вашата поръчка</h3>

                                <div id="order_review" class="woocommerce-checkout-review-order">
                                    <?php do_action('woocommerce_checkout_order_review'); ?>
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

                <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Checkout Styles - COLOR SCHEME UPDATED TO #e74c3c */
.modern-checkout-container {
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
    background: #e74c3c; /* CHANGED COLOR */
    border-radius: 50%;
    animation: floatAnimation 10s ease-in-out infinite;
}

.checkout-shapes .shape-2 {
    bottom: -15%;
    left: -10%;
    width: 600px;
    height: 600px;
    background: #f39c12; /* Kept secondary color for contrast */
    border-radius: 50%;
    animation: floatAnimation 12s ease-in-out infinite 1s;
}

.checkout-shapes .shape-3 {
    top: 40%;
    right: 15%;
    width: 300px;
    height: 300px;
    background: #3498db; /* Kept secondary color for contrast */
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
    background: #e74c3c; /* CHANGED COLOR */
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
    color: #e74c3c; /* CHANGED COLOR */
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(231, 76, 60, 0.1); /* CHANGED COLOR */
    border-radius: 50%;
}

.checkout-feature span {
    font-weight: 500;
    color: #333;
}

/* Form Styles */
.woocommerce-billing-fields__field-wrapper,
.woocommerce-shipping-fields__field-wrapper,
.woocommerce-additional-fields__field-wrapper {
    margin-bottom: 20px;
}

.form-row {
    margin-bottom: 20px;
}

.form-row label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 14px;
    color: #555;
}

.form-row input,
.form-row select,
.form-row textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s;
    background-color: #f9f9f9;
}

.form-row input:focus,
.form-row select:focus,
.form-row textarea:focus {
    border-color: #e74c3c; /* CHANGED COLOR */
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1); /* CHANGED COLOR */
    outline: none;
    background-color: #fff;
}

/* Shipping Methods */
.woocommerce-shipping-methods {
    list-style: none;
    padding: 0;
    margin: 0 0 15px;
}

.woocommerce-shipping-methods li {
    margin-bottom: 10px;
    padding: 12px 15px;
    background-color: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #eee;
    transition: all 0.3s;
    display: flex;
    align-items: center;
}

.woocommerce-shipping-methods li:hover {
    border-color: #e74c3c; /* CHANGED COLOR */
    background-color: #fff9f8;
    transform: translateY(-2px);
}

.woocommerce-shipping-methods li label {
    margin-bottom: 0;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.woocommerce-shipping-methods input[type="radio"] {
    margin-right: 8px;
    flex: 0 0 auto;
}

/* Validation Styles */
.woocommerce-invalid input {
    border-color: #e74c3c;
}

.woocommerce-validated input {
    border-color: #2ecc71;
}

.required {
    color: #e74c3c;
}

/* Loading State */
.processing {
    opacity: 0.5;
    pointer-events: none;
}

/* Additional fields styling */
.woocommerce-additional-fields {
    margin-top: 10px;
}

.woocommerce-additional-fields h3 {
    font-size: 18px;
}

/* Econt Notice Styling */
.econt-notice {
    margin-bottom: 25px;
}

.econt-notice .alert {
    background-color: #fff9f8; /* CHANGED COLOR */
    border-left: 4px solid #e74c3c; /* CHANGED COLOR */
    color: #333;
    padding: 18px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.econt-notice .alert i {
    font-size: 20px;
    margin-right: 15px;
    color: #e74c3c; /* CHANGED COLOR */
}

.econt-notice .alert span {
    font-size: 15px;
    font-weight: 500;
}

/* Fix for horizontal scrollbar */
html, body {
    overflow-x: hidden;
    max-width: 100%;
}

.container {
    max-width: 100%;
    overflow-x: hidden;
}

.row {
    margin-left: 0;
    margin-right: 0;
}

/* Econt Plugin Integration Styles */
.econt-delivery-options {
    margin-top: 20px;
    padding: 15px;
    background-color: #fff9f8; /* CHANGED COLOR */
    border-radius: 8px;
    border: 1px solid rgba(231, 76, 60, 0.2); /* CHANGED COLOR */
}

.econt-delivery-type {
    margin-bottom: 20px;
}

.econt-delivery-type label {
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.econt-delivery-type-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.econt-delivery-type-option {
    flex: 1 0 30%;
    min-width: 150px;
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    text-align: center;
    transition: all 0.3s;
}

.econt-delivery-type-option:hover,
.econt-delivery-type-option.selected {
    border-color: #e74c3c; /* CHANGED COLOR */
    background-color: #fff9f8; /* CHANGED COLOR */
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.1); /* CHANGED COLOR */
}

.econt-delivery-type-option i {
    display: block;
    font-size: 24px;
    margin-bottom: 10px;
    color: #e74c3c; /* CHANGED COLOR */
}

.econt-city-field {
    margin-top: 20px;
}

.econt-loading {
    text-align: center;
    padding: 20px;
    color: #e74c3c; /* CHANGED COLOR */
}

.econt-loading i {
    font-size: 24px;
    animation: spin 1s infinite linear;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
    
    .econt-delivery-type-option {
        flex: 1 0 45%;
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
    
    .form-row label {
        font-size: 13px;
    }
    
    .form-row input,
    .form-row select,
    .form-row textarea {
        padding: 10px 12px;
        font-size: 14px;
    }
    
    .econt-delivery-type-option {
        flex: 1 0 100%;
    }
    
    .econt-delivery-type-options {
        flex-direction: column;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Show loading indicator on form submit
    $('form.checkout').on('submit', function() {
        $(this).addClass('processing');
        $('#place_order').html('<i class="fas fa-spinner fa-spin"></i> Обработка...');
    });
    
    // Real-time form validation
    $('.woocommerce-input-wrapper input, .woocommerce-input-wrapper select').on('blur', function() {
        const $input = $(this);
        const $wrapper = $input.closest('.form-row');
        
        if ($input.val() === '' && $wrapper.hasClass('validate-required')) {
            $wrapper.removeClass('woocommerce-validated').addClass('woocommerce-invalid');
        } else {
            $wrapper.removeClass('woocommerce-invalid').addClass('woocommerce-validated');
        }
    });
    
    // Smooth scroll to checkout sections
    $('.checkout-form-wrapper h3').on('click', function() {
        $(this).next().slideToggle(300);
    });
    
    // Add animation to checkout features
    $('.checkout-feature').each(function(index) {
        $(this).css('animation-delay', (index * 0.15) + 's');
        $(this).addClass('fade-in');
    });
    
    // Handle shipping method selection
    $('body').on('change', '.shipping_method', function() {
        // Add active class to selected shipping method
        $(this).closest('li').addClass('selected');
        $(this).closest('li').siblings().removeClass('selected');
    });
    
    // Compatibility with Econt plugin
    // This ensures that when the Econt delivery form is loaded, it gets proper styling
    $(document).on('econt_delivery_form_loaded', function() {
        $('.econt-delivery-type-option').on('click', function() {
            $('.econt-delivery-type-option').removeClass('selected');
            $(this).addClass('selected');
        });
    });
});
</script>

<?php
get_footer('shop');
