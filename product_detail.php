<?php
require_once 'includes/database.php';

$product = null;
$error = null;

// Controleer of er een product ID is meegegeven
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $error = 'Geen geldig product ID opgegeven';
} else {
    try {
        $db = new Database();
        $product = $db->getProductById($_GET['id']);
        
        if (!$product) {
            $error = 'Product niet gevonden';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product ? escapeHtml($product['naam']) . ' - ' : '' ?>Maison van Dijk</title>
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
        
        .product-detail-kaart {
            background-color: #171717;
            padding: 30px;
            border: 1px solid #333;
        }
        
        .product-img {
            max-height: 500px;
            width: 100%;
            object-fit: cover;
            border: 1px solid #333;
        }
        
        .placeholder-img {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #333;
            color: #666;
            font-size: 5rem;
            height: 500px;
            border: 1px solid #333;
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
        
        .prijs {
            font-size: 2rem;
            font-weight: 700;
            color: #936c39;
        }
        
        .product-info {
            border-left: 3px solid #936c39;
            padding-left: 20px;
        }
        
        .info-item {
            border-bottom: 1px solid #333;
            padding: 15px 0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #936c39;
            display: inline-block;
            width: 120px;
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
                        <a class="nav-link text-white" href="producten.php">Producten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="product_toevoegen.php">Product Toevoegen</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Product Detail -->
    <section class="py-5" style="margin-top: 80px;">
        <div class="container">
            <div class="mb-4">
                <a href="producten.php" class="text-decoration-none goud-tekst">
                    <i class="fas fa-arrow-left me-2"></i>Terug naar producten
                </a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= escapeHtml($error) ?>
                </div>
                <div class="text-center">
                    <a href="producten.php" class="btn goudknop">
                        <i class="fas fa-arrow-left me-2"></i>Ga naar producten
                    </a>
                </div>
            <?php else: ?>
                <div class="row">
                    <!-- Product Afbeelding -->
                    <div class="col-lg-6 mb-4">
                        <?php if (!empty($product['afbeelding']) && file_exists("images/" . $product['afbeelding'])): ?>
                            <img src="images/<?= escapeHtml($product['afbeelding']) ?>" 
                                 alt="<?= escapeHtml($product['naam']) ?>"
                                 class="product-img">
                        <?php else: ?>
                            <div class="placeholder-img">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Product Informatie -->
                    <div class="col-lg-6">
                        <div class="product-detail-kaart">
                            <h1 class="mb-4"><?= escapeHtml($product['naam']) ?></h1>
                            
                            <div class="prijs mb-4">
                                <?= Database::formatPrice($product['prijs']) ?>
                            </div>
                            
                            <?php if (!empty($product['omschrijving'])): ?>
                                <div class="mb-4">
                                    <h5 class="goud-tekst mb-3">Beschrijving</h5>
                                    <p class="lead"><?= nl2br(escapeHtml($product['omschrijving'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="product-info">
                                <h5 class="goud-tekst mb-3">Product Details</h5>
                                
                                <div class="info-item">
                                    <span class="info-label">Product ID:</span>
                                    <span>#<?= $product['id'] ?></span>
                                </div>
                                
                                <?php if (!empty($product['maat'])): ?>
                                    <div class="info-item">
                                        <span class="info-label">Maat:</span>
                                        <span class="badge bg-secondary"><?= strtoupper(escapeHtml($product['maat'])) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($product['afbeelding'])): ?>
                                    <div class="info-item">
                                        <span class="info-label">Afbeelding:</span>
                                        <span><?= escapeHtml($product['afbeelding']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-4">
                                <a href="#" class="btn goudknop me-3" onclick="alert('Contact opnemen functionaliteit nog toe te voegen')">
                                    <i class="fas fa-phone me-2"></i>Contact Opnemen
                                </a>
                                <a href="producten.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-th me-2"></i>Meer Producten
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gerelateerde Producten Suggestie -->
                <div class="mt-5">
                    <div class="text-center">
                        <h3 class="goud-tekst mb-4">Interesse in maatwerk?</h3>
                        <p class="lead mb-4">
                            Ontdek onze volledige collectie of maak een afspraak voor een persoonlijke consultatie.
                        </p>
                        <a href="producten.php" class="btn goudknop me-3">
                            <i class="fas fa-th me-2"></i>Alle Producten
                        </a>
                        <a href="index.html#contact" class="btn btn-outline-secondary">
                            <i class="fas fa-calendar me-2"></i>Afspraak Maken
                        </a>
                    </div>
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