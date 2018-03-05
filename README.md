# HT vaihe 1


### Toimintaympäristö ja sen valinta

Päädyin valitsemaan tekniikaksi php:n harjoitustyön tekemiseen. En halunnut valita Pythonia, koska olen jo tutustunut Pythoniin aikaisemmin ja halusin oppia jonkun uuden kielen. Kuulin muutamalta tutulta, että kaikki javascript kielet on vaikeita oppia, joten myöskään niihin ei löytynyt siitä syystä halua iskeytyä. Tiedän myös tarpeen tullen php:n osaajia, joilta voin mahdollisesti pyytää apua, jos sitä harjoitustyön aikana tarvitsen.

Toisena osana halusin myös käyttää johdanto tietokantoihin kurssin asioita ja käyttää SQL tietokantakieltä harjoitustyössä. SQL:n perusteet on hallussa, mutta oikeasti en ole tehnyt yhtäkään käytettävää tietokantaa, joten tämä on hyvä harjoitus myös SQL:n oppimiseen syvällisemmin. 

Aluksi en tiennyt ollenkaan, miten päästä edes testaamaan omaa php-koodia ja toimintaympäristön valinnasta ei ollut minkäänlaista tietoa, joten googlaamalla etsin erilaisia mahdollisuuksia. [Tämän](https://stackoverflow.com/questions/1678010/php-server-on-local-machine) Stack Overflow kysymyksen kautta päädyin valitsemaan XAMPP:n. Toimintaympäristönä työlle toimii siis [XAMPP](https://www.apachefriends.org/index.html) v.7.2.1., josta asennettu Apache ja MySQL.

---

#### Toimintaympäristön asentaminen

Toimintaympäristön eli XAMPP:n asentaminen ei ole hankalaa. Ladataan koneelle asennustiedosto, joka omalla kohdallani oli .exe -tiedosto Windowsilla ja asennus ei eroa normaalin sovelluksen asennuksesta mitenkään. Asennuksen aikana voi valita tarvittavat paketit, joita halutaan käyttää. Omassa työssäni tallennan käyttäjätilit ja haetun datan SQL tietokantoihin, joten asensin Apachen lisäksi MySQL:n. Asennuksen ja ohjelman testauksen ohjeistuksena käytin [XAMPP Tutorialia](https://blog.udemy.com/xampp-tutorial/).


XAMPP:n ensimmäisellä käynnistyskerralla ohjelma kysyy kielen, jota käytetään. Muut asetukset itse täytyy asettaa, jos niitä haluaa muuttaa. En ole muuttanut muita asetuksia millään tavalla vaan oletukset ovat toimineet tähän asti. XAMPP:n käynnistämiseen löytyy oma XAMPP_start.exe, josta palvelin saa päälle. Helpommin kuitenkin palvelimen näkee, kun käynnistää samassa paketissa asennetun Control Panelin. Paneelista näkyy, mitkä palvelut ovat tällä hetkellä päällä ja mitkä pois päältä. Itse käynnistän paneelista aiemmin mainitut MySQL:n ja Apachen.


Ongelmaksi tuli alussa se, että en tiennyt ollenkaan mihin php-tiedostot tuli asettaa, joita halusi palvelimella käyttää. Löysin oikean tiedostopolun etsimällä XAMPP:n asennuskansiosta "index.php", joka toimii oletussivuna palvelimelle. Oletussivulla näkyi aluksi Apachen tervetuloa sivu, jonka nähdessään tiesi palvelimen toimivan. Tässä vaiheessa ajattelin tehdä Hello world -ohjelman, jolla testaan palvelimen toiminnan omilla php-tiedostoilla. Loin kansioon helloworld.php tiedoston, jonka koodi löytyy alta.
```
<?php
   echo ("Hello world");
?>
```
Tähän ohjelmaan pääsi käsiksi kirjoittamalla selaimeen localhost/helloworld.php. Ensimmäinen ohjelma on helposti luotu ja toimi kuten odotettukin. Syntaksi php:ssä on hieman erilaista, mihin olen tottunut, mutta uskon kuitenkin sen selkenevän harjoitustyön kulkiessa eteenpäin. 

Työ löytyy kokonaisuudessaan omasta [gitistäni](https://github.com/a-BRout/OHSIHA).

---
### Harjoitustyön sisältö

Harjoitustyöni tulee olemaan palvelu, joka hakee [Google Sheets](https://www.google.com/sheets/about/) palvelusta taulukon, joka sisältää kerättyjä auton polttoainekulutuksen seurannan lukuja. Olen itse kerännyt tietoa yli kahden vuoden ajan auton polttoaineen kulutuksesta ja ajattelin nyt tehdä kerättyyn dataan visualisoinnin ja seurannan web-palvelun kautta.

Harjoitustyö tulee sisältämään muutaman eri osa-alueen: kirjautuminen palveluun, taulukon haku Google Sheets API:sta, taulukon tallentaminen SQL tietokantaan, taulukon tietojen muuttaminen ja tiedoista tehty visuaalinen representaatio. 



### Käytettyjä resursseja:

Markdown:

https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet#code


Google autentikointi:

https://developers.google.com/api-client-library/php/auth/web-app
https://developers.google.com/sheets/api/quickstart/php 


php ja SQL toiminta:

https://www.w3schools.com/php/ 

Kirjautuminen:

https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php

---
### Tarvittavat paketit:
[Google API library](https://github.com/google/google-api-php-client#download-the-release) + [Asennus](https://developers.google.com/api-client-library/php/start/installation)
