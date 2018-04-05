# HT vaihe 3

Tekniikka:
PHP, SQL ja palvelimena XAMPP

https://github.com/a-BRout/OHSIHA

### Kuvaus toteutuksesta
Työn tavoitteena on luoda palvelu, josta nähdään itse kerätyn datan avulla polttoainekulutus ja muita autoon tai ajamiseen liittyviä tietoja. Tässä vaiheessa haetaan data, jota käytetään.

Tässä osassa työtä haen dataa Googlen Sheets -palvelusta. Haetun datan voi itse määritellä, mutta sen on löydyttävä oman Google tilin alta. Laitan lopulliseen palautukseen esimerkkidatan mukaan, jonka avulla voi testata ohjelman toimintaa. Data on oltava itse kerättyä ja tallennettu oikeanlaiseen muotoon. Tämä on selkeä rajoite käyttäjille, mutta tiedostettu valinta, koska harjoitustyön tavoite on saada itselle sopiva palvelu kerätyn datan näyttämiseen. 

##### Google API
Työssä päätin käyttää Googlen API:a, koska uskon Googlella olevan laajoja mahdollisuuksia heidän API:n käyttöön myös tulevaisuudessa. Tavoitteena olikin tutustua sen käyttöön ja saada se toimimaan asiallisesti. Google API koostuu kahdesta eri osasta. Toisena osana on [Googlen pilvipalveluiden](https://console.cloud.google.com/apis) sivuilla projektin luominen, jotta saadaan avain palveluun. Avaimen avulla nähdään erilaista dataa palvelun käytöstä ja virheet, jos niitä on tullut. Omasta projektista käyttäjädataa ei juurikaan saada, koska käytän vain itse palvelua ja se ei ole julkisesti jaossa. Sieltä kuitenkin näkyy omat tekemäni testit datan hakemiseen ja muutama virhe, jos olen testannut hylätä palvelun pyynnön hakea dataa.

Projektin luominen oli yllättävän yksinkertaista Googlen sivuilla. Se vaati Google tilin ja projektin Googlen pilvipalvelun sivustolle. Projektilta saadaan hankittua avain JSON -muodossa. Sen avulla päästään Google projektin sisälle ja saadaan API:n kautta tiedot haettua. Kun JSON-tiedosto löytyy oman työn kansiosta niin ensimmäinen osa API:in pääsemisestä on tehty.

##### Datan saaminen
Toisena osana on käyttää ladattua avainta saadakseen dataa. Tämä vaatii muutaman eri osan: kirjautumisen omaan Google tiliin, jolta data haetaan; avaimen lähetys ja varmennus Googlen kanssa ja datan hakeminen API:n kautta. Näiden osien avulla saadaan haettu data Googlen palvelimelta.

Ensimmäisenä oli kirjautuminen Google tiliin, josta data halutaan. Tiliin kirjautumisen onnistuessa voidaan Googlelta pyytää "Token", jonka avulla saadaan haettua dataa. Tässä työssä haetaan käyttäjältä lupa ainoastaan lukea Google Sheets palvelun dataa. Sen avulla ei voida kirjoittaa tai poistaa tiedostoja. Palveluun voisi luoda selaimen, jossa näkyisi kaikki käyttäjän Google Sheets taulukot, mutta tässä tapauksessa sitä ei ole tehty. Vaan käyttäjän on syötettävä taulukkonsa ID, joka löytyy jokaiselta taulukolta. Taulukosta on myös syötettävä kenttä (esim. Sheet1!A1:C33), josta tiedot haetaan.

**Alla oleva koodi tiedostosta: "SheetChoose.php"**
``` html
<form action="/SheetValueGET.php" method="post">
    <label>Sheet ID:</label>
    <input type="text" name="Sheet_ID"class="form-control" 
    pattern="[a-zA-Z0-9-_]+" title="Get your Google Sheet ID">
    <br><br>
    <label>Sheet Range:</label>
    <input type="text" name="Sheet_Range" class="form-control" 	
    pattern="[a-zA-Z0-9]+![A-Z][1-9]:[A-Z][1-9]+" title="Example: Sheet1:A1:G33">
    <br><br>
    <div class="form-group">
    <input type="submit" class="btn btn-primary" value="Submit">
</form>
```
Yllä näkyy html-koodi, jolla saadaan luotua kaksi tekstikenttää, johon käyttäjä voi laittaa oman Sheets -taulukon ID:n ja taulukon datan paikan. "pattern" on Regexillä luotu malli, jota käyttäjän syötteen on vastattava. Lomaketta ei voi lähettää ellei molemmat käyttäjän kentät ole mallin mukaisia. ID ja range ovat aina kyseisessä muodossa.

**Alla oleva koodi tiedostosta: "SheetValueGET.php"**
``` PHP
<?php
    $client = new Google_Client();
    $client->setAuthConfig('client_secret.json');
    $client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);
?>
```
Yllä olevassa PHP-koodissa näkyy, miten Google API:in voidaan luoda client, jossa haetaan vain Sheetsin lukuoikeudet. Tämä vaatii palvelimelta paketin, jonka Google tarjoaa eri kielille. Tässä työssä on käytetty PHP:lle tehtyä pakettia. Linkki pakettiin löytyy tämän tiedoston lopusta.

Tässä vaiheessa käyttäjä on sallinut palvelun hakea tietoja Google Sheets palvelusta. Tähän kohtaan voitaisiin toteuttaa myös kohta, jos käyttäjä ei salli palvelun käyttää tietoja. Tällä hetkellä sivusto vain palautuu aloitussivulle ja käyttäjä ei pääse jatkamaan mihinkään muualle kuin kirjautumiseen Googlen tilille. Toisena mahdollisuutena olin miettinyt palveluun esimerkiksi csv:n lataamista palvelimelle ja sen käyttämisen datan saamiseksi. Kuitenkin päädyin kurssin kannalta järkevämpään tapaan käyttää API:a.

**Alla oleva koodi tiedostosta: "oauth2callback.php"**
``` PHP
if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/SheetValueGET.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
```
Yllä olevassa koodissa alussa tarkistetaan löytyykö "pääsykoodi" jo käyttäjältä, jos ei löydy niin haetaan sellainen ja palataan takaisin samaan kohtaan. Koodin ollessa määritelty haetaan "Access token", jonka avulla voidaan API:sta hakea tietoja. Mainittu "Access token" pysyy voimassa tietyn ajanjakson, jonka Google on määrittänyt. Nyt voidaan edetä tietojen hakemiseen.

**Alla oleva koodi tiedostosta: "SheetValueGET.php"**
``` PHP
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  $drive = new Google_Service_Sheets($client);
  $spreadsheetId = $_POST['Sheet_ID'];
  $range = $_POST['Sheet_Range'];
  $response = $drive->spreadsheets_values->get($spreadsheetId, $range);
  $values = $response->getValues();
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
```
Yllä olevassa koodissa haetaan tiedot saadun tokenin avulla Googlelta. Tässä tapauksessa käytetään POST-metodia lähettäessä tietoja Googlen palveluun. Sieltä saadaan vastaus, joka sisältää arvot sisällään. Arvot tulevat joko yhtenä objektina tai listana (array). Tässä tapauksessa tiedot tulevat listana. Tässä listassa esim. listan ensimmäinen alkio on lista, joka sisältää ensimmäisen rivin tiedot. Tieto tulee siis muodossa, jossa on listan sisällä taulukon rivit listoina. 

Tässä vaiheessa tiedot on saatu Googlen API:sta ja sitä voidaan alkaa työstämään haluamallaan tavalla. Tähän kohtaan päättyy kolmas harjoitustyön osa ja seuraavassa osassa tarkistellaan, miten tiedosta saadaan aikaan taulukoita ja visualisointeja.

### Linkkejä joista oli apua
Googlen oAuth 2.0 ohje: https://developers.google.com/identity/protocols/OAuth2
Googlen paketti: https://github.com/google/google-api-php-client
Google API for PHP ohje: https://developers.google.com/api-client-library/php/start/get_started
### Vaikeat ja helpot asiat
1. Projektin luominen Googlen pilvipalvelun sisälle oli yksinkertaista ja JSON-avaimen saanti oli vain napin painallus
2. Vaikeaa oli ymmärtää, miten Googlen API toimii. Ohjeet oli kirjoitettu henkilöille, joilla on enemmän kokemusta web-devaamisesta, joten sen ymmärtäminen oli osaltaan hankalaa.
3. Paketti tekee suurimmat työt automaattisesta, joten sen käyttöönotto helpottaa työtä huomattavasti. Yksittäisiä metodeja tietojen hakemiseen ei tarvitse tehdä vaan paketti hoitaa ne valmiilla funktioilla.
4. Alussa en myöskään ymmärtänyt, missä muodossa tiedot tulevat niitä hakiessa. Googlen sivuilla mainitaan objekti tai lista. Lista kuitenkin sisälsi vielä toisen listan, jonka ymmärtäminen vaati jonkun aikaa ja varsinkin, että sen sai asialliseen ja ymmärrettävään muotoon.

