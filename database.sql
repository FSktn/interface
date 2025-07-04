-- Database aanmaken en tabel voor producten
CREATE DATABASE IF NOT EXISTS producten_db;
USE producten_db;

-- Tabel aanmaken met alle vereiste velden
CREATE TABLE producten (
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    naam TEXT NOT NULL,
    omschrijving TEXT,
    maat ENUM('xs', 's', 'm', 'l', 'xl') DEFAULT NULL,
    afbeelding TEXT,
    prijs INTEGER
);

-- Enkele voorbeeldgegevens toevoegen
INSERT INTO producten (naam, omschrijving, maat, afbeelding, prijs) VALUES
('Milano Zakelijk Pak', 'Italiaanse wol, modern slim fit', 'l', 'milano_pak.jpg', 89900),
('Venetië Ceremonie Pak', 'Fijne Engelse wol, met zijden accenten', 'm', 'venetie_pak.jpg', 129900),
('Napoli Casual Pak', 'Wol-linnen mix, half gevoerd', 'xl', 'napoli_pak.jpg', 79900),
('Classic Smoking', 'Traditionele smoking voor speciale gelegenheden', 'l', 'smoking.jpg', 149900),
('Business Blazer', 'Elegante blazer voor kantoor', 's', 'blazer.jpg', 59900);
