<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maison van Dijk - Producten</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0e0e0e;
            color: #e0e0e0;
            font-family: 'Raleway', sans-serif;
        }
        
        .product-card {
            background-color: #171717;
            border-left: 3px solid #936c39;
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-card img {
            height: 200px;
            width: 100%;
            object-fit: cover;
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
            font-size: 1.2rem;
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
                <a class="nav-link active" href="producten.php">Producten</a>
                <a class="nav-link" href="product_toevoegen.php">Product Toevoegen</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-5">Onze Producten</h1>
        
        <div class="row">
            <?php
            require_once 'includes/database.php';
            
            $product = new Product();
            $producten = $product->getAllProducts();
            
            if (empty($producten)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Er zijn nog geen producten beschikbaar.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($producten as $item): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card product-card h-100">
                            <?php if (!empty($item['afbeelding'])): ?>
                                <img src="images/<?php echo htmlspecialchars($item['afbeelding']); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($item['naam']); ?>"
                                     onerror="this.src='https://via.placeholder.com/300x200?text=Geen+Afbeelding'">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x200?text=Geen+Afbeelding" 
                                     class="card-img-top" 
                                     alt="Geen afbeelding">
                            <?php endif; ?>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['naam']); ?></h5>
                                
                                <?php if (!empty($item['omschrijving'])): ?>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($item['omschrijving'], 0, 100)); ?>
                                    <?php if (strlen($item['omschrijving']) > 100) echo '...'; ?></p>
                                <?php endif; ?>
                                
                                <div class="mt-auto">
                                    <?php if (!empty($item['maat'])): ?>
                                        <span class="badge bg-secondary mb-2">Maat: <?php echo strtoupper(htmlspecialchars($item['maat'])); ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($item['prijs'])): ?>
                                        <p class="price mb-2"><?php echo formatPrice($item['prijs']); ?></p>
                                    <?php endif; ?>
                                    
                                    <a href="product_detail.php?id=<?php echo $item['id']; ?>" 
                                       class="btn btn-gold w-100">
                                        <i class="fas fa-eye me-2"></i>Bekijk Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="product_toevoegen.php" class="btn btn-gold btn-lg">
                <i class="fas fa-plus me-2"></i>Nieuw Product Toevoegen
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
