<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Maison van Dijk</title>
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
        
        .product-detail {
            background-color: #171717;
            border-left: 3px solid #936c39;
            padding: 30px;
        }
        
        .product-image {
            max-height: 500px;
            width: 100%;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .btn-gold {
            background-color: #936c39;
            border-color: #936c39;
            color: white;
        }
        
        .btn-gold:hover {
            background-color: #7d5b2e;
            border-color: #7d5b2e;
            color: white;
        }
        
        .navbar {
            background-color: rgba(10, 10, 10, 0.95);
            border-bottom: 1px solid #936c39;
        }
        
        .price {
            color: #936c39;
            font-weight: bold;
            font-size: 2rem;
        }
        
        .product-info {
            background-color: #222;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Navigatie -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">Maison van Dijk</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.html">Home</a>
                <a class="nav-link" href="producten.php">Producten</a>
                <a class="nav-link" href="product_toevoegen.php">Product Toevoegen</a>
            </div>
        </div>
    </nav>

    <?php
    require_once 'includes/database.php';
    
    // Product ID ophalen en valideren
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $error_message = '';
    $item = null;
    
    if ($product_id <= 0) {
        $error_message = 'Ongeldig product ID.';
    } else {
        $product = new Product();
        $item = $product->getProductById($product_id);
        
        if (!$item) {
            $error_message = 'Product niet gevonden.';
        }
    }
    ?>

    <div class="container mt-5">
        <?php if ($error_message): ?>
            <div class="alert alert-<?= $product_id <= 0 ? 'danger' : 'warning' ?> text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
            <div class="text-center">
                <a href="producten.php" class="btn btn-gold">Terug naar Producten</a>
            </div>
        <?php else: ?>
            
        <div class="row">
            <div class="col-md-6 mb-4">
                <?php if (!empty($item['afbeelding'])): ?>
                    <img src="images/<?= htmlspecialchars($item['afbeelding']) ?>" 
                         class="product-image" 
                         alt="<?= htmlspecialchars($item['naam']) ?>"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="product-image d-none align-items-center justify-content-center bg-secondary text-white" style="height: 500px;">
                        <div class="text-center">
                            <i class="fas fa-image fa-3x mb-3"></i><br>
                            <span>Geen afbeelding beschikbaar</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="product-image d-flex align-items-center justify-content-center bg-secondary text-white" style="height: 500px;">
                        <div class="text-center">
                            <i class="fas fa-image fa-3x mb-3"></i><br>
                            <span>Geen afbeelding beschikbaar</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-6">
                <div class="product-detail h-100">
                    <h1 class="mb-4"><?= htmlspecialchars($item['naam']) ?></h1>
                    
                    <?php if (!empty($item['prijs'])): ?>
                        <p class="price mb-4"><?= formatPrice($item['prijs']) ?></p>
                    <?php endif; ?>
                    
                    <div class="product-info mb-4">
                        <h5><i class="fas fa-info-circle me-2"></i>Productinformatie</h5>
                        
                        <div class="row mt-3">
                            <div class="col-sm-4"><strong>Product ID:</strong></div>
                            <div class="col-sm-8">#<?= $item['id'] ?></div>
                        </div>
                        
                        <?php if (!empty($item['maat'])): ?>
                        <div class="row mt-2">
                            <div class="col-sm-4"><strong>Maat:</strong></div>
                            <div class="col-sm-8">
                                <span class="badge bg-secondary"><?= strtoupper(htmlspecialchars($item['maat'])) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($item['afbeelding'])): ?>
                        <div class="row mt-2">
                            <div class="col-sm-4"><strong>Afbeelding:</strong></div>
                            <div class="col-sm-8"><?= htmlspecialchars($item['afbeelding']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($item['omschrijving'])): ?>
                    <div class="mb-4">
                        <h5><i class="fas fa-file-text me-2"></i>Omschrijving</h5>
                        <p class="mt-3"><?= nl2br(htmlspecialchars($item['omschrijving'])) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="producten.php" class="btn btn-outline-light me-md-2">
                            <i class="fas fa-arrow-left me-2"></i>Terug naar Producten
                        </a>
                        <button class="btn btn-gold" onclick="alert('Contacteer ons voor meer informatie!')">
                            <i class="fas fa-envelope me-2"></i>Informatie Aanvragen
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
