<?php
// includes/footer.php
?>
    </div> <!-- Close container -->
    
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><?php echo SITE_NAME; ?></h5>
                    <p>Your trusted online shopping destination.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>about.php" class="text-white">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>contact.php" class="text-white">Contact</a></li>
                        <li><a href="<?php echo SITE_URL; ?>privacy.php" class="text-white">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p><i class="fas fa-envelope"></i> support@example.com</p>
                    <p><i class="fas fa-phone"></i> +62 21 1234 5678</p>
                </div>
            </div>
            <hr class="bg-light">
            <p class="text-center mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>