# HT vaihe 4

Tekniikka:
PHP, SQL ja palvelimena XAMPP

https://github.com/a-BRout/OHSIHA

### Kuvaus toteutuksesta
Työn tavoitteena on luoda palvelu, josta nähdään itse kerätyn datan avulla polttoainekulutus ja muita autoon tai ajamiseen liittyviä tietoja. Tässä vaiheessa visualisoidaan ja tallenetaan dataa SQL-tietokantaan.

Tässä osassa työtä on datan hakeminen suoritettu ja tarkoituksena on tallentaa data SQL-tietokantaan. Tämän jälkeen tulee luoda visualisointi datasta, jonka avulla datasta on helpompi tehdä johtopäätöksiä. 

##### SQL
Ensimmäisenä ongelmana oli selvittää, missä muodossa tarkalleen ottaen data Googlen palvelimelta tulee. Tätä varten loin taulukon, johon printtaan kaikki arvot ja käyttäjä voi hyväksyä datan, jos se näyttäisi olevan kunnossa. Alla oleva koodi tiedostosta SheetValueGET.php
``` html
<?php foreach ($values as $row): array_map('htmlentities', $row); ?>
    <tr>
      <td><?php echo implode('</td><td>', $row); ?></td>
    </tr>
<?php endforeach; ?>
```
Tässä koodissa muuttuja values pitää sisällään Googlelta saadut tiedot. Row on yksittäisen rivin sisältämät tiedot taulukossa. Samalla tein myös tarkastuksen, että käyttäjä on syöttänyt taulukon, jossa tiedot ovat oikealla tavalla eli rivien otsikoiden tulee täsmätä haluttuihin otsikoihin. Alla oleva koodi tiedostosta SheetValueGET.php
``` PHP
$row_names < 5 
OR $row_names[0] != 'Pvm' 
OR $row_names[1] != 'Litraa' 
OR $row_names[2] != 'Euroa' 
OR $row_names[3] != 'Kilometrit'
```
Sen jälkeen oletetaan, että käyttäjän antama data on oikeassa muodossa. Jos otsikot eivät täsmänneet niin, käyttäjä lähetetään takaisin valitsemaan uusia arvoja. Tähän kohtaan ei ole tässä tapauksessa luotu muita tarkistuskeinoja, vaikka niitä olisi kannattavaa tehdä, koska palvelua ei ole tarkoitus jakaa julkisesti eteenpäin.

Seuraavaksi edetään datan tallennukseen. Ensimmäisenä asiana työssä on päätetty, että jokaisella käyttäjällä voi olla vain yksi taulukko tallennettuna. Jos taulukkoa ei vielä löydy niin aikaisemman datan tarkistuksen jälkeen tallennetaan haettu data SQL-tietokantaan. Tietokantaan ei päästä suoraan tallentamaan, jos sinne ei ole ensin luotu tietovarastoa ja siihen taulukkoa. 

XAMPP:n sisällä päästään oman SQL tietovaraston admin sivuille, josta voidaan testailla SQL-lausekkeita. Aiemmin ensimmäisessä ja toisessa harjoitustyön vaiheessa olin luonut jo käyttäjille oman taulukon ja testannut myös taulukkoa haetulle datalle. Datalle on siis valmis taulukko, mutta kävin muuttamassa muutaman taulukon sarakkeen muotoa desimaaleiksi (float). Sen sai muutettua muutamalla hiiren klikkauksella admin-sivulta.

Seuraavaksi tarkoituksena on tallentaa Googlen palvelimelta saatu data tietokantaan. Tämä tapahtuu seuraavanlaisella SQL-lausekkeella:
``` SQL
      $sql ="INSERT INTO `data`(
    `paivamaara`,
    `litraa`,
    `hinta`,
    `trip`,
    `trip_full`,
    `owner`)
    VALUES
    ('$row[0]', $row[1], $row[2], $row[3], $row[4], '$user')";
    }
```
Lausekkeesta voidaan nähdä nyt haluttu datan muoto. Päivämäärää en saanut SQL:n tietokantaan tallennettua päivämääränä, joten tällä hetkellä se on 10 merkkiä pitkänä merkkijonona (CHAR(10)). Litraa, hinta, trip ja trip_full ovat kaikki desimaaleja (float) ja owner on käyttäjä, joka on kirjautunut sisään. Nyt ollaan vaiheessa, jossa käyttäjän data on saatu tallennettua ja voidaan edetä datan visualisointiin ja käyttämiseen.

##### Datan visualisointi

