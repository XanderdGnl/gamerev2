# gamerev


# GameRev

**GameRev** is een webapplicatie waarmee gamers hun mening kunnen delen over verschillende games. Gebruikers kunnen posts schrijven, een beoordeling geven en likes geven aan posts van anderen. De applicatie is bedoeld voor gamers die op zoek zijn naar een plek om ervaringen te delen en feedback te ontvangen.

## Projectomschrijving

GameRev is een communityplatform waar gebruikers hun eigen gamebeoordelingen kunnen plaatsen. Het lost het probleem op van verspreide en inconsistente gebruikersreviews door alles te centraliseren in één toegankelijke webomgeving.

Voor wie?
- Gamers die hun ervaringen willen delen
- Game reviewers
- Ontwikkelaars die feedback van gebruikers willen verzamelen

## Functionaliteiten

- Gebruikersregistratie en login
- Gameposts maken met:
  - Titel
  - Genre (categorie)
  - Rating (1.0 – 5.0)
  - Beschrijving/tekst
- Like-functionaliteit (per gebruiker, maximaal één like per post)
- Filteren op categorieën (bijv. Action, RPG, Indie)
- Likes worden automatisch geteld en weergegeven bij elke post
- Tijdstempel bij iedere post

## Installatie-instructies

Volg deze stappen om GameRev lokaal te draaien:

### 1. Clone de repository

```bash
git clone https://github.com/XanderdGnl/gamerev.git
cd gamerev
```

### 2. Importeer de database 

Importeer de gegeven database in mySQL


### 3. Verander connection.php file

Pas de databasegegevens aan in het bestand connection.php zodat deze overeenkomen met jouw lokale database-instellingen/inloggegevens.

## Gebruik

1. Start je lokale webserver (XAMPP) en plaats deze map onder je webroot (bijv. `htdocs`).
2. Importeer `import.sql` in je MySQL/MariaDB en controleer of de database `gamerev` aanwezig is.
3. Pas `connection.php` aan met je database-credentials.
4. Open `http://localhost/gamerev/index.php` in je browser.
5. Maak een account via `make_account.php`, log in en maak posts via `add_post.php`.
6. Like een post met de like-knop; likes worden geteld in de `post_likes` tabel.

Opmerkingen:
- De weergegeven like-aantallen worden berekend uit de `post_likes` tabel (autoritatieve bron).
- Bij het verwijderen van een gebruiker of post worden de bijbehorende likes automatisch verwijderd (FOREIGN KEY ... ON DELETE CASCADE).
- Als je de pagina in een browser opslaat als HTML, vermijd spaties in bestandsnamen (bijv. `All%20Posts%20-%20GameRev_files/...`) of gebruik percent-encoding, anders kunnen sommige validators fouten melden over spaties in `href`-attributen.

Screenshots:
- Voeg screenshots toe in een `docs/screenshots/` map en link naar die afbeeldingen in deze README (bijv. `docs/screenshots/login.png`).
