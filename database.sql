-- SQLite Database Schema voor Maison van Dijk Product Management
-- Creëer de producten tabel met de vereiste velden

-- Maak de producten tabel aan
CREATE TABLE IF NOT EXISTS producten (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    naam TEXT NOT NULL,
    omschrijving TEXT,
    maat TEXT CHECK (maat IN ('xs', 's', 'm', 'l', 'xl')),
    afbeelding TEXT,
    prijs INTEGER -- opgeslagen in centen
);

-- Voeg enkele voorbeeldproducten toe
INSERT INTO producten (naam, omschrijving, maat, afbeelding, prijs) VALUES 
(
    'Klassiek Maatpak Zwart',
    'Een tijdloos zwart maatpak van Super 150''s wol. Perfect voor formele gelegenheden en zakelijke bijeenkomsten.',
    'l',
    'pak-zwart.jpg',
    89900
),
(
    'Navy Business Pak',
    'Elegant navy pak in slim fit. Gemaakt van premium Italiaanse wol met subtiele textuur.',
    'm',
    'pak-navy.jpg',
    79900
),
(
    'Charcoal Grey Smoking',
    'Luxe smoking in charcoal grey. Ideaal voor speciale evenementen en galadiner.',
    'l',
    'smoking-grey.jpg',
    129900
),
(
    'Lichtgrijze Zomerpak',
    'Luchtig zomerpak in lichtgrijs linnen. Perfect voor bruiloften en zomerevenementen.',
    'm',
    'pak-lichtgrijs.jpg',
    69900
),
(
    'Vintage Tweed Blazer',
    'Handgeweven tweed blazer met vintage charme. Exclusieve stof uit Schotland.',
    'xl',
    'blazer-tweed.jpg',
    49900
);