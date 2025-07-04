-- SQLite database schema voor producten
-- Database wordt automatisch aangemaakt bij eerste gebruik

-- Tabel aanmaken met alle vereiste velden
CREATE TABLE IF NOT EXISTS producten (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    naam TEXT NOT NULL,
    omschrijving TEXT,
    maat TEXT CHECK(maat IN ('xs', 's', 'm', 'l', 'xl')),
    afbeelding TEXT,
    prijs INTEGER
);

-- Enkele voorbeeldgegevens toevoegen
INSERT OR IGNORE INTO producten (naam, omschrijving, maat, afbeelding, prijs) VALUES
('Milano Zakelijk Pak', 'Italiaanse wol, modern slim fit', 'l', 'milano_pak.jpg', 89900),
('Venetië Ceremonie Pak', 'Fijne Engelse wol, met zijden accenten', 'm', 'venetie_pak.jpg', 129900),
('Napoli Casual Pak', 'Wol-linnen mix, half gevoerd', 'xl', 'napoli_pak.jpg', 79900),
('Classic Smoking', 'Traditionele smoking voor speciale gelegenheden', 'l', 'smoking.jpg', 149900),
('Business Blazer', 'Elegante blazer voor kantoor', 's', 'blazer.jpg', 59900);
