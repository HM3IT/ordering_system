<?php
if (!isset($_SESSION)) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php require "./components/base-link.php" ?>
    <link rel="stylesheet" href="css/about-us.css">
    <link rel="stylesheet" href="css/faq-section.css">

</head>

<body>
    <?php

    define('COMPONENTS_PATH', './pages/');
    require COMPONENTS_PATH . 'navbar.php';
    ?>


    <section class="parallax-wrapper">


        <img src="../images/About-us/sky.png" alt="sky" class="parallax-bg-sky">
        <img src="../images/About-us/cloud-left1.png" alt="cloud left 1" class="parallax-bg-cloud-left1">
        <img src="../images/About-us/cloud-left2.png" alt="cloud left 2" class="parallax-bg-cloud-left2">
        <img src="../images/About-us/cloud-left3.png" alt="cloud left 2" class="parallax-bg-cloud-left3">
        <img src="../images/About-us/cloud-left4.png" alt="cloud left 2" class="parallax-bg-cloud-left4">
        <img src="../images/About-us/cloud-top.png" alt="cloud top" class="parallax-bg-cloud-top">
        <h2 id="title">Hello World</h2>
        <img src="../images/About-us/cloud-right1.png" alt="cloud right 1" class="parallax-bg-cloud-right1">
        <img src="../images/About-us/cloud-right2.png" alt="cloud right 2" class="parallax-bg-cloud-right2">
        <img src="../images/About-us/mountain-left1.png" alt="mountain left 1" class="parallax-bg-mountain-left1">
        <img src="../images/About-us/mountain-left2.png" alt="mountain left 2" class="parallax-bg-mountain-left2">
        <img src="../images/About-us/mountain-both-side.png" alt="mountain both side" class="parallax-bg-mountain-both-side">
        <img src="../images/About-us/sun.png" alt="sun" class="parallax-bg-sun">
        <img src="../images/About-us/meadow.png" alt="meadow" class="parallax-bg-meadow">
        <img src="../images/About-us/girl.png" alt="girl" class="parallax-bg-girl" id="girl">
        <img src="../images/About-us/mountain-right1.png" alt="mountain right1" class="parallax-bg-mountain-right1">


    </section>
    <section class="content-section">
        <div class="hidden">
            <h2 class="about-title">About us</h2>
            <p class="background-info">
                Welcome to our online store, your ultimate destination for stylish and high-quality clothing. At our website, we specialize in three categories: summer clothes, winter clothes, and rainy clothes. Our mission is to provide you with fashionable options that keep you comfortable and confident in every season. Whether you're preparing for a sunny getaway, bundling up for the cold winter months, or looking for rain-ready attire, we have you covered. We curate a diverse collection of clothing items, carefully selected for their quality, durability, and trendy designs. Each piece is crafted with attention to detail, ensuring both style and functionality. From breezy summer dresses to cozy winter coats and waterproof rain jackets, our range of clothing is designed to meet your specific needs and preferences.
            </p>
            <p class="background-info">
                We understand that fashion is a form of self-expression, and our goal is to help you showcase your unique style effortlessly. Browse through our carefully organized categories, explore our latest arrivals, and discover the perfect outfits to suit your personal taste and lifestyle. With our secure and user-friendly online platform, shopping with us is convenient and enjoyable. Our dedicated team is committed to providing exceptional customer service, assisting you with any inquiries or concerns you may have.

            </p>
            <p class="background-info">
                Thank you for choosing <span class="primary">HM3</span>. We are excited to be a part of your fashion journey and look forward to helping you find the perfect clothing pieces that make you look and feel amazing.

            </p>

        </div>
        <div class="hidden" id="mission-vision-anchor">
            <h2 class="about-title"> Mission </h2>
            <p class="background-info">
                At H3IN, our mission is to empower individuals to express their personal style through high-quality clothing. We aim to provide a seamless and enjoyable shopping experience, offering a wide selection of trendy and comfortable clothing options for every season. Our goal is to inspire confidence and help our customers feel their best, no matter the occasion.
            </p>
        </div>
        <div class="hidden">
            <h2 class="about-title"> Vision </h2>
            <p class="background-info">
                Our vision is to become the go-to destination for fashion enthusiasts seeking top-notch clothing that perfectly suits their style and needs. We strive to build long-lasting relationships with our customers by consistently delivering exceptional products and exceptional service. Through continuous innovation and a customer-centric approach, we aim to create a community where everyone can explore their fashion preferences, find inspiration, and embrace their individuality.
            </p>
            <p class="background-info">
                We are committed to curating a diverse collection of clothing items that cater to different tastes, body types, and lifestyles. Our vision is to be at the forefront of fashion trends, providing our customers with access to the latest styles while maintaining a focus on quality and affordability. As we grow, we remain dedicated to sustainability and ethical practices. We aim to collaborate with eco-conscious brands and prioritize eco-friendly materials, contributing to a more sustainable fashion industry.

                Join us on this exciting fashion journey, and let us help you define your unique style with confidence and convenience.
            </p>
        </div>
    </section>
    <section id="our-team-section">
        <h2>Meet Our Team</h2>
        <div class="team-member-card-container">

            <div class="card-group">
                <div class="team-member hidden">
                    <div class="team-member-card">
                        <div class="team-member-card-img admin"></div>
                        <h2 class="name">Hein Min Min Maw </h2>
                        <h3 class="role">(Admin)</h3>
                    </div>
                </div>
                <div class="team-member hidden">
                    <div class="team-member-card">
                        <div class="team-member-card-img customer-service"></div>
                        <h2 class="name">Kaung Set Hein </h2>
                        <h3 class="role">(Customer Service)</h3>
                    </div>
                </div>
            </div>
            <div class="card-group">
            <div class="team-member hidden">
                <div class="team-member-card">
                    <div class="team-member-card-img finance"></div>
                    <h2 class="name">Kyal Sin Hein</h2>
                    <h3 class="role">(Finance)</h3>
                </div>
            </div>
            <div class="team-member hidden">
                <div class="team-member-card">
                    <div class="team-member-card-img business-develop"></div>
                    <h2 class="name">Sai Lon Aung</h2>
                    <h3 class="role">(Business Development)</h3>
                </div>
            </div>
            </div>
        </div>
    </section>

    <?php
    require COMPONENTS_PATH . 'faq-section.php';
    require COMPONENTS_PATH . 'footer.html';
    ?>
    <script src="scripts/navbar.js"> </script>
    <script src="scripts/about-us-parallax.js"> </script>
    <script src="scripts/faq-section.js"></script>
    <script src="scripts/footer.js"></script>
</body>

</html>