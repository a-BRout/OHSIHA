# HT vaihe 1

Tekniikka:
PHP, SQL ja palvelimena XAMPP
tiivis kuvaus toteutuksesta,
muutamia otteita toteutuksesta (ohjelmakoodista, asetustiedostoista, ...),
listaus (siis lista linkkejä) ohjeista tai esimerkiksi verkkolähteistä jotka olivat erityisesti hyödyksi tehtävää tehdessä ja
listaus vähintään kolmesta asiasta, jotka olivat valitulla teknologialla joko erityisen helppoja tai vastaavasti hankaloittivat työtäsi merkittävästi.

### Kuvaus toteutuksesta
Tähän osaan harjoitustyötä olen saanut aikaan kirjautumisen ja esimerkkidatan esille laiton. Dataa ei ole muokattu mitenkään, mutta data saadaan näkyviin SQL-käskyllä oikealta käyttäjältä. Datan sain tietokantaan tallentamalla koneelta löytyneen csv-tiedoston, joka onnistui SQL:n admin sivulta "Tuonti" välilehdeltä. Tätä käytetään vain datan testauksessa ja lopullisessa työssä data haetaan API:n kautta.

##### Kirjatuminen
Harjoitustyössä kirjautuminen tapahtuu aina ennen mihinkään sivulle pääsyä. Jos käyttäjä ei ole kirjautunut niin palataan aina login.php sivulle, jossa pyydetään käyttäjää kirjautumaan. Tämä tapahtuu melko yksinkertaisella if-lauseella, jossa tarkistetaan onko sessionissa käyttäjää tällä hetkellä. Jos käyttäjää ei löydy niin palautetaan käyttäjä login.php sivulle. Tämä estää käyttäjää pääsemästä muille sivuille, jotka eivät toimi ilman käyttäjää ennen kuin pitäisi.
``` PHP
<?php
   if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
?>
```
Kirjautumista varten muuten on luotu kolme eri tiedostoa: login.php, logout.php ja register.php. Kaikki niistä tekevät nimensä mukaisesti oman sisältönsä. Lähdetään liikkeelle rekisteröitymisestä. Rekisteröintiin ei tarvita tässä tapauksessa muuta kuin Username ja Password, joka tarkistetaan Confirm Password kohdalla. Käyttäjä ei voi valita samaa käyttäjänimeä, joka on jo käytössä ja salasanan on oltava yli 6 merkkiä pitkä. Muuten tiliin ei aseteta erityisiä vaatimuksia.
``` SQL
SELECT id FROM users WHERE username = "username"
```
Yllä esitetty SQL-lause, jolla saadaan etsittyä kaikki username nimiset käyttäjät. Tämän jälkeen voidaan testata onko tälläinen käyttäjä jo olemassa if-lauseella. 

Tärkeä asia, joka pitää huomioida käyttäjätilejä tehdessä on tarkastella turvallisuutta. Tämän takia kirjautumisessa käytetään POST-metodia, jotta salasanaa tai käyttäjänimeä ei voida noukkia suoraan osoitepalkista. Salasana myös tallennetaan password hashina, jossa siitä ei saada selkokieltä edes käyttäjien taulukoita tutkiessa.
``` PHP
$param_password = password_hash($password, PASSWORD_DEFAULT);
```
Huomioon tässä piti ottaa, että salasanan hash voi PHP:n sivujen mukaan ylittää jopa 60 merkkiä, joten se tulee tallentaa SQL:n tietokantaan stringinä, jonka maksimipituus on 255 merkkiä.
``` SQL
INSERT INTO users (username, password) VALUES ("username", "password")
```
Yllä olevassa SQL-lauseessa on esitetty käyttäjän username lisääminen tietokantaa salasanalla password. Tässä selvästikkään salasanaa ei ole kätketty mitenkään, mutta oikeassa koodissa tallennetaan kätketty salasana.

Login-sivulla mielestäni ei ole erikoisempaa mainittavaa, jota aiemmat kohdat ei selitä auki. Käyttäjänimi ja salasana haetaan tietokannasta ja niitä verrataan käyttäjän antamiin tietoihin. Kätketty salasana saadaan testattua alla olevalla lausekkeella.
``` PHP
password_verify($password, $hashed_password)
```
Käyttäjätilin luomisessa ja rekisteröinnissä on käytetty hyödyksi bootstrapin valmista css-teemaa, jonka avulla napit ja tekstikentät saadaan yhtenäisen näköisiksi läpi ohjelman.

##### Esimerkki datan näyttäminen

Alussa kerrottiin, että olin asettanut SQL-tietokantaan csv-tiedoston. Tämä data voidaan nyt hakea alla olevalla SQL-käskyllä
``` SQL
SELECT `paivamaara`, `litraa`, `hinta`, `trip`, `trip_full` FROM `data` WHERE `owner` = '".$user."'"
```
Tässä kohdassa on lisätty omistajaksi käyttäjä nimeltään testi ja sille ollaan kirjauduttu sisään sivustolle. Tuloksena käskylle saadaan lista (array) tiedoista, joita käyttäjä testi on asettanut tietokantaan. Tietokantaan on tallennettu tankkauksen paivamaara, kuinka monta litraa on tankattu, hinta, tankilla ajettu matka ja auton kilometrit. Kaksi esimerkki riviä löytyy alta.
|Pvm   |  Litraa | Euroa   | Kilometrit   | Trip  |
|---|---|---|---|---|
| 28/09/2016  |41.91   |59.47   | 574   | 251250  |
|  07/10/2016 | 42.03  | 61.2  | 609  | 251859  |
Tiedot pystytään haun jälkeen tulostamaan näkymiin alla olevalla html ja PHP-koodilla. $values pitää nyt sisällään taulukon, joka on haettu SQL:n kanssa tietokannasta alussa esitetyllä käskyllä. 
``` HTML
<table>
  <thead>
    <tr>
      <th><?php echo implode('</th><th>', array_keys(current($values))); ?></th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($values as $row): array_map('htmlentities', $row); ?>
    <tr>
      <td><?php echo implode('</td><td>', $row); ?></td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>
```

### Linkkejä joista oli apua
Käytin suurimmaksi osaksi saman sivuston tutoriaaleja harjoitustyön apuna. Näistä sai helposti selvää ja kertoivat hyvin, mikä kohta teki mitäkin.
Kirjautuminen: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
SQL: https://www.tutorialrepublic.com/php-tutorial/php-mysql-introduction.php

Täältä voi etsiä kaikkiin PHP:n ominaisuuksiin ja valmiisiin funktioihin tietoa parametreistä. Esimerkiksi password_hash löytyy täältä selityksineen.
PHP manuaali: http://php.net/manual/en/

HTML ja PHP listan tulostuksessa käytetty hyödyksi Stack Overflowia
Stack Overflow: https://stackoverflow.com/questions/4746079/how-to-create-a-html-table-from-a-php-array

### Vaikeat ja helpot asiat

