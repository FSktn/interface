<?php
require_once 'includes/database.php';

try {
    $db = new Database();
    $producten = $db->getAllProducts();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producten - Maison van Dijk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0e0e0e;
            color: #e0e0e0;
            font-family: 'Raleway', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        
        .kaart, .kenmerk, .contactdoos {
            background-color: #171717;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 1px solid #936c39;
        }
        
        .product-kaart {
            background-color: #171717;
            overflow: hidden;
            transition: transform 0.3s;
            margin-bottom: 20px;
            position: relative;
            border: 1px solid #333;
        }
        
        .product-kaart:hover {
            transform: translateY(-5px);
            border-color: #936c39;
        }
        
        .product-kaart img {
            height: 300px;
            width: 100%;
            object-fit: cover;
            background-color: #333;
        }
        
        .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background-color: rgba(23, 23, 23, 0.9);
            border-left: 3px solid #936c39;
        }
        
        .titel {
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .titel:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: #936c39;
        }
        
        .navigatie {
            background-color: rgba(10, 10, 10, 0.95) !important;
            border-bottom: 1px solid #936c39;
        }
        
        .logo {
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
        }
        
        .voeter {
            background-color: #0a0a0a;
            border-top: 1px solid #936c39;
        }
        
        .goudknop {
            background-color: #936c39 !important;
            border-color: #936c39 !important;
            border-radius: 0;
            padding: 10px 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .goudknop:hover {
            background-color: #7d5b2e !important;
        }
        
        .goud-tekst {
            color: #936c39;
        }
        
        .placeholder-img {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #333;
            color: #666;
            font-size: 3rem;
        }
        
        .prijs {
            font-size: 1.2rem;
            font-weight: 600;
            color: #936c39;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navigatie fixed-top">
        <div class="container">
            <a class="navbar-brand logo text-white" href="index.html">
                <i class="fas fa-gem me-2 goud-tekst"></i>Maison van Dijk
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="producten.php">Producten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="product_toevoegen.php">Product Toevoegen</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="py-5" style="margin-top: 80px;">
        <div class="container">
            <h1 class="titel">Onze Collectie</h1>
            <p class="text-center mb-5">Ontdek onze exclusieve collectie van maatpakken en luxe herenkleding</p>
        </div>
    </section>

    <!-- Producten Grid -->
    <section class="py-5">
        <div class="container">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Fout bij laden van producten: <?= escapeHtml($error) ?>
                </div>
            <?php elseif (empty($producten)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Geen producten gevonden. <a href="product_toevoegen.php" class="text-decoration-none goud-tekst">Voeg het eerste product toe</a>.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($producten as $product): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="product-kaart">
                                <?php if (!empty($product['afbeelding']) && file_exists("images/" . $product['afbeelding'])): ?>
                                    <img src="images/<?= escapeHtml($product['afbeelding']) ?>" 
                                         alt="<?= escapeHtml($product['naam']) ?>">
                                <?php else: ?>
                                    <div class="placeholder-img" style="height: 300px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="overlay">
                                    <h5 class="mb-2"><?= escapeHtml($product['naam']) ?></h5>
                                    <?php if (!empty($product['maat'])): ?>
                                        <p class="mb-1">
                                            <small><i class="fas fa-ruler me-1"></i> Maat: <?= strtoupper(escapeHtml($product['maat'])) ?></small>
                                        </p>
                                    <?php endif; ?>
                                    <p class="prijs mb-3"><?= Database::formatPrice($product['prijs']) ?></p>
                                    <a href="product_detail.php?id=<?= $product['id'] ?>" class="btn goudknop btn-sm">
                                        <i class="fas fa-eye me-1"></i> Bekijk Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="voeter text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Maison van Dijk</h5>
                    <p>Exclusieve maatpakken & handgemaakte luxe herenkleding voor de moderne gentleman.</p>
                    <p class="mt-3">Opening op 08-08-2024!</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Contact</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Kruiskade 88, Rotterdam</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> 010-1234567</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@maisonvandijk.nl</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Volg ons</h5>
                    <div class="socials">
                        <a href="#" class="me-3"><i class="fab fa-instagram goud-tekst"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-facebook goud-tekst"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-linkedin goud-tekst"></i></a>
                        <a href="#"><i class="fab fa-pinterest goud-tekst"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: #333;">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Maison van Dijk. Alle rechten voorbehouden.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>