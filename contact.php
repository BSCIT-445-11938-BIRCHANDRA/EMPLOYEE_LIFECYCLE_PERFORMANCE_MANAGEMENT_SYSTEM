<?php include 'components/header.php'; ?>

<div class="page-content">
    <div class="container">
        <h1 class="page-title">Contact Us</h1>
        
        <div class="contact-form">
            <form action="#" method="POST" id="contactForm">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email address">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="What is this regarding?">
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" required placeholder="Tell us how we can help you..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
        
        <div style="text-align: center; margin-top: 3rem; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
            <h3>Other Ways to Reach Us</h3>
            <p style="margin: 1rem 0; color: #666;">
                <strong>Email:</strong> support@emssystem.com<br>
                <strong>Phone:</strong> +1 (555) 123-4567<br>
                <strong>Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM EST
            </p>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    
    // Simple validation
    if (!name || !email || !message) {
        alert('Please fill in all required fields.');
        return;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }
    
    // Show success message (in real implementation, this would send to server)
    alert('Thank you for your message! We will get back to you soon.');
    
    // Clear form
    this.reset();
});
</script>

<?php include 'components/footer.php'; ?>
