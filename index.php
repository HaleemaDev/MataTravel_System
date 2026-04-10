<?php 

    include 'includes/header.php'; 
    include 'includes/db.php'; 
    session_start();

?>


<div id="heroCarousel" class="carousel slide carousel-fade shadow" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('assets/images/hero1.webp') center/cover; height: 600px;">
            <div class="carousel-caption d-none d-md-block pb-5">
                <h1 class="display-3 fw-bold">Discover Matara</h1>
                <p class="lead">Where the sun meets the southern horizon.</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="explore.php" class="btn btn-warning btn-lg">Start Exploring</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-warning btn-lg">Start Exploring</a>
                    <?php endif; ?>
            </div>
        </div>
        <div class="carousel-item" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('assets/images/hero2.png') center/cover; height: 600px;">
            <div class="carousel-caption d-none d-md-block pb-5">
                <h1 class="display-3 fw-bold">History & Heritage</h1>
                <p class="lead">Visit the iconic Star Fort and Dondra Head Lighthouse.</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="explore.php" class="btn btn-warning btn-lg">Start Exploring</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-warning btn-lg">Start Exploring</a>
                    <?php endif; ?>
            </div>
        </div>
        <div class="carousel-item" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('assets/images/hero3.png') center/cover; height: 600px;">
            <div class="carousel-caption d-none d-md-block pb-5">
                <h1 class="display-3 fw-bold">Coastal Paradis</h1>
                <p class="lead">Safe snorkeling and golden sands at Polhena Beach.</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="explore.php" class="btn btn-warning btn-lg">Start Exploring</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-warning btn-lg">Start Exploring</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<section class="container my-5">
    <div class="row align-items-center">

        <!-- LEFT CONTENT -->
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4"><span style="color:blue;">Plan Smarter</span>,<span style="color:green;"> Travel Better</span></h2>

            <p class="text-muted" style="font-size: 18px; line-height: 1.8;">
                Exploring Matara in just one day can be challenging. Travelers often spend more time searching for places 
                than actually enjoying them. Information is scattered across multiple platforms, making it difficult to 
                decide where to go and how to plan efficiently.
            </p>

            <p class="text-muted" style="font-size: 18px; line-height: 1.8;">
                That’s where <strong>MataTravel</strong> makes a difference. We bring everything together in one place — 
                helping you discover nearby attractions, understand travel distances, and organize your journey with ease.
            </p>

            <p class="text-muted" style="font-size: 18px; line-height: 1.8;">
                Designed especially for short trips, our platform allows you to create a complete one-day travel plan 
                within a <strong>25 km radius of Matara</strong>, saving time and maximizing your experience.
            </p>
        </div>

        <!-- RIGHT SIDE (HIGHLIGHT BOXES) -->
        <div class="col-lg-6">
            <div class="row g-3">

                <div class="col-6">
                    <div class="p-4 border rounded shadow-sm text-center h-100 bg-col">
                        <h4 class="fw-bold text-primary">25km</h4>
                        <p class="text-muted mb-0">Smart travel radius</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="p-4 border rounded shadow-sm text-center h-100 bg-col">
                        <h4 class="fw-bold text-success">1 Day</h4>
                        <p class="text-muted mb-0">Perfect trip planning</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="p-4 border rounded shadow-sm text-center h-100 bg-col">
                        <h4 class="fw-bold" style="color:$orange-500;">All-in-One</h4>
                        <p class="text-muted mb-0">No more scattered info</p>
                    </div>
                </div>

                <div class="col-6">
                    <div class="p-4 border rounded shadow-sm text-center h-100 bg-col">
                        <h4 class="fw-bold text-danger">Easy</h4>
                        <p class="text-muted mb-0">Simple planning experience</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container my-5">
    <!-- Home page card 1 -->
    <div class="card mb-4 shadow-sm" style="min-height: 450px;">
    <div class="row g-0 h-100">
        
        <!-- Image -->
        <div class="col-md-5">
        <img src="assets/images/home_card1.png"
            alt="Matara City"
            style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        <!-- Content -->
        <div class="col-md-7">
        <div class="card-body d-flex flex-column justify-content-center h-100" style="padding: 30px;">       
            <h3 class="card-title mb-3">Welcome to Matara</h3>
            <p class="card-text" style="font-size: 20px; line-height: 1.7;">
            Matara is a lively coastal city in southern Sri Lanka that beautifully combines natural beauty, history, and modern living. 
            Known for its relaxing seaside atmosphere and friendly community, Matara offers visitors a peaceful escape from busy urban life. 
            The city is rich in heritage, with influences from colonial times still visible in its architecture and culture. 
            At the same time, it continues to grow with modern facilities, making it comfortable for travelers. 
            Whether you are looking to unwind by the ocean, explore local traditions, or simply enjoy the calm environment, Matara provides a perfect balance of relaxation and discovery.
            </p>
            <p class="card-text mt-3">
            <small class="text-body-secondary">Discover the southern charm</small>
            </p>
        </div>
        </div>
    </div>
    </div>

    <!-- Home page card 2 -->
    <div class="card mb-4 shadow-sm" style="min-height: 450px;">
    <div class="row g-0 h-100">
        <!-- Content -->
        <div class="col-md-7">
        <div class="card-body d-flex flex-column justify-content-center h-100" style="padding: 30px;">       
            <h3 class="card-title mb-3">Culture & Lifestyle</h3>
            <p class="card-text" style="font-size: 20px; line-height: 1.7;">
                The culture and lifestyle in Matara reflect the true essence of Sri Lankan traditions blended with a touch of modern influence. 
                Daily life in Matara is simple, warm, and deeply connected to community values. 
                Visitors can experience authentic local living through bustling markets, traditional food, and friendly interactions with residents. 
                Festivals, religious practices, and cultural events play an important role in everyday life, giving travelers a chance to witness meaningful traditions firsthand. 
                The coastal lifestyle also adds a unique charm, where fishing, evening walks, and enjoying fresh seafood are part of the daily routine.
            </p>
            <p class="card-text mt-3">
            <small class="text-body-secondary">Experience true Sri Lankan culture</small>
            </p>
        </div>
        </div>
        <!-- Image -->
        <div class="col-md-5">
        <img src="assets/images/home_card2.png"
            alt="Matara City"
            style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>
    </div>

    <!-- Home page card 3 -->
    <div class="card mb-4 shadow-sm" style="min-height: 450px;">
        <div class="row g-0 h-100">            
            <!-- Image -->
            <div class="col-md-5">
                <img src="assets/images/home_card3.png"
                    alt="Matara City"
                    style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <!-- Content -->
            <div class="col-md-7">
                <div class="card-body d-flex flex-column justify-content-center h-100" style="padding: 30px;">       
                    <h3 class="card-title mb-3">Why Visit Matara?</h3>
                    <p class="card-text" style="font-size: 20px; line-height: 1.7;">
                        Ideal destination for travelers who seek both relaxation and exploration in one place. 
                        Its calm environment, welcoming atmosphere, and scenic surroundings make it perfect for a peaceful getaway. 
                        Unlike crowded tourist hotspots, Matara offers a more authentic and less commercialized experience, allowing visitors to truly connect with the destination. 
                        The city is easily accessible and provides a variety of experiences, from enjoying the coastal breeze to discovering cultural richness. 
                        Whether you are traveling with family, friends, or solo, Matara promises memorable moments and a refreshing break from everyday life.
                    </p>
                    <p class="card-text mt-3">
                    <small class="text-body-secondary">Your perfect getaway destination</small>
                    </p>
                </div>
            </div>
        </div>
    </div>

     <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="text-primary mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-tsunami" viewBox="0 0 16 16">
                        <path d="M.036 12.314a.5.5 0 0 1 .65-.278l1.757.703a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.757-.703a.5.5 0 1 1 .371.928l-1.758.703a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.014.406a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.014.406a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.758.703a.5.5 0 0 1-.65-.278zM0 10.77q.066.01.134.01a.5.5 0 0 0 .37-.16l1.201-1.352a1.5 1.5 0 0 1 2.247 0l1.014 1.141a2.5 2.5 0 0 0 3.746 0l1.014-1.141a1.5 1.5 0 0 1 2.247 0l1.201 1.352a.5.5 0 1 0 .741-.673l-1.201-1.352a2.5 2.5 0 0 0-3.746 0l-1.014 1.141a1.5 1.5 0 0 1-2.247 0L2.079 8.595a2.5 2.5 0 0 0-3.746 0L1.75 4.341a.5.5 0 1 0-.741-.673L1.01 3.668a1.5 1.5 0 0 1 2.247 0l1.014 1.141a2.5 2.5 0 0 0 3.746 0l1.014-1.141a1.5 1.5 0 0 1 2.247 0l1.201 1.352a.5.5 0 1 0 .741-.673l-1.201-1.352a2.5 2.5 0 0 0-3.746 0L9.255 3.668a1.5 1.5 0 0 1-2.247 0L5.994 2.527a2.5 2.5 0 0 0-3.746 0L1.047 3.879a.5.5 0 1 0 .741.673z"/>
                    </svg>
                </div>
                <h3>Beautiful Beaches</h3>
                <p class="text-muted">Explore tropical shorelines from Polhena to Hiriketiya, ideal for surfing and snorkeling.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="text-success mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16">
                        <path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1 3.777 3zM2 6v7h1V6H2zm2 0v7h1V6H4zm2 0v7h1V6H6zm2 0v7h1V6H8zm2 0v7h1V6h-1zm2 0v7h1V6h-1zM1 14v1h14v-1H1z"/>
                    </svg>
                </div>
                <h3>Rich History</h3>
                <p class="text-muted">Step back in time at the colonial Star Fort and the Weherahena Temple's unique architecture.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="text-warning mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                        <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                </div>
                <h3>Easy Planning</h3>
                <p class="text-muted">Use our smart itinerary builder to save locations and organize your daily travel schedule.</p>
            </div>
        </div>
    </div>
</section>

 

    <div class="text-white py-5" style="background-color:#0056B3;">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <h2 class="fw-bold">15+</h2>
                    <p>Top Destinations</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold">24/7</h2>
                    <p>Tourist Support</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold">100%</h2>
                    <p>Free to Plan</p>
                </div>
                <div class="col-md-3">
                    <h2 class="fw-bold">25km</h2>
                    <p>Radius</p>
                </div>
            </div>
        </div>
    </div>

<script>
        if (isset($_GET['redirect'])) {
            header("Location: " . $_GET['redirect']);
    } else {
            header("Location: explore.php");
        }
    exit();
</script>
<?php include 'includes/footer.php'; ?>