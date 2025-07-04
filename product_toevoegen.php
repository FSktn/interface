<?php
require_once 'includes/database.php';

$success = false;
$errors = [];
$formData = [
    'naam' => '',
    'omschrijving' => '',
    'maat' => '',
    'afbeelding' => '',
    'prijs' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal form data op
    $formData = [
        'naam' => trim($_POST['naam'] ?? ''),
        'omschrijving' => trim($_POST['omschrijving'] ?? ''),
        'maat' => trim($_POST['maat'] ?? ''),
        'afbeelding' => trim($_POST['afbeelding'] ?? ''),
        'prijs' => trim($_POST['prijs'] ?? '')
    ];
    
    // Valideer data
    $errors = Database::validateProduct($formData);
    
    if (empty($errors)) {
        try {
            // Converteer prijs naar centen als ingevuld
            $prijsInCenten = empty($formData['prijs']) ? null : Database::eurosToCents($formData['prijs']);
            
            $productData = [
                'naam' => $formData['naam'],
                'omschrijving' => empty($formData['omschrijving']) ? null : $formData['omschrijving'],
                'maat' => empty($formData['maat']) ? null : $formData['maat'],
                'afbeelding' => empty($formData['afbeelding']) ? null : $formData['afbeelding'],
                'prijs' => $prijsInCenten
            ];
            
            $db = new Database();
            if ($db->insertProduct($productData)) {
                $success = true;
                // Reset form data na succesvol toevoegen
                $formData = [
                    'naam' => '',
                    'omschrijving' => '',
                    'maat' => '',
                    'afbeelding' => '',
                    'prijs' => ''
                ];
            } else {
                $errors[] = 'Er is een fout opgetreden bij het toevoegen van het product';
            }
        } catch (Exception $e) {
            $errors[] = 'Database fout: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Toevoegen - Maison van Dijk</title>
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
        
        .form-kaart {
            background-color: #171717;
            padding: 40px;
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
        
        .form-control, .form-select {
            background-color: #222;
            border: 1px solid #333;
            color: #e0e0e0;
            border-radius: 0;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: #222;
            color: #e0e0e0;
            border-color: #936c39;
            box-shadow: 0 0 0 0.2rem rgba(147, 108, 57, 0.25);
        }
        
        .form-label {
            color: #e0e0e0;
            font-weight: 500;
        }
        
        .required-label::after {
            content: ' *';
            color: #dc3545;
        }
        
        .alert {
            border-radius: 0;
        }
        
        .help-text {
            font-size: 0.875rem;
            color: #888;
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
                        <a class="nav-link text-white active" href="product_toevoegen.php">Product Toevoegen</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Product Toevoegen -->
    <section class="py-5" style="margin-top: 80px;">
        <div class="container">
            <h1 class="titel">Nieuw Product Toevoegen</h1>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Success Message -->
                    <?php if ($success): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Product succesvol toegevoegd! 
                            <a href="producten.php" class="text-decoration-none">Bekijk alle producten</a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Error Messages -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Er zijn fouten gevonden:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= escapeHtml($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-kaart">
                        <form method="POST" action="" novalidate>
                            <!-- Naam -->
                            <div class="mb-3">
                                <label for="naam" class="form-label required-label">Productnaam</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="naam" 
                                       name="naam" 
                                       value="<?= escapeHtml($formData['naam']) ?>"
                                       required
                                       minlength="2"
                                       maxlength="255">
                                <div class="help-text">Minimaal 2 karakters, maximaal 255 karakters</div>
                            </div>
                            
                            <!-- Omschrijving -->
                            <div class="mb-3">
                                <label for="omschrijving" class="form-label">Omschrijving</label>
                                <textarea class="form-control" 
                                          id="omschrijving" 
                                          name="omschrijving" 
                                          rows="4"
                                          maxlength="1000"><?= escapeHtml($formData['omschrijving']) ?></textarea>
                                <div class="help-text">Optioneel - beschrijving van het product (maximaal 1000 karakters)</div>
                            </div>
                            
                            <!-- Maat -->
                            <div class="mb-3">
                                <label for="maat" class="form-label">Maat</label>
                                <select class="form-select" id="maat" name="maat">
                                    <option value="">-- Selecteer maat (optioneel) --</option>
                                    <option value="xs" <?= $formData['maat'] === 'xs' ? 'selected' : '' ?>>XS</option>
                                    <option value="s" <?= $formData['maat'] === 's' ? 'selected' : '' ?>>S</option>
                                    <option value="m" <?= $formData['maat'] === 'm' ? 'selected' : '' ?>>M</option>
                                    <option value="l" <?= $formData['maat'] === 'l' ? 'selected' : '' ?>>L</option>
                                    <option value="xl" <?= $formData['maat'] === 'xl' ? 'selected' : '' ?>>XL</option>
                                </select>
                                <div class="help-text">Optioneel - selecteer de beschikbare maat</div>
                            </div>
                            
                            <!-- Afbeelding -->
                            <div class="mb-3">
                                <label for="afbeelding" class="form-label">Afbeelding</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="afbeelding" 
                                       name="afbeelding" 
                                       value="<?= escapeHtml($formData['afbeelding']) ?>"
                                       placeholder="bijv. pak-zwart.jpg">
                                <div class="help-text">
                                    Optioneel - bestandsnaam van de afbeelding in de images/ map
                                    <br><small class="text-muted">Zorg ervoor dat de afbeelding al geüpload is naar de images/ map</small>
                                </div>
                            </div>
                            
                            <!-- Prijs -->
                            <div class="mb-4">
                                <label for="prijs" class="form-label">Prijs</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background-color: #333; border-color: #333; color: #e0e0e0;">€</span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="prijs" 
                                           name="prijs" 
                                           value="<?= escapeHtml($formData['prijs']) ?>"
                                           min="0"
                                           step="0.01"
                                           placeholder="899.00">
                                </div>
                                <div class="help-text">Optioneel - prijs in euro's (bijv. 899.00)</div>
                            </div>
                            
                            <!-- Submit Buttons -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn goudknop">
                                    <i class="fas fa-plus me-2"></i>Product Toevoegen
                                </button>
                                <a href="producten.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Annuleren
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Info Box -->
                    <div class="mt-4">
                        <div class="kaart">
                            <h5 class="goud-tekst mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informatie
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check me-2 goud-tekst"></i>
                                    Alleen de <strong>productnaam</strong> is verplicht
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check me-2 goud-tekst"></i>
                                    Alle andere velden zijn optioneel
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check me-2 goud-tekst"></i>
                                    Prijs wordt automatisch opgeslagen in centen
                                </li>
                                <li>
                                    <i class="fas fa-check me-2 goud-tekst"></i>
                                    Upload afbeeldingen naar de <code>images/</code> map
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
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
    
    <!-- Client-side validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const prijsInput = document.getElementById('prijs');
            
            // Prijs input validatie
            prijsInput.addEventListener('input', function(e) {
                const value = e.target.value;
                if (value && (isNaN(value) || parseFloat(value) < 0)) {
                    e.target.setCustomValidity('Prijs moet een positief getal zijn');
                } else {
                    e.target.setCustomValidity('');
                }
            });
            
            // Form validatie bij submit
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    </script>
</body>
</html>