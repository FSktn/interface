# Maison van Dijk - Product Management Systeem

Dit project bevat een complete PHP database applicatie voor het beheren van producten voor Maison van Dijk.

## Bestanden Structuur

```
/workspaces/interface/
├── index.html                  # Hoofdpagina van de website
├── database.sql                # Database schema en voorbeeldgegevens
├── includes/
│   └── database.php           # Database klasse en functies
├── images/                    # Map voor productafbeeldingen
├── producten.php             # Overzichtspagina van alle producten
├── product_detail.php        # Detailpagina voor individuele producten
└── product_toevoegen.php     # Formulier voor nieuwe producten
```

## Database Setup

1. **Database aanmaken:**
   - Importeer `database.sql` in je MySQL database
   - Dit maakt de database `producten_db` aan met de `producten` tabel

2. **Database velden:**
   - `id`: AUTO_INCREMENT primary key
   - `naam`: TEXT NOT NULL (verplicht)
   - `omschrijving`: TEXT (optioneel)
   - `maat`: ENUM('xs','s','m','l','xl') (optioneel)
   - `afbeelding`: TEXT (optioneel - bestandsnaam)
   - `prijs`: INTEGER (opgeslagen in centen)

## Functionaliteiten

### Opdracht 1: Database ✅
- Database met juiste velden en constraints
- Auto-increment ID
- Prijs opgeslagen als integer (in centen)
- Maat met beperkte waarden
- NULL waarden toegestaan waar vereist

### Opdracht 2: Product Pagina's ✅
- **producten.php**: Toont alle producten in een overzichtelijke grid
- **product_detail.php**: Detailpagina met alle productinformatie
- Prijs wordt getoond in euro's met 2 decimalen
- Responsief design met Bootstrap
- Placeholder afbeeldingen bij ontbrekende bestanden

### Opdracht 3: Product Toevoegen ✅
- **product_toevoegen.php**: Formulier voor nieuwe producten
- Server-side validatie:
  - Naam is verplicht (minimaal 2 karakters)
  - Maat moet geldig zijn (xs,s,m,l,xl) als ingevuld
  - Prijs moet positief getal zijn als ingevuld
  - Alle andere velden zijn optioneel
- Client-side ondersteuning voor betere UX
- Succesmelding na toevoegen

## Database Configuratie

Pas de database instellingen aan in `includes/database.php`:

```php
private $host = 'localhost';
private $dbname = 'producten_db';
private $username = 'root';
private $password = '';
```

## Gebruik

1. **Setup database** met `database.sql`
2. **Configureer** database verbinding in `includes/database.php`
3. **Plaats afbeeldingen** in de `images/` map
4. **Navigeer naar:**
   - `index.html` - Hoofdpagina van de website
   - `producten.php` - Alle producten bekijken
   - `product_detail.php?id=X` - Product details
   - `product_toevoegen.php` - Nieuw product toevoegen

## Beveiliging

- Prepared statements tegen SQL injection
- HTML escaping tegen XSS
- Server-side validatie van alle invoer
- Proper error handling

## Styling

Het project gebruikt het donkere thema van Maison van Dijk met:
- Gouden accenten (#936c39)
- Donkere achtergrond (#0e0e0e)
- Bootstrap 5 voor responsiviteit
- Font Awesome iconen
