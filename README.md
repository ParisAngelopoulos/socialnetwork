<p align="center">
  <img src=".github/banner.svg" alt="Social Network – profielen, vrienden en een feed" width="100%">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black" alt="JavaScript">
  <img src="https://img.shields.io/badge/Tailwind%20CSS-06B6D4?logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
</p>

# Social Network (Pynch)

Een klein sociaal netwerk, gebouwd in gewone PHP zonder framework. Je maakt een account, vult je profiel in, voegt vrienden toe en plaatst berichten. Dit was het project waarin ik voor het eerst zelf een inlogsysteem bouwde.

## Functies

- **Registreren en inloggen**, met controle van de formulieren in JavaScript.
- **Profiel**: naam, bijnaam, woonplaats, een stukje over jezelf en een profielfoto.
- **Berichten plaatsen**, met of zonder foto. Een bericht is openbaar of alleen voor vrienden.
- **Feed** (`home.php`): je eigen berichten, openbare berichten en berichten van je vrienden.
- **Vrienden**: vriendverzoeken sturen, accepteren (`requests.php`) en je vriendenlijst bekijken (`friends.php`).
- **Zoeken** (`search.php`) op naam, e-mailadres, woonplaats of in berichten.

## Zelf draaien

Je hebt een lokale webserver met PHP en MySQL nodig, bijvoorbeeld XAMPP.

1. Clone het project in de map van je webserver (bij XAMPP is dat `htdocs`):
   ```bash
   git clone https://github.com/ParisAngelopoulos/socialnetwork.git
   ```
2. Maak in phpMyAdmin een database aan met de naam `socialnetwork`.
3. Importeer daarin het bestand `socialnetwork (1).sql`.
4. Ga naar http://localhost/socialnetwork en log in met een van de testaccounts, of maak zelf een account aan.

### Testaccounts

In de database staan vijf gebruikers met berichten, vriendschappen en openstaande vriendverzoeken. Ze hebben allemaal het wachtwoord `welkom123`.

| Naam | E-mailadres |
| --- | --- |
| Paris Angelopoulos | `paris@voorbeeld.nl` (heeft 2 vriendverzoeken openstaan) |
| Sanne de Vries | `sanne@voorbeeld.nl` |
| Daan Bakker | `daan@voorbeeld.nl` |
| Youssef El Amrani | `youssef@voorbeeld.nl` |
| Lisa Jansen | `lisa@voorbeeld.nl` |

De verbinding met de database staat in `functions/functions.php` (standaard `root` zonder wachtwoord op `localhost`).

## Mappen

| Map / bestand | Wat erin staat |
| --- | --- |
| `index.php` | Inloggen en registreren |
| `home.php` | De feed en een nieuw bericht plaatsen |
| `profile.php` | Profielpagina's en vriendverzoeken |
| `includes/` | Stukken die op meer pagina's terugkomen (navigatie, bericht, profiel) |
| `functions/` | Databaseverbinding en uploaden van foto's |
| `data/images/` | Geüploade profiel- en berichtfoto's |
| `resources/` | CSS en JavaScript |

## Wat ik geleerd heb

- Hoe sessies werken en hoe je pagina's afschermt voor wie niet is ingelogd.
- Hoe je met SQL-joins een feed samenstelt uit je eigen berichten en die van je vrienden.
- Foto's uploaden en aan een gebruiker of bericht koppelen.

## Beveiliging

- Alle invoer van gebruikers gaat via **prepared statements** (`db_query()` in `functions/functions.php`), dus niemand kan SQL in een zoekveld of formulier typen.
- Alles wat gebruikers schrijven wordt **ge-escaped** (`h()`) voordat het op de pagina komt, zodat een bericht geen JavaScript kan uitvoeren.
- Wachtwoorden worden opgeslagen met `password_hash`. Oude md5-wachtwoorden worden bij de eerste keer inloggen vanzelf omgezet.
- Bij uploads worden alleen JPG, PNG en GIF geaccepteerd, en het bestand moet echt een afbeelding zijn.

---

Gemaakt door **Paris Angelopoulos**, web developer in opleiding · [GitHub](https://github.com/ParisAngelopoulos) · [parisangelopoulos@gmail.com](mailto:parisangelopoulos@gmail.com)
