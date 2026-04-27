<section class="hero">
    <div class="hero-bg" style="background-image: url('<?php echo asset('assets/images/hero.png'); ?>');"></div>
    <div class="hero-content">
        <p class="tagline">Welcome to Luxury</p>
        <h2>Experience <span>Unforgettable</span> Moments</h2>
        <p>Discover the perfect blend of elegance, comfort, and world-class hospitality at Dorado Salgado Grand Hotel. Your dream getaway starts here.</p>
        <div class="hero-buttons">
            <a href="<?php echo base_url('reservation.php'); ?>" class="btn btn-primary btn-lg"><i class="fas fa-calendar-check"></i> Book Now</a>
            <?php if (!$isLoggedIn): ?>
                <a href="<?php echo base_url('login.php'); ?>" class="btn btn-secondary btn-lg"><i class="fas fa-user"></i> Get Started</a>
            <?php else: ?>
                <a href="<?php echo base_url('my-bookings.php'); ?>" class="btn btn-secondary btn-lg"><i class="fas fa-calendar-alt"></i> My Bookings</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-scroll"><a href="#rooms"><i class="fas fa-chevron-down"></i></a></div>
</section>

<section class="section" id="rooms">
    <div class="container">
        <div class="section-header">
            <h2>Our Rooms &amp; Suites</h2>
            <p class="subtitle">Discover handcrafted spaces designed for your ultimate comfort</p>
            <div class="gold-line"></div>
        </div>
        <div class="rooms-grid">
            <?php foreach ($rooms as $room): ?>
            <div class="room-card fade-in-up">
                <div class="room-image">
                    <img src="<?php echo asset($room['image']); ?>" alt="<?php echo e($room['name']); ?>">
                    <span class="room-badge"><?php echo e($room['capacity_type']); ?> &middot; <?php echo e($room['room_type']); ?></span>
                </div>
                <div class="room-details">
                    <h3><?php echo e($room['name']); ?></h3>
                    <p><?php echo e($room['description']); ?></p>
                    <div class="room-meta">
                        <div class="room-price">&#8369;<?php echo number_format((float) $room['price'], 2); ?> <small>/ day</small></div>
                        <div class="room-capacity"><i class="fas fa-tag"></i> <?php echo e($room['room_type']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:40px;">
            <a href="<?php echo base_url('reservation.php'); ?>" class="btn btn-primary btn-lg"><i class="fas fa-calendar-check"></i> Book Now</a>
        </div>
    </div>
</section>

<section class="section" style="background: var(--white);">
    <div class="container">
        <div class="section-header">
            <h2>Hotel Amenities</h2>
            <p class="subtitle">Everything you need for a perfect stay</p>
            <div class="gold-line"></div>
        </div>
        <div class="amenities-grid">
            <div class="amenity-card">
                <div class="amenity-icon"><i class="fas fa-swimming-pool"></i></div>
                <h4>Infinity Pool</h4>
                <p>Relax at our rooftop infinity pool with stunning views</p>
            </div>
            <div class="amenity-card">
                <div class="amenity-icon"><i class="fas fa-spa"></i></div>
                <h4>Spa &amp; Wellness</h4>
                <p>Rejuvenate with our premium spa treatments</p>
            </div>
            <div class="amenity-card">
                <div class="amenity-icon"><i class="fas fa-utensils"></i></div>
                <h4>Fine Dining</h4>
                <p>Savor exquisite cuisines from world-class chefs</p>
            </div>
            <div class="amenity-card">
                <div class="amenity-icon"><i class="fas fa-wifi"></i></div>
                <h4>Free High-Speed WiFi</h4>
                <p>Stay connected with complimentary internet access</p>
            </div>
            <div class="amenity-card">
                <div class="amenity-icon"><i class="fas fa-dumbbell"></i></div>
                <h4>Fitness Center</h4>
                <p>State-of-the-art equipment for your workout routine</p>
            </div>
            <div class="amenity-card">
                <div class="amenity-icon"><i class="fas fa-concierge-bell"></i></div>
                <h4>24/7 Concierge</h4>
                <p>Our dedicated staff is available around the clock</p>
            </div>
            <div class="amenity-card">
                <div class="amenity-icon"><i class="fas fa-car"></i></div>
                <h4>Valet Parking</h4>
                <p>Complimentary valet parking for all hotel guests</p>
            </div>
            <div class="amenity-card">
                <div class="amenity-icon"><i class="fas fa-cocktail"></i></div>
                <h4>Sky Lounge Bar</h4>
                <p>Handcrafted cocktails with panoramic city views</p>
            </div>
        </div>
    </div>
</section>

<section class="section testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 style="color: var(--white);">Guest Reviews</h2>
            <p class="subtitle" style="color: rgba(255,255,255,0.6);">What our guests say about their experience</p>
            <div class="gold-line"></div>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p>"An absolutely incredible experience! The staff went above and beyond to make our anniversary unforgettable. The suite was breathtaking."</p>
                <div class="guest-info">
                    <div class="guest-avatar">AR</div>
                    <div><div class="guest-name">Ana Reyes</div><div class="guest-title">Honeymoon Guest</div></div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p>"Best hotel I've stayed at in the Philippines. The ocean view from the deluxe room was spectacular, and the dining experience was top-notch."</p>
                <div class="guest-info">
                    <div class="guest-avatar">JT</div>
                    <div><div class="guest-name">James Thompson</div><div class="guest-title">Business Traveler</div></div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                <p>"Perfect for a family vacation! The kids loved the pool, and we appreciated the excellent room service. Will definitely come back."</p>
                <div class="guest-info">
                    <div class="guest-avatar">MS</div>
                    <div><div class="guest-name">Maria Santos</div><div class="guest-title">Family Vacation</div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-banner">
    <div class="container">
        <h2>Ready for an Extraordinary Stay?</h2>
        <p>Book your room today and enjoy exclusive rates and premium amenities.</p>
        <a href="<?php echo base_url('reservation.php'); ?>" class="btn btn-dark btn-lg"><i class="fas fa-calendar-check"></i> Reserve Your Room</a>
    </div>
</section>
