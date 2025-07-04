<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Toevoegen - Maison van Dijk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0e0e0e;
            color: #e0e0e0;
            font-family: 'Raleway', sans-serif;
        }
        
        .form-container {
            background-color: #171717;
            border-left: 3px solid #936c39;
            padding: 30px;
            border-radius: 5px;
        }
        
        .form-control {
            background-color: #222;
            border: 1px solid #333;
            color: #e0e0e0;
        }
        
        .form-control:focus {
            background-color: #222;
            color: #e0e0e0;
            border-color: #936c39;
            box-shadow: 0 0 0 0.2rem rgba(147, 108, 57, 0.25);
        }
        
        .form-label {
            color: #e0e0e0;
            font-weight: 500;
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
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .invalid-feedback {
            color: #dc3545;
        }
        
        .alert-success {
            background-color: #155724;
            border-color: #c3e6cb;
            color: #d4edda;
        }
    </style>
</head>
<body>
    <!-- Navigatie -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="interface1/index.html">Maison van Dijk</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="interface1/index.html">Home</a>
                <a class="nav-link" href="producten.php">Producten</a>
                <a class="nav-link active" href="product_toevoegen.php">Product Toevoegen</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    <h1 class="mb-4"><i class="fas fa-plus-circle me-2"></i>Nieuw Product Toevoegen</h1>
                    
                    <?php
                    require_once 'includes/database.php';
                    
                    $errors = [];
                    $success = false;
                    $formData = [];
                    
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Formulierdata ophalen
                        $formData = [
                            'naam' => trim($_POST['naam'] ?? ''),
                            'omschrijving' => trim($_POST['omschrijving'] ?? ''),
                            'maat' => $_POST['maat'] ?? '',
                            'afbeelding' => trim($_POST['afbeelding'] ?? ''),
                            'prijs' => $_POST['prijs'] ?? ''
                        ];
                        
                        // Server-side validatie
                        $errors = validateProduct($formData);
                        
                        // Prijs converteren naar integer (centen)
                        if (!empty($formData['prijs']) && is_numeric($formData['prijs'])) {
                            $formData['prijs'] = (int)($formData['prijs'] * 100);
                        }
                        
                        // Als geen fouten, product opslaan
                        if (empty($errors)) {
                            try {
                                $product = new Product();
                                $result = $product->addProduct(
                                    $formData['naam'],
                                    $formData['omschrijving'] ?: null,
                                    $formData['maat'] ?: null,
                                    $formData['afbeelding'] ?: null,
                                    $formData['prijs'] ?: null
                                );
                                
                                if ($result) {
                                    $success = true;
                                    $formData = []; // Reset form
                                } else {
                                    $errors['general'] = 'Er is een fout opgetreden bij het opslaan van het product.';
                                }
                            } catch (Exception $e) {
                                $errors['general'] = 'Databasefout: ' . $e->getMessage();
                            }
                        }
                    }
                    ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Product succesvol toegevoegd!
                            <a href="producten.php" class="btn btn-sm btn-outline-success ms-3">Bekijk Alle Producten</a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($errors['general']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="naam" class="form-label">Productnaam *</label>
                            <input type="text" 
                                   class="form-control <?php echo isset($errors['naam']) ? 'is-invalid' : ''; ?>" 
                                   id="naam" 
                                   name="naam" 
                                   value="<?php echo htmlspecialchars($formData['naam'] ?? ''); ?>" 
                                   required>
                            <?php if (isset($errors['naam'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['naam']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="omschrijving" class="form-label">Omschrijving</label>
                            <textarea class="form-control" 
                                      id="omschrijving" 
                                      name="omschrijving" 
                                      rows="3"><?php echo htmlspecialchars($formData['omschrijving'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="maat" class="form-label">Maat</label>
                            <select class="form-control <?php echo isset($errors['maat']) ? 'is-invalid' : ''; ?>" 
                                    id="maat" 
                                    name="maat">
                                <option value="">Geen maat geselecteerd</option>
                                <option value="xs" <?php echo ($formData['maat'] ?? '') === 'xs' ? 'selected' : ''; ?>>XS</option>
                                <option value="s" <?php echo ($formData['maat'] ?? '') === 's' ? 'selected' : ''; ?>>S</option>
                                <option value="m" <?php echo ($formData['maat'] ?? '') === 'm' ? 'selected' : ''; ?>>M</option>
                                <option value="l" <?php echo ($formData['maat'] ?? '') === 'l' ? 'selected' : ''; ?>>L</option>
                                <option value="xl" <?php echo ($formData['maat'] ?? '') === 'xl' ? 'selected' : ''; ?>>XL</option>
                            </select>
                            <?php if (isset($errors['maat'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['maat']); ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="afbeelding" class="form-label">Afbeelding (bestandsnaam)</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="afbeelding" 
                                   name="afbeelding" 
                                   value="<?php echo htmlspecialchars($formData['afbeelding'] ?? ''); ?>" 
                                   placeholder="bijv. product.jpg">
                            <div class="form-text">Voer alleen de bestandsnaam in (bijv. product.jpg)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="prijs" class="form-label">Prijs (in euro's)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" 
                                       class="form-control <?php echo isset($errors['prijs']) ? 'is-invalid' : ''; ?>" 
                                       id="prijs" 
                                       name="prijs" 
                                       value="<?php echo isset($formData['prijs']) && $formData['prijs'] > 0 ? number_format($formData['prijs'] / 100, 2, '.', '') : ''; ?>" 
                                       step="0.01" 
                                       min="0" 
                                       placeholder="0.00">
                                <?php if (isset($errors['prijs'])): ?>
                                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['prijs']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Voer de prijs in euro's in (bijv. 899.00)</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="producten.php" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-2"></i>Annuleren
                            </a>
                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-save me-2"></i>Product Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validatie
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>
