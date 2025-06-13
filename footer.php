<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop" aria-label="<?php esc_attr_e('Обратно в началото', 'shoes-store'); ?>">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Modern Footer -->
<footer class="modern-footer">
    <div class="footer-top-wave">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="url(#footer-gradient-1)"></path>
            <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="url(#footer-gradient-2)"></path>
            <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="url(#footer-gradient-3)"></path>
            <defs>
                <linearGradient id="footer-gradient-1" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:#1a252f"/>
                    <stop offset="50%" style="stop-color:#2c3e50"/>
                    <stop offset="100%" style="stop-color:#34495e"/>
                </linearGradient>
                <linearGradient id="footer-gradient-2" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:#1a252f"/>
                    <stop offset="50%" style="stop-color:#2c3e50"/>
                    <stop offset="100%" style="stop-color:#34495e"/>
                </linearGradient>
                <linearGradient id="footer-gradient-3" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:#1a252f"/>
                    <stop offset="50%" style="stop-color:#2c3e50"/>
                    <stop offset="100%" style="stop-color:#34495e"/>
                </linearGradient>
            </defs>
        </svg>
    </div>

    <div class="container">
        <div class="footer-main">
            <!-- Brand Column -->
            <div class="footer-brand">
                <div class="footer-logo-text">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <i class="fas fa-shoe-prints"></i>
                        <?php bloginfo('name'); ?>
                    </a>
                </div>
                <p class="footer-brand-desc">
                    <?php _e('Вашият надежден магазин за стилни и качествени обувки. Открийте нашата колекция от съвременни модели, които съчетават комфорт, качество и стил.', 'shoes-store'); ?>
                </p>
                <div class="social-media footer-social">
                    <h4 class="social-title"><?php _e('Последвайте ни', 'shoes-store'); ?></h4>
                    <div class="social-icons">
                        <a href="#" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Links Column -->
            <div class="footer-nav">
                <h3 class="footer-title"><?php _e('Полезни връзки', 'shoes-store'); ?></h3>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><i class="fas fa-angle-right"></i> <?php _e('Начало', 'shoes-store'); ?></a></li>
                    <li><a href="<?php echo class_exists('WooCommerce') ? esc_url(wc_get_page_permalink('shop')) : '#'; ?>"><i class="fas fa-angle-right"></i> <?php _e('Обувки', 'shoes-store'); ?></a></li>
                    <li><a href="#about"><i class="fas fa-angle-right"></i> <?php _e('За Нас', 'shoes-store'); ?></a></li>
                    <li><a href="#contact"><i class="fas fa-angle-right"></i> <?php _e('Контакти', 'shoes-store'); ?></a></li>
                    <?php if (class_exists('WooCommerce')) : ?>
                        <li><a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>"><i class="fas fa-angle-right"></i> <?php _e('Моят профил', 'shoes-store'); ?></a></li>
                        <li><a href="<?php echo esc_url(wc_get_cart_url()); ?>"><i class="fas fa-angle-right"></i> <?php _e('Количка', 'shoes-store'); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="footer-contact">
                <h3 class="footer-title"><?php _e('Контакти', 'shoes-store'); ?></h3>
                <ul class="contact-info">
                    <li>
                        <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                        <span><?php _e('ул. Граф Игнатиев 15, София 1000, България', 'shoes-store'); ?></span>
                    </li>
                    <li>
                        <span class="icon"><i class="fas fa-phone-alt"></i></span>
                        <span>+359 88 123 4567</span>
                    </li>
                    <li>
                        <span class="icon"><i class="fas fa-envelope"></i></span>
                        <span>contact@shoesstore.bg</span>
                    </li>
                    <li>
                        <span class="icon"><i class="fas fa-clock"></i></span>
                        <span><?php _e('Пон-Пет: 10:00 - 19:00<br>Съб: 10:00 - 17:00', 'shoes-store'); ?></span>
                    </li>
                </ul>
            </div>
            
            <!-- Payment Methods Column -->
            <div class="footer-payments">
                <h3 class="footer-title"><?php _e('Методи на плащане', 'shoes-store'); ?></h3>
                <div class="payment-methods-grid">
                    <div class="payment-method">
                        <i class="fab fa-cc-visa" aria-hidden="true"></i>
                        <span>Visa</span>
                    </div>
                    <div class="payment-method">
                        <i class="fab fa-cc-mastercard" aria-hidden="true"></i>
                        <span>Mastercard</span>
                    </div>
                    <div class="payment-method">
                        <i class="fab fa-cc-paypal" aria-hidden="true"></i>
                        <span>PayPal</span>
                    </div>
                    <div class="payment-method">
                        <i class="fas fa-money-bill-wave" aria-hidden="true"></i>
                        <span><?php _e('В брой', 'shoes-store'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('Всички права запазени.', 'shoes-store'); ?></p>
            </div>
            <div class="footer-policy-links">
                <a href="#"><?php _e('Условия за ползване', 'shoes-store'); ?></a>
                <span class="separator"> | </span>
                <a href="#"><?php _e('Политика за поверителност', 'shoes-store'); ?></a>
                <span class="separator"> | </span>
                <a href="#"><?php _e('Бисквитки', 'shoes-store'); ?></a>
            </div>
        </div>
    </div>
</footer>

<script>
// Back to Top Button Functionality
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('backToTop');
    
    // Show button when user scrolls down 300px
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('visible');
        } else {
            backToTopButton.classList.remove('visible');
        }
    });
    
    // Scroll to top when button is clicked
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

<?php wp_footer(); ?>

</body>
</html> 