<?php
/**
 * Template Name: Checkout Page
 *
 * This template overrides the WooCommerce checkout page and includes
 * a definitive JavaScript fix to ensure compatibility between ThemeHigh Checkout Field Editor
 * and the Econt shipping plugin, with an updated color scheme.
 *
 * @package Shoes_Store_Theme
 */

defined('ABSPATH') || exit;

// Get the global checkout object. This is essential for the hooks to work.
$checkout = WC()->checkout();

get_header('shop');

do_action('woocommerce_before_main_content');
?>

<style>
    /*
     * This CSS is a fallback. The main fix is in the JavaScript below.
    */
    .woocommerce-billing-fields {
        display: block !important;
        height: auto !important;
        overflow: visible !important;
        opacity: 1 !important;
        margin-bottom: 30px !important;
    }
</style>

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

                <!-- Econt Information Notice -->
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

                                    <!-- This hook triggers the ThemeHigh plugin to show your custom fields -->
                                    <?php do_action('woocommerce_checkout_billing', $checkout); ?>

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
.woocommerce-shipping-methods li:hover {
    border-color: #e74c3c; /* CHANGED COLOR */
    background-color: #fff9f8;
}

/* Validation Styles */
.required {
    color: #e74c3c;
}

/* Econt Notice Styling */
.econt-notice .alert {
    background-color: #fff9f8; /* CHANGED COLOR */
    border-left: 4px solid #e74c3c; /* CHANGED COLOR */
}

.econt-notice .alert i {
    color: #e74c3c; /* CHANGED COLOR */
}

/* Econt Plugin Integration Styles */
.econt-delivery-options {
    background-color: #fff9f8; /* CHANGED COLOR */
    border: 1px solid rgba(231, 76, 60, 0.2); /* CHANGED COLOR */
}

.econt-delivery-type-option:hover,
.econt-delivery-type-option.selected {
    border-color: #e74c3c; /* CHANGED COLOR */
    background-color: #fff9f8; /* CHANGED COLOR */
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.1); /* CHANGED COLOR */
}

.econt-delivery-type-option i {
    color: #e74c3c; /* CHANGED COLOR */
}

.econt-loading {
    color: #e74c3c; /* CHANGED COLOR */
}

/* CHECKOUT BUTTON STYLING */
.woocommerce #payment #place_order,
.woocommerce-page #payment #place_order {
    background-color: #e74c3c !important;
    color: #fff !important;
    font-weight: 600;
    padding: 15px !important;
    border-radius: 8px !important;
    transition: background-color 0.3s ease !important;
}
.woocommerce #payment #place_order:hover,
.woocommerce-page #payment #place_order:hover {
    background-color: #c0392b !important; /* Darker shade for hover */
}

</style>

<!-- ============================================= -->
<!-- === START: JAVASCRIPT FIX FOR FIELD VISIBILITY === -->
<!-- ============================================= -->
<script>
jQuery(function($) { // Use the standard shorthand for document.ready

    /**
     * This function uses a timed delay to force the billing fields to be visible.
     * It's designed to run *after* other plugins (like Econt) have finished
     * hiding the address section.
     */
    function showBillingFieldsWithDelay() {
        setTimeout(function() {
            var $billingFields = $('.woocommerce-billing-fields');
            
            // We only act if the fields container exists but is not visible
            if ($billingFields.length && !$billingFields.is(':visible')) {
                $billingFields.show().css({
                    'display': 'block',
                    'opacity': '1',
                    'height': 'auto',
                    'overflow': 'visible'
                });
                // This message will appear in your browser's console (F12) to confirm the fix is running
                console.log('Shoes Store Theme Fix: Billing fields were hidden and are now forced to be visible.');
            }
        }, 150); // A small delay of 150ms to ensure this runs last.
    }

    // --- Main Execution ---

    // 1. Run the function on initial page load, after a short delay.
    showBillingFieldsWithDelay();

    // 2. IMPORTANT: Run the function again every time the checkout updates.
    // This is the most crucial part for handling shipping method changes.
    $(document.body).on('updated_checkout', function() {
        console.log('Shoes Store Theme Fix: Checkout updated. Re-checking billing fields visibility.');
        showBillingFieldsWithDelay();
    });

});
</script>
<!-- =========================================== -->
<!-- === END: JAVASCRIPT FIX === -->
<!-- =========================================== -->

<?php
get_footer('shop');
