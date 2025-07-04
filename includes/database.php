<?php
// Database configuratie voor SQLite
class Database {
    private $dbFile = 'producten.db';
    private $pdo;
    
    public function __construct() {
        try {
            // SQLite database in de project directory
            $this->pdo = new PDO("sqlite:" . __DIR__ . "/../" . $this->dbFile);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Maak de tabel aan als deze nog niet bestaat
            $this->createTables();
        } catch (PDOException $e) {
            die("Database verbinding mislukt: " . $e->getMessage());
        }
    }
    
    private function createTables() {
        $sql = "
        CREATE TABLE IF NOT EXISTS producten (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            naam TEXT NOT NULL,
            omschrijving TEXT,
            maat TEXT CHECK(maat IN ('xs', 's', 'm', 'l', 'xl')),
            afbeelding TEXT,
            prijs INTEGER
        )";
        
        $this->pdo->exec($sql);
        
        // Voeg voorbeelddata toe als de tabel leeg is
        $count = $this->pdo->query("SELECT COUNT(*) FROM producten")->fetchColumn();
        if ($count == 0) {
            $this->insertSampleData();
        }
    }
    
    private function insertSampleData() {
        $sql = "INSERT INTO producten (naam, omschrijving, maat, afbeelding, prijs) VALUES
                ('Milano Zakelijk Pak', 'Italiaanse wol, modern slim fit', 'l', 'milano_pak.jpg', 89900),
                ('Venetië Ceremonie Pak', 'Fijne Engelse wol, met zijden accenten', 'm', 'venetie_pak.jpg', 129900),
                ('Napoli Casual Pak', 'Wol-linnen mix, half gevoerd', 'xl', 'napoli_pak.jpg', 79900),
                ('Classic Smoking', 'Traditionele smoking voor speciale gelegenheden', 'l', 'smoking.jpg', 149900),
                ('Business Blazer', 'Elegante blazer voor kantoor', 's', 'blazer.jpg', 59900)";
        
        $this->pdo->exec($sql);
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
