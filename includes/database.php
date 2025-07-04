<?php
/**
 * Database Class voor SQLite connectie en product management
 * Maison van Dijk Product Management Systeem
 */

class Database {
    private $db;
    private $dbPath;
    
    public function __construct() {
        $this->dbPath = __DIR__ . '/../producten.db';
        $this->connect();
        $this->initializeDatabase();
    }
    
    /**
     * Maak verbinding met SQLite database
     */
    private function connect() {
        try {
            $this->db = new PDO('sqlite:' . $this->dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Database verbinding mislukt: ' . $e->getMessage());
        }
    }
    
    /**
     * Initialiseer database met schema als deze nog niet bestaat
     */
    private function initializeDatabase() {
        $sql = "CREATE TABLE IF NOT EXISTS producten (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            naam TEXT NOT NULL,
            omschrijving TEXT,
            maat TEXT CHECK (maat IN ('xs', 's', 'm', 'l', 'xl')),
            afbeelding TEXT,
            prijs INTEGER
        )";
        
        $this->db->exec($sql);
        
        // Controleer of er al data is, zo niet voeg voorbeelddata toe
        $count = $this->db->query("SELECT COUNT(*) as count FROM producten")->fetch();
        if ($count['count'] == 0) {
            $this->insertSampleData();
        }
    }
    
    /**
     * Voeg voorbeelddata toe
     */
    private function insertSampleData() {
        $sampleProducts = [
            [
                'naam' => 'Klassiek Maatpak Zwart',
                'omschrijving' => 'Een tijdloos zwart maatpak van Super 150\'s wol. Perfect voor formele gelegenheden en zakelijke bijeenkomsten.',
                'maat' => 'l',
                'afbeelding' => 'pak-zwart.jpg',
                'prijs' => 89900
            ],
            [
                'naam' => 'Navy Business Pak',
                'omschrijving' => 'Elegant navy pak in slim fit. Gemaakt van premium Italiaanse wol met subtiele textuur.',
                'maat' => 'm',
                'afbeelding' => 'pak-navy.jpg',
                'prijs' => 79900
            ],
            [
                'naam' => 'Charcoal Grey Smoking',
                'omschrijving' => 'Luxe smoking in charcoal grey. Ideaal voor speciale evenementen en galadiner.',
                'maat' => 'l',
                'afbeelding' => 'smoking-grey.jpg',
                'prijs' => 129900
            ],
            [
                'naam' => 'Lichtgrijze Zomerpak',
                'omschrijving' => 'Luchtig zomerpak in lichtgrijs linnen. Perfect voor bruiloften en zomerevenementen.',
                'maat' => 'm',
                'afbeelding' => 'pak-lichtgrijs.jpg',
                'prijs' => 69900
            ],
            [
                'naam' => 'Vintage Tweed Blazer',
                'omschrijving' => 'Handgeweven tweed blazer met vintage charme. Exclusieve stof uit Schotland.',
                'maat' => 'xl',
                'afbeelding' => 'blazer-tweed.jpg',
                'prijs' => 49900
            ]
        ];
        
        foreach ($sampleProducts as $product) {
            $this->insertProduct($product);
        }
    }
    
    /**
     * Haal alle producten op
     */
    public function getAllProducts() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM producten ORDER BY naam");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception('Fout bij ophalen producten: ' . $e->getMessage());
        }
    }
    
    /**
     * Haal een specifiek product op via ID
     */
    public function getProductById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM producten WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception('Fout bij ophalen product: ' . $e->getMessage());
        }
    }
    
    /**
     * Voeg een nieuw product toe
     */
    public function insertProduct($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO producten (naam, omschrijving, maat, afbeelding, prijs) 
                VALUES (:naam, :omschrijving, :maat, :afbeelding, :prijs)
            ");
            
            $stmt->bindParam(':naam', $data['naam']);
            $stmt->bindParam(':omschrijving', $data['omschrijving']);
            $stmt->bindParam(':maat', $data['maat']);
            $stmt->bindParam(':afbeelding', $data['afbeelding']);
            $stmt->bindParam(':prijs', $data['prijs'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Fout bij toevoegen product: ' . $e->getMessage());
        }
    }
    
    /**
     * Formatteer prijs van centen naar euro's
     */
    public static function formatPrice($cents) {
        if ($cents === null || $cents === '') {
            return 'Prijs op aanvraag';
        }
        return '€ ' . number_format($cents / 100, 2, ',', '.');
    }
    
    /**
     * Converteer euro's naar centen
     */
    public static function eurosToCents($euros) {
        return (int) round(floatval($euros) * 100);
    }
    
    /**
     * Valideer product data
     */
    public static function validateProduct($data) {
        $errors = [];
        
        // Naam is verplicht
        if (empty(trim($data['naam']))) {
            $errors[] = 'Naam is verplicht';
        } elseif (strlen(trim($data['naam'])) < 2) {
            $errors[] = 'Naam moet minimaal 2 karakters lang zijn';
        }
        
        // Maat validatie (alleen als ingevuld)
        if (!empty($data['maat']) && !in_array($data['maat'], ['xs', 's', 'm', 'l', 'xl'])) {
            $errors[] = 'Maat moet een van de volgende waarden zijn: xs, s, m, l, xl';
        }
        
        // Prijs validatie (alleen als ingevuld)
        if (!empty($data['prijs'])) {
            if (!is_numeric($data['prijs']) || floatval($data['prijs']) < 0) {
                $errors[] = 'Prijs moet een positief getal zijn';
            }
        }
        
        return $errors;
    }
    
    /**
     * Sluit database verbinding
     */
    public function __destruct() {
        $this->db = null;
    }
}

// Helper functie voor het escapen van HTML output
function escapeHtml($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>