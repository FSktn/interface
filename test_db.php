<?php
require_once 'includes/database.php';

echo "=== DATABASE TEST ===\n";

// Maak database connectie
$product = new Product();
$producten = $product->getAllProducts();

echo "Aantal producten: " . count($producten) . "\n\n";

foreach ($producten as $item) {
    echo "ID: " . $item['id'] . "\n";
    echo "Naam: " . $item['naam'] . "\n";
    echo "Afbeelding: " . $item['afbeelding'] . "\n";
    echo "---\n";
}

echo "\n=== AFBEELDINGEN IN DIRECTORY ===\n";
$images = glob('images/*');
foreach ($images as $image) {
    echo basename($image) . "\n";
}
?>
