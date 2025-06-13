<?php
/**
 * The main template file
 *
 * @package Shoes_Store_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    
    <!-- Hero Section -->
    <section class="hero-section modern-hero">
        <div class="hero-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        
        <div class="container">
            <div class="hero-wrapper">
                <!-- Left Content -->
                <div class="hero-content">
                    <div class="sale-badge-wrapper">
                        <div class="sale-badge">
                            <i class="fas fa-tags fa-sm"></i>
                            <span><?php echo esc_html(get_theme_mod('sale_label', __('Продукти в Промоция', 'shoes-store'))); ?></span>
                        </div>
                    </div>
                    
                    <h1 class="hero-title">
                        <?php echo esc_html(get_theme_mod('hero_title', __('Направи своя ход с до 50% отстъпка', 'shoes-store'))); ?>
                    </h1>
                    
                    <p class="hero-subtitle">
                        <?php echo esc_html(get_theme_mod('hero_subtitle', __('Първокласни спортни обувки Air Max се завръщат с ярки цветове, които определено ще привлекат погледите', 'shoes-store'))); ?>
                    </p>
                    
                    <div class="hero-buttons">
                        <a href="<?php echo esc_url(get_theme_mod('hero_button_url', '#products')); ?>" class="hero-cta-button">
                            <?php echo esc_html(get_theme_mod('hero_button_text', __('Пазарувай Сега', 'shoes-store'))); ?>
                        </a>
                        <a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_page_permalink('shop')) : '#'; ?>" class="hero-secondary-button">
                            <?php _e('Разгледай Всички', 'shoes-store'); ?>
                        </a>
                    </div>
                    
                    <div class="hero-features">
                        <div class="hero-feature">
                            <i class="fas fa-truck-fast"></i>
                            <span><?php _e('Безплатна Доставка', 'shoes-store'); ?></span>
                        </div>
                        <div class="hero-feature">
                            <i class="fas fa-shield-alt"></i>
                            <span><?php _e('Гаранция за Качество', 'shoes-store'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Hero Product -->
                <div class="hero-product">
                    <div class="hero-product-image">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/nike-air-max-90.png" 
                             alt="Nike Air Max 90" 
                             class="featured-shoe-img"
                             loading="lazy"
                             onerror="this.src='https://static.nike.com/a/images/t_PDP_1280_v1/f_auto,q_auto:eco/6392cf18-9735-45a0-8f08-76f0ab2b4497/air-max-90-shoes-G4vNnW.png'; this.onerror=null;">
                        <div class="hero-product-badge">
                            <span class="hero-product-discount">-50%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="scroll-indicator">
                <a href="#products" aria-label="<?php _e('Scroll Down', 'shoes-store'); ?>">
                    <div class="mouse">
                        <div class="wheel"></div>
                    </div>
                    <div class="arrows">
                        <span class="arrow-down"></span>
                        <span class="arrow-down"></span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <?php get_template_part('template-parts/content', 'about'); ?>

    <!-- Featured Products Section -->
    <section class="products-section modern-products" id="products">
        <div class="container">
            
            <?php 
            // Get the selected category
            $selected_category = get_theme_mod('home_product_category', 'new_arrivals');
            
            // Get products query
            $products_query = shoes_store_get_homepage_products();
            
            // Get the section title based on selected category
            $section_title = get_theme_mod('products_section_title', __('Препоръчани Продукти', 'shoes-store'));
            
            // Display section header
            ?>
            <div class="products-header">
                <h2 class="section-title">
                    <?php echo esc_html($section_title); ?>
                </h2>
            </div>
            
            <?php
            // Check if products exist
            if ($products_query->have_posts()) : 
                // Count total products found
                $product_count = $products_query->post_count;
            ?>
            
            <div class="products-grid modern-grid products-count-<?php echo $product_count; ?>">
                <?php 
                // Loop through products
                while ($products_query->have_posts()) : $products_query->the_post(); 
                    $product = wc_get_product(get_the_ID());
                    if (!$product) continue;
                ?>
                    <div class="product-card modern-card">
                        <div class="product-image-wrapper">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('product-thumbnail', array('class' => 'product-image')); ?>
                                </a>
                            <?php else : ?>
                                <div class="product-image-placeholder">
                                    <i class="fas fa-shoe-prints shoe-icon"></i>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($product->is_on_sale()) : ?>
                                <div class="sale-badge">
                                    <span><?php _e('Sale!', 'shoes-store'); ?></span>
                                </div>
                            <?php endif; ?>
                            

                        </div>
                        
                        <div class="product-info">
                            <?php 
                            $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
                            if ($product_cats && !is_wp_error($product_cats)) : 
                            ?>
                                <div class="product-brand"><?php echo esc_html($product_cats[0]->name); ?></div>
                            <?php endif; ?>
                            
                            <h3 class="product-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <?php if (get_theme_mod('show_product_ratings', true) && $product->get_average_rating()) : ?>
                                <div class="product-rating">
                                    <?php echo shoes_store_get_star_rating($product->get_average_rating()); ?>
                                    <span class="rating-count">(<?php echo $product->get_rating_count(); ?>)</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-price-action">
                                <div class="product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                                <button class="add-to-cart-btn" data-product-id="<?php the_ID(); ?>" aria-label="<?php esc_attr_e('Добави', 'shoes-store'); ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span><?php _e('Добави', 'shoes-store'); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="products-footer">
                <?php if (class_exists('WooCommerce')) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="view-all-btn">
                        <?php _e('Виж Всички', 'shoes-store'); ?> <i class="fas fa-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            
            <?php 
            wp_reset_postdata();
            else : 
                // No products found message
                if (class_exists('WooCommerce')) :
            ?>
                <div class="no-products-message">
                    <div class="message-container">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php if (empty($selected_category) || $selected_category == '') : ?>
                            <h3><?php _e('Не е избрана категория', 'shoes-store'); ?></h3>
                            <p><?php _e('Моля, изберете категория от настройките на темата. Отидете в Външен вид > Персонализиране > Настройки за Продукти на Началната Страница и изберете категория от падащото меню.', 'shoes-store'); ?></p>
                        <?php else : ?>
                            <h3><?php _e('Няма намерени продукти', 'shoes-store'); ?></h3>
                            <p><?php _e('Няма продукти в избраната категория. Моля, добавете продукти или изберете друга категория от настройките на темата.', 'shoes-store'); ?></p>
                        <?php endif; ?>
                        
                        <?php if (current_user_can('manage_options')) : ?>
                            <a href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=home_products_section')); ?>" class="btn btn-primary">
                                <?php _e('Настрой Категорията', 'shoes-store'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
                else :
                    // Default products if WooCommerce is not activated
                    // [Default products code remains unchanged]
                    // Default products if no WooCommerce products are found
                $default_products = array(
                    array(
                        'title' => __('Nike Air Force 1', 'shoes-store'),
                        'category' => 'Nike',
                        'price' => '210,00 лв',
                        'sale_price' => '',
                        'rating' => 4.5,
                        'color' => '#f0f8e8'
                    ),
                    array(
                        'title' => __('Nike Air Jordan 1', 'shoes-store'),
                        'category' => 'Nike',
                        'price' => '250,00 лв',
                        'sale_price' => '199,00 лв',
                        'rating' => 4.7,
                        'color' => '#e8f4f8'
                    ),
                    array(
                        'title' => __('Nike Air Max 270', 'shoes-store'),
                        'category' => 'Nike',
                        'price' => '210,00 лв',
                        'sale_price' => '',
                        'rating' => 4.3,
                        'color' => '#f8e8f4'
                    ),
                    array(
                        'title' => __('Nike Dunk Low', 'shoes-store'),
                        'category' => 'Nike',
                        'price' => '180,00 лв',
                        'sale_price' => '',
                        'rating' => 4.6,
                        'color' => '#e8f0f8'
                    )
                );
            ?>
                <div class="products-grid modern-grid">
                    <?php foreach ($default_products as $index => $product) : ?>
                        <div class="product-card modern-card">
                            <div class="product-image-wrapper">
                                <div class="product-image-placeholder" style="background: <?php echo esc_attr($product['color']); ?>;">
                                    <i class="fas fa-shoe-prints shoe-icon"></i>
                                </div>
                                
                                <?php if (!empty($product['sale_price'])) : ?>
                                    <div class="sale-badge">
                                        <span><?php _e('Sale!', 'shoes-store'); ?></span>
                                    </div>
                                <?php endif; ?>
                                

                            </div>
                            
                            <div class="product-info">
                                <div class="product-brand"><?php echo esc_html($product['category']); ?></div>
                                
                                <h3 class="product-title">
                                    <a href="#"><?php echo esc_html($product['title']); ?></a>
                                </h3>
                                
                                <?php if (get_theme_mod('show_product_ratings', true)) : ?>
                                    <div class="product-rating">
                                        <?php echo shoes_store_get_star_rating($product['rating']); ?>
                                        <span class="rating-count">(<?php echo rand(10, 50); ?>)</span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="product-price-action">
                                    <div class="product-price">
                                        <?php if (!empty($product['sale_price'])) : ?>
                                            <del><?php echo esc_html($product['price']); ?></del>
                                            <ins><?php echo esc_html($product['sale_price']); ?></ins>
                                        <?php else : ?>
                                            <?php echo esc_html($product['price']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <button class="add-to-cart-btn" aria-label="<?php esc_attr_e('Добави', 'shoes-store'); ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span><?php _e('Добави', 'shoes-store'); ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="products-footer">
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="view-all-btn">
                            <?php _e('Виж Всички', 'shoes-store'); ?> <i class="fas fa-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php 
                endif;
            endif; 
            ?>
        </div>
    </section>

    <!-- Contact Information Section -->
    <?php get_template_part('template-parts/content', 'contact'); ?>

</main>

<!-- Back to Top Button -->
<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Върни се в началото', 'shoes-store'); ?>">
    <i class="fas fa-arrow-up"></i>
</button>

<?php get_footer(); ?> 