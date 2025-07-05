<?php
require_once '../config.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Save to database
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) 
                                   VALUES (:name, :email, :subject, :message)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':subject' => $subject,
                ':message' => $message
            ]);
            
            $success = 'Your message has been sent successfully! We will get back to you soon.';
            
            // Clear form
            $name = $email = $subject = $message = '';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<style>
/* ===================== */
/* CONTAINER & SECTION   */
/* ===================== */
.container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 32px 20px;
    background: linear-gradient(135deg, #f8faff, #e3e9f7);
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(60, 80, 120, 0.1), 0 1.5px 4px rgba(60, 80, 120, 0.07);
}

/* ===================== */
/* HEADING STYLES        */
/* ===================== */
h1, h2 {
    text-align: center;
    font-weight: 800;
    color: #3665f3;
    margin-bottom: 18px;
    letter-spacing: 1px;
}

h1 {
    font-size: 2.5rem;
}

h2 {
    font-size: 1.8rem;
    margin-top: 24px;
    margin-bottom: 12px;
    color: #4f8cff;
}

/* ===================== */
/* CONTACT INFO SECTION  */
/* ===================== */
.contact-container {
    background: linear-gradient(120deg,rgb(206, 212, 237) 0%, #f0f4ff 60%, #f8fafc 100%);
    box-shadow: 0 6px 24px rgba(80, 120, 200, 0.10), 0 1.5px 4px rgba(60, 80, 120, 0.07);
    border-radius: 16px;
    padding: 28px 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 36px;
    align-items: flex-start;
    margin-bottom: 36px;
}

.contact-info {
    flex: 1;
    min-width: 300px;
    font-size: 1rem;
    color: #333;
    line-height: 1.7;
}

.contact-details {
    margin-top: 20px;
}

.contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.contact-item img {
    width: 40px;
    height: 40px;
    margin-right: 15px;
    object-fit: contain;
}

.contact-item h3 {
    font-size: 1.2rem;
    color: #3665f3;
    margin: 0;
}

.contact-item p {
    margin: 0;
    font-size: 0.95rem;
    color: #555;
}

/* ===================== */
/* CONTACT FORM SECTION  */
/* ===================== */
.contact-form {
    flex: 1;
    min-width: 300px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(60, 80, 120, 0.1);
}

.contact-form h2 {
    margin-bottom: 20px;
    font-size: 1.5rem;
    color: #4f8cff;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 0.95rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #3665f3;
    box-shadow: 0 0 4px rgba(54, 101, 243, 0.3);
    outline: none;
}

textarea {
    resize: none;
}

/* ===================== */
/* SUBMIT BUTTON         */
/* ===================== */
.submit-btn {
    display: inline-block;
    width: 100%;
    max-width: 200px;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: bold;
    color: #fff;
    background: linear-gradient(90deg, #3665f3 0%, #4f8cff 100%);
    border: none;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 4px 12px rgba(54, 101, 243, 0.2);
    margin-top: 10px;
}

.submit-btn:hover {
    background: linear-gradient(90deg, #2a56d6 0%, #3a78ff 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(54, 101, 243, 0.3);
}

.submit-btn:active {
    transform: translateY(0);
    box-shadow: 0 3px 8px rgba(54, 101, 243, 0.2);
}

.submit-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    box-shadow: none;
}

/* ===================== */
/* MAP SECTION           */
/* ===================== */
.contact-map {
    margin-top: 40px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(60, 80, 120, 0.1);
}

/* ===================== */
/* RESPONSIVE DESIGN     */
/* ===================== */
@media (max-width: 900px) {
    .contact-container {
        flex-direction: column;
        gap: 24px;
    }

    .contact-info,
    .contact-form {
        min-width: 100%;
    }
}

@media (max-width: 600px) {
    .container {
        padding: 16px;
        border-radius: 12px;
    }

    .contact-item img {
        width: 30px;
        height: 30px;
    }

    .submit-btn {
        max-width: 100%;
    }
}
</style>
<div class="container">
    <h1>Contact Us</h1>
    
    <div class="contact-container">
        <div class="contact-info">
            <h2>Get In Touch</h2>
            <p>We'd love to hear from you! Please fill out the form or use the contact information below.</p>
            <div class="contact-details">
                <div class="contact-item">
                    <img src="/PWDTUBES_WalBayExpress/assets/img/contact/location (1).png" alt="Location">
                    <div>
                        <h3>Address</h3>
                        <p>123 E-commerce Street, Jakarta, Indonesia 12345</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <img src="/PWDTUBES_WalBayExpress/assets/img/contact/telephone.png" alt="Phone">
                    <div>
                        <h3>Phone</h3>
                        <p>+62 21 1234 5678</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <img src="/PWDTUBES_WalBayExpress/assets/img/contact/email.png" alt="Email">
                    <div>
                        <h3>Email</h3>
                        <p>support@walbayexpress.com</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <img src="/PWDTUBES_WalBayExpress/assets/img/contact/working-time.png" alt="Hours">
                    <div>
                        <h3>Working Hours</h3>
                        <p>Monday - Friday: 9AM - 6PM</p>
                        <p>Saturday: 10AM - 4PM</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-form">
            <h2>Send Us a Message</h2>
            
            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert success"><?= $success ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required value="<?= htmlspecialchars($name ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required value="<?= htmlspecialchars($subject ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($message ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </div>
    
    <div class="contact-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613507824!3d-6.194741395493371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0x9c332daa7f9acf9c!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1679999999999!5m2!1sen!2sid" 
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>
<script src="/PWDTUBES_WalBayExpress/assets/js/wishlist.js"></script>
<?php require_once '../includes/footer.php'; ?>