<?php
// Database configuratie
class Database {
    private $host = 'localhost';
    private $dbname = 'producten_db';
    private $username = 'root';
    private $password = '';
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Database verbinding mislukt: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}

// Product klasse voor database operaties
class Product {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // Alle producten ophalen
    public function getAllProducts() {
        $query = "SELECT * FROM producten ORDER BY id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Enkel product ophalen
    public function getProductById($id) {
        $query = "SELECT * FROM producten WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Nieuw product toevoegen
    public function addProduct($naam, $omschrijving, $maat, $afbeelding, $prijs) {
        $query = "INSERT INTO producten (naam, omschrijving, maat, afbeelding, prijs) 
                  VALUES (:naam, :omschrijving, :maat, :afbeelding, :prijs)";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':naam', $naam);
        $stmt->bindParam(':omschrijving', $omschrijving);
        $stmt->bindParam(':maat', $maat);
        $stmt->bindParam(':afbeelding', $afbeelding);
        $stmt->bindParam(':prijs', $prijs, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}

// Validatie functies
function validateProduct($data) {
    $errors = [];
    
    // Naam validatie
    if (empty(trim($data['naam']))) {
        $errors['naam'] = 'Naam is verplicht';
    } elseif (strlen(trim($data['naam'])) < 2) {
        $errors['naam'] = 'Naam moet minimaal 2 karakters bevatten';
    }
    
    // Maat validatie (optioneel maar moet geldig zijn als ingevuld)
    if (!empty($data['maat']) && !in_array($data['maat'], ['xs', 's', 'm', 'l', 'xl'])) {
        $errors['maat'] = 'Maat moet xs, s, m, l of xl zijn';
    }
    
    // Prijs validatie
    if (!empty($data['prijs'])) {
        if (!is_numeric($data['prijs']) || $data['prijs'] < 0) {
            $errors['prijs'] = 'Prijs moet een positief getal zijn';
        }
    }
    
    return $errors;
}

// Prijs formatteren naar euro's
function formatPrice($price) {
    return '€ ' . number_format($price / 100, 2, ',', '.');
}
?>
