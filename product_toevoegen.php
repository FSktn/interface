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
        
        .form-select {
            background-color: #222;
            border: 1px solid #333;
            color: #e0e0e0;
        }
        
        .form-select:focus {
            background-color: #222;
            color: #e0e0e0;
            border-color: #936c39;
            box-shadow: 0 0 0 0.2rem rgba(147, 108, 57, 0.25);
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
        
        .invalid-feedback {
            color: #dc3545;
        }
        
        .form-label {
            color: #e0e0e0;
            font-weight: 500;
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
                    <h1 class="text-center mb-4">
                        <i class="fas fa-plus-circle me-2" style="color: #936c39;"></i>
                        Nieuw Product Toevoegen
                    </h1>
                    
                    <?php
                    require_once 'includes/database.php';
                    
                    $errors = [];
                    $success = false;
                    $formData = [
                        'naam' => '',
                        'omschrijving' => '',
                        'maat' => '',
                        'afbeelding' => '',
                        'prijs' => ''
                    ];
                    
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Formuliergegevens ophalen
                        $formData = [
                            'naam' => trim($_POST['naam'] ?? ''),
                            'omschrijving' => trim($_POST['omschrijving'] ?? ''),
                            'maat' => $_POST['maat'] ?? '',
                            'afbeelding' => trim($_POST['afbeelding'] ?? ''),
                            'prijs' => $_POST['prijs'] ?? ''
                        ];
                        
                        // Server-side validatie
                        $errors = validateProduct($formData);
                        
                        // Extra validatie voor prijs (converteren naar centen)
                        if (!empty($formData['prijs'])) {
                            $prijsInCenten = (int)(floatval($formData['prijs']) * 100);
                            if ($prijsInCenten <= 0) {
                                $errors['prijs'] = 'Prijs moet groter zijn dan 0';
                            }
                        } else {
                            $prijsInCenten = null;
                        }
                        
                        // Als geen fouten, product toevoegen
                        if (empty($errors)) {
                            $product = new Product();
                            
                            // Lege strings omzetten naar NULL voor optionele velden
                            $omschrijving = !empty($formData['omschrijving']) ? $formData['omschrijving'] : null;
                            $maat = !empty($formData['maat']) ? $formData['maat'] : null;
                            $afbeelding = !empty($formData['afbeelding']) ? $formData['afbeelding'] : null;
                            
                            if ($product->addProduct($formData['naam'], $omschrijving, $maat, $afbeelding, $prijsInCenten)) {
                                $success = true;
                                // Formulier leegmaken na succesvolle toevoeging
                                $formData = [
                                    'naam' => '',
                                    'omschrijving' => '',
                                    'maat' => '',
                                    'afbeelding' => '',
                                    'prijs' => ''
                                ];
                            } else {
                                $errors['general'] = 'Er is een fout opgetreden bij het toevoegen van het product.';
                            }
                        }
                    }
                    
                    // Succesbericht tonen
                    if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Product succesvol toegevoegd!
                            <a href="producten.php" class="btn btn-sm btn-outline-success ms-3">Bekijk alle producten</a>
                        </div>
                    <?php endif;
                    
                    // Algemene foutmelding tonen
                    if (isset($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($errors['general']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label for="naam" class="form-label">
                                Productnaam <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php echo isset($errors['naam']) ? 'is-invalid' : ''; ?>" 
                                   id="naam" 
                                   name="naam" 
                                   value="<?php echo htmlspecialchars($formData['naam']); ?>"
                                   required>
                            <?php if (isset($errors['naam'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars($errors['naam']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="omschrijving" class="form-label">Omschrijving</label>
                            <textarea class="form-control" 
                                      id="omschrijving" 
                                      name="omschrijving" 
                                      rows="4"
                                      placeholder="Beschrijf het product..."><?php echo htmlspecialchars($formData['omschrijving']); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="maat" class="form-label">Maat</label>
                                <select class="form-select <?php echo isset($errors['maat']) ? 'is-invalid' : ''; ?>" 
                                        id="maat" 
                                        name="maat">
                                    <option value="">Selecteer maat (optioneel)</option>
                                    <option value="xs" <?php echo $formData['maat'] === 'xs' ? 'selected' : ''; ?>>XS</option>
                                    <option value="s" <?php echo $formData['maat'] === 's' ? 'selected' : ''; ?>>S</option>
                                    <option value="m" <?php echo $formData['maat'] === 'm' ? 'selected' : ''; ?>>M</option>
                                    <option value="l" <?php echo $formData['maat'] === 'l' ? 'selected' : ''; ?>>L</option>
                                    <option value="xl" <?php echo $formData['maat'] === 'xl' ? 'selected' : ''; ?>>XL</option>
                                </select>
                                <?php if (isset($errors['maat'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo htmlspecialchars($errors['maat']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="prijs" class="form-label">Prijs (in euro's)</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background-color: #333; border-color: #333; color: #e0e0e0;">€</span>
                                    <input type="number" 
                                           class="form-control <?php echo isset($errors['prijs']) ? 'is-invalid' : ''; ?>" 
                                           id="prijs" 
                                           name="prijs" 
                                           step="0.01" 
                                           min="0"
                                           value="<?php echo htmlspecialchars($formData['prijs']); ?>"
                                           placeholder="0.00">
                                    <?php if (isset($errors['prijs'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo htmlspecialchars($errors['prijs']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="afbeelding" class="form-label">Afbeelding</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="afbeelding" 
                                   name="afbeelding" 
                                   value="<?php echo htmlspecialchars($formData['afbeelding']); ?>"
                                   placeholder="bijv. product_naam.jpg">
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Voer de bestandsnaam in van de afbeelding (optioneel). De afbeelding moet in de 'images' map staan.
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="producten.php" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-2"></i>Terug naar Producten
                            </a>
                            <div>
                                <button type="reset" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-undo me-2"></i>Wissen
                                </button>
                                <button type="submit" class="btn btn-gold">
                                    <i class="fas fa-plus me-2"></i>Product Toevoegen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validatie ondersteuning
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const naamInput = document.getElementById('naam');
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Naam validatie
                if (naamInput.value.trim().length < 2) {
                    isValid = false;
                    naamInput.classList.add('is-invalid');
                } else {
                    naamInput.classList.remove('is-invalid');
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
            
            // Real-time validatie voor naam
            naamInput.addEventListener('input', function() {
                if (this.value.trim().length >= 2) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    </script>
</body>
</html>