Datan visualisointia varten olen käyttänyt [CanvasJS](https://canvasjs.com/php-charts/spline-chart/) nimistä palvelua, jolla helposti saadaan luotua erilaisia graafeja datasta. Valitsin sen, koska halusin saada datan näkyviin niin, että hiirellä datapointin päältä saadaan yksittäisen pisteen tiedot näkyviin. CanvasJS oli myös melko yksinkertainen käyttää kunhan tajusi, miten data pitää syöttää kaavion sisään.

Visualisointi aloitetaan sillä, että haetaan aiemmin tallennettu data tietokannasta. Tätä varten loin ylimääräisen tiedoston nimeltään functions.php, johon lisäsin muutamia tarvittavia funktioita ja tämän voi ottaa käyttöön tarvittaessa. Tiedosto "functions.php" pitää sisällään nyt kaksi funktiota "Get_table" ja "Redirect", joista "Get_table" hakee syötetyn käyttäjän datan tietokannasta. "Redirect" toimii uudelleenohjauksen funktiona, jossa parametrinä on url, johon halutaan siirtyä. Alla oleva koodi on tiedostosta funtions.php
``` SQL
SELECT `paivamaara`, `litraa`, `hinta`, `trip`, `trip_full` FROM `data` WHERE `owner` = '".$user."'"
```
Yllä olevalla SQL-lausekkeella saadaan nyt haettua tietokannasta käyttäjälle tallennettu data. Data tallennetaan muuttujaan result listana (array), joka on muodoltaan samanlainen kuin Googlelta saatiin eli jokainen rivi on yksi lista ja rivit listana tekevät koko taulukon. Alla oleva koodi tiedostosta funtions.php
``` PHP
      $stmt->execute();
      $stmt->bind_result($paivamaara, $litraa, $hinta, $trip, $tripfull);
      while ($stmt->fetch()) 
      {
        $row = array();
        $row = [$paivamaara, $litraa, $hinta, $trip, $tripfull];
        $result[] = $row;
      }
```
Yllä olevassa koodissa "stmt" on nyt aikaisemmin esitelty SQL-lauseke. Tässä koodissa haetaan yksittäiset rivit tietokannasta ja tallenetaan ne "result" nimiseen parametriin. Tämä "result" palautetaan funktion lopussa ja sen avulla saadaan kaikki tallennettu data käyttöön.

Viimeisenä osana on tehdä visualisointi datasta. Tässä tapauksessa käytin CanvasJS:n SplineChart nimistä graafia kuvaamaan polttoaineen hintaa ja kulutusta. Molemmat löytyvät samasta graafista ja hiirellä saa datapointin tiedot näkymiin tarkemmin. Graafia varten tein kaksi eri muuttujaa, joilla sain listattua kulutukset ja hinnan erikseen.
``` PHP
foreach ($result as $value) {
    $kulutus = $value[1]/($value[3]/100);
    $litrat[] = array("label" => $value[0], "y" => $kulutus);
    $hinta[] = array("label" => $value[0], "y" => $value[2]/$value[1]);
}
```
Tässä tapauksessa tallennetaan pareja listan sisälle, jossa ensimmäisenä on datan "label", joka kertoo päivämäärän ja toisena osana on itse data eli kulutus ja bensan hinta. Nämä saadaan kaavioon näkyviin seuraavalla tavalla.
``` PHP
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: "Fuel Consumption per 100km and Cost per litre"
	},
	axisY: {
		title: "Litres and Euros",
		valueFormatString: "##",
		suffix: "",
		prefix: ""
	},
	data: [{
		type: "spline",
		markerSize: 5,
		xValueFormatString: "DD/MM/YYYY",
		yValueFormatString: "##.00",
		xValueType: "dateTime",
		dataPoints: <?php echo json_encode($litrat, JSON_NUMERIC_CHECK); ?>
	},{
    type: "spline",
    markerSize: 5,
    xValueFormatString: "DD/MM/YYYY",
    yValueFormatString: "##.00",
    xValueType: "dateTime",
    dataPoints: <?php echo json_encode($hinta, JSON_NUMERIC_CHECK); ?>
    }]
});
```
Tämä koodi voidaan jakaa kolmeen osaan, jossa alussa on kuvaajan otsikko sekä y-akselin tiedot ja toisena osana on data, sekä x-akselin tiedot. Y-akseliin on asetettu kaksi eri muuttujaa eli litrat ja eurot ja formatoitu kuvaajan asteikko kahteen merkkiin (valueFormatString: "##"). 

Toisena osana on datan määrittäminen, joka tässä on jaettu kahteen eri kokonaisuuteen, koska kuvaajalla on kahdet tiedot samaan aikaan. Toisena osana on ensiksi on kulutus ja toisena on hinta. CanvasJS itsessään tekee suurimman osan työstä ja sille tarvitsee vain antaa erilaisia parametrejä sekä datapisteet, jolloin kuvaajan tekeminen on yksinkertaista.

### Linkkejä, joista oli apua
CanvasJS: https://canvasjs.com/php-charts/spline-chart/
SQL:n ohje: https://www.w3schools.com/sql/

### Vaikeat ja helpot asiat
1. Datan saataessa sen muodon hahmottaminen oli melko hankalaa, koska sitä ei suoraan mainittu, että se tulisi listana, jonka sisällä on listoja.
1. Kuvaajan saaminen ensimmäistä kertaa tuntui hankalalta, kun ei tiennyt suoraan, minkälaista dataa ja miten se pitää kuvaajan sisään syöttää.
2. SQL-lausekkeessa, jolla tallennettiin dataa oli melkoinen rakentaminen, jotta oikeat tiedot päätyivät oikeaan paikkaan oikeassa muodossa.
3. XAMPP:n SQL-admin sivusto auttoi paljon eri tietokantojen näkemisessä ja lausekkeiden testaamisessa.


