<?php
require_once 'config.php';

$success = $error = '';

// Handle contact form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['number'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $subject, $message])) {
            $success = "Message sent successfully! We'll contact you soon.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

// Fetch dynamic data
$services = $pdo->query("SELECT * FROM services ORDER BY display_order")->fetchAll();
$gallery = $pdo->query("SELECT * FROM gallery ORDER BY display_order")->fetchAll();
$pricePlans = $pdo->query("SELECT * FROM price_plans ORDER BY display_order")->fetchAll();
$reviews = $pdo->query("SELECT * FROM reviews ORDER BY display_order")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evento - Professional Event Organizer</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
    <a href="#" class="logo"><span>e</span>vento</a>
    <nav class="navbar">
        <a href="#home">home</a>
        <a href="#service">service</a>
        <a href="#about">about</a>
        <a href="#gallery">gallery</a>
        <a href="#price">price</a>
        <a href="#review">review</a>
        <a href="#contact">contact</a>
        <a href="admin.php">Admin</a>
    </nav>
    <div id="menu-bars" class="fas fa-bars"></div>
</header>

<!-- Home Section -->
<section class="home" id="home">
    <div class="content">
        <h3>its time to celebrate! the best <span> event organizers </span></h3>
        <a href="#contact" class="btn">contact us</a>
    </div>
    <div class="swiper-container home-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="images/slide-1.jpg" alt=""></div>
            <div class="swiper-slide"><img src="images/slide-2.jpg" alt=""></div>
            <div class="swiper-slide"><img src="images/slide-3.jpg" alt=""></div>
            <div class="swiper-slide"><img src="images/slide-4.jpg" alt=""></div>
            <div class="swiper-slide"><img src="images/slide-5.jpg" alt=""></div>
            <div class="swiper-slide"><img src="images/slide-6.jpg" alt=""></div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="service" id="service">
    <h1 class="heading"> our <span>services</span> </h1>
    <div class="box-container">
        <?php foreach($services as $service): ?>
        <div class="box">
            <i class="<?= htmlspecialchars($service['icon']) ?>"></i>
            <h3><?= htmlspecialchars($service['title']) ?></h3>
            <p><?= htmlspecialchars($service['description']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- About Section -->
<section class="about" id="about">
    <h1 class="heading"><span>about</span> us</h1>
    <div class="row">
        <div class="image">
            <img src="images/about-img.jpg" alt="">
        </div>
        <div class="content">
            <h3>we will give a very special celebration for you</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ullam labore fugiat ut esse perferendis perspiciatis provident dolores fuga in facilis culpa possimus, quia praesentium itaque, sapiente quasi harum rem asperiores.</p>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugiat vero expedita incidunt provident quibusdam aut odit, numquam nesciunt similique nisi.</p>
            <a href="#contact" class="btn">contact us</a>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery" id="gallery">
    <h1 class="heading">our <span>gallery</span></h1>
    <div class="box-container">
        <?php foreach($gallery as $item): ?>
        <div class="box">
            <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="">
            <h3 class="title"><?= htmlspecialchars($item['title']) ?></h3>
            <div class="icons">
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share"></a>
                <a href="#" class="fas fa-eye"></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Price Section -->
<section class="price" id="price">
    <h1 class="heading"> our <span>price</span> </h1>
    <div class="box-container">
        <?php foreach($pricePlans as $plan): 
            $features = explode(',', $plan['features']);
        ?>
        <div class="box">
            <h3 class="title"><?= htmlspecialchars($plan['title']) ?></h3>
            <h3 class="amount">$<?= number_format($plan['amount'], 2) ?></h3>
            <ul>
                <?php foreach($features as $feature): ?>
                <li><i class="fas fa-check"></i><?= htmlspecialchars(trim($feature)) ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="#contact" class="btn">check out</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Review Section -->
<section class="reivew" id="review">
    <h1 class="heading">client's <span>review</span></h1>
    <div class="review-slider swiper-container">
        <div class="swiper-wrapper">
            <?php foreach($reviews as $review): ?>
            <div class="swiper-slide box">
                <i class="fas fa-quote-right"></i>
                <div class="user">
                    <img src="<?= htmlspecialchars($review['image_path']) ?>" alt="">
                    <div class="user-info">
                        <h3><?= htmlspecialchars($review['name']) ?></h3>
                        <span><?= htmlspecialchars($review['role']) ?></span>
                    </div>
                </div>
                <p><?= htmlspecialchars($review['review_text']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact" id="contact">
    <h1 class="heading"> <span>contact</span> us </h1>
    <?php if($success): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="inputBox">
            <input type="text" name="name" placeholder="name" required>
            <input type="email" name="email" placeholder="email" required>
        </div>
        <div class="inputBox">
            <input type="number" name="number" placeholder="number" required>
            <input type="text" name="subject" placeholder="subject" required>
        </div>
        <textarea name="message" placeholder="your message" rows="10" required></textarea>
        <input type="submit" name="submit_contact" value="send message" class="btn">
    </form>
</section>

<!-- Footer -->
<section class="footer">
    <div class="box-container">
        <div class="box">
            <h3>branches</h3>
            <a href="#"><i class="fas fa-map-marker-alt"></i> mumbai</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> jogeshwari</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> goregaon</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> navi mumbai</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> andheri</a>
        </div>
        <div class="box">
            <h3>quick links</h3>
            <a href="#home"><i class="fas fa-arrow-right"></i> home</a>
            <a href="#service"><i class="fas fa-arrow-right"></i> service</a>
            <a href="#about"><i class="fas fa-arrow-right"></i> about</a>
            <a href="#gallery"><i class="fas fa-arrow-right"></i> gallery</a>
            <a href="#price"><i class="fas fa-arrow-right"></i> price</a>
            <a href="#review"><i class="fas fa-arrow-right"></i> review</a>
            <a href="#contact"><i class="fas fa-arrow-right"></i> contact</a>
        </div>
        <div class="box">
            <h3>contact info</h3>
            <a href="#"><i class="fas fa-phone"></i> +123-456-7890</a>
            <a href="#"><i class="fas fa-phone"></i> +111-222-3333</a>
            <a href="#"><i class="fas fa-envelope"></i> info@evento.com</a>
            <a href="#"><i class="fas fa-map-marker-alt"></i> mumbai, india - 400104</a>
        </div>
        <div class="box">
            <h3>follow us</h3>
            <a href="#"><i class="fab fa-facebook-f"></i> facebook</a>
            <a href="#"><i class="fab fa-twitter"></i> twitter</a>
            <a href="#"><i class="fab fa-instagram"></i> instagram</a>
            <a href="#"><i class="fab fa-linkedin"></i> linkedin</a>
        </div>
    </div>
    <div class="credit"> created by <span>Evento Team</span> | all rights reserved </div>
</section>

<div class="theme-toggler">
    <div class="toggle-btn">
        <i class="fas fa-cog"></i>
    </div>
    <h3>choose color</h3>
    <div class="buttons">
        <div class="theme-btn" style="background: #3867d6;"></div>
        <div class="theme-btn" style="background: #f7b731;"></div>
        <div class="theme-btn" style="background: #ff0033;"></div>
        <div class="theme-btn" style="background: #20bf6b;"></div>
        <div class="theme-btn" style="background: #fa8231;"></div>
        <div class="theme-btn" style="background: #FC427B;"></div>
    </div>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>