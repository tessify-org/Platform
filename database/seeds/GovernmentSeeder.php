<?php

use App\Models\Ministry;
use App\Models\Organization;
use App\Models\OrganizationType;
use App\Models\OrganizationLocation;
use App\Models\OrganizationDepartment;
use App\Models\AssignmentType;

use Illuminate\Database\Seeder;

class GovernmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("ministries")->delete();
        DB::table("organizations")->delete();
        DB::table("organization_types")->delete();
        DB::table("organization_departments")->delete();
        DB::table("assignment_types")->delete();

        //
        // Ministries
        //

        $min_az = Ministry::create([
            "name" => "Ministerie van Algemene Zaken", 
            "abbreviation" => "AZ",
            "description" => "Algemene Zaken is het ministerie van de minister-president. Het ministerie houdt zich bezig met de coördinatie van het algemeen regeringsbeleid en van de overheidscommunicatie. Ook verzorgt het departement de voorlichting over het Koninklijk Huis.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-algemene-zaken",
            "logo_url" => "storage/images/ministries/logos/algemenezaken.jpg"
        ]);

        $min_bzk = Ministry::create([
            "name" => "Ministerie van Binnenlandse Zaken en Koninkrijksrelaties", 
            "abbreviation" => "BZK",
            "description" => "'Samen leven en wonen, in een democratische rechtsstaat, met een slagvaardig bestuur. BZK, duidelijk voor mensen'. Dit is de missie van BZK. Het ministerie van BZK borgt de kernwaarden van de democratie. BZK staat voor een goed en slagvaardig openbaar bestuur en een overheid waar burgers op kunnen vertrouwen. BZK draagt eraan bij dat burgers kunnen wonen in betaalbare, veilige en energiezuinige woningen in een buurt waar iedereen meetelt en meedoet en het prettig leven is.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-binnenlandse-zaken-en-koninkrijksrelaties",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_bz = Ministry::create([
            "name" => "Ministerie van Buitenlandse Zaken", 
            "abbreviation" => "BZ",
            "description" => "Het ministerie van Buitenlandse Zaken (BZ) maakt ons Koninkrijk veiliger en welvarender en zet zich in voor een eerlijke en duurzame wereld. BZ steunt ook 24/7 Nederlanders in het buitenland. Dat alles doen we samen met onze Nederlandse en buitenlandse partners. Voor Nederland, wereldwijd.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-buitenlandse-zaken",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_def = Ministry::create([
            "name" => "Ministerie van Defensie", 
            "abbreviation" => "DEF",
            "description" => "Defensie staat voor vrede en veiligheid, in Nederland en daarbuiten. De krijgsmacht levert een bijdrage aan de stabiliteit en vrijheid in de wereld. Het ministerie van Defensie bestaat uit het departement (de Bestuursstaf), 4 krijgsmachtdelen (Koninklijke Marine, Koninklijke Landmacht, Koninklijke Luchtmacht en Koninklijke Marechaussee), het Defensie Ondersteuningscommando en de Defensie Materieel Organisatie.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-defensie",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_ezk = Ministry::create([
            "name" => "Ministerie van Economische Zaken en Klimaat", 
            "abbreviation" => "EZK",
            "description" => "Het ministerie van Economische Zaken en Klimaat (EZK) staat voor een duurzaam, ondernemend Nederland. We zetten ons in voor een uitstekend ondernemersklimaat en een sterke internationale concurrentiepositie. Door de juiste randvoorwaarden te creëren en door ondernemers de ruimte te geven om te vernieuwen en te groeien. Door samenwerking te stimuleren tussen onderzoekers en ondernemers. Zo bouwen we onze topposities in energie, industrie en diensten verder uit en investeren we in een krachtig en duurzaam Nederland.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-economische-zaken-en-klimaat",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_fin = Ministry::create([
            "name" => "Ministerie van Financiën", 
            "abbreviation" => "FIN",
            "description" => "Het ministerie van Financiën regelt alles wat te maken heeft met moneys.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-financien",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_iw = Ministry::create([
            "name" => "Ministerie van Infrastructuur en Waterstaat", 
            "abbreviation" => "I&W",
            "description" => "Het ministerie van Infrastructuur en Waterstaat (IenW) zet in op leefbaarheid en bereikbaarheid, met een vlotte doorstroming in een goed ingerichte, schone en veilige omgeving. Het ministerie werkt aan krachtige verbindingen over de weg, spoor, het water en door de lucht, beschermt tegen wateroverlast en bevordert de kwaliteit van lucht en water. Een leefbaar, bereikbaar en veilig Nederland. Daar werkt IenW aan.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-infrastructuur-en-waterstaat",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_lnv = Ministry::create([
            "name" => "Ministerie van Landbouw, Natuur en Voedselkwaliteit", 
            "abbreviation" => "LNV",
            "description" => "Het ministerie van Landbouw, Natuur en Voedselkwaliteit staat voor een eerlijke en verantwoorde landbouw en visserij. Boeren, tuinders en vissers moeten economisch perspectief hebben en produceren in verbondenheid met waarden van duurzaamheid en welzijn. Samen met alle betrokkenen wordt er gewerkt aan het herstel en behoud van Nederlandse natuur. Het ministerie van LNV wil de internationale koppositie van de agrarische sector verstevigen met een nadruk op het benutten van kennis en innovatie. Daarmee draagt Nederland bij aan de aanpak van het wereldvoedselvraagstuk. Verder stimuleert het ministerie de vitalisering van de plattelandsregio’s in Nederland.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-landbouw-natuur-en-voedselkwaliteit",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_jv = Ministry::create([
            "name" => "Ministerie van Justitie en Veiligheid", 
            "abbreviation" => "J&V",
            "description" => "Het ministerie van Justitie en Veiligheid zorgt voor de rechtsstaat in Nederland, zodat mensen in vrijheid kunnen samenleven, ongeacht hun levensstijl of opvattingen. Justitie en Veiligheid werkt aan een veiliger en rechtvaardiger samenleving door mensen rechtsbescherming te geven en waar nodig in te grijpen in hun leven. Soms is dat een ingrijpende maatregel, soms worden nieuwe perspectieven geopend. Altijd zijn het ingrepen die alleen Justitie en Veiligheid kan en mag doen. Recht raakt mensen.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-justitie-en-veiligheid",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_ocw = Ministry::create([
            "name" => "Ministerie van Onderwijs, Cultuur en Wetenschap", 
            "abbreviation" => "OCW",
            "description" => "Het ministerie van Onderwijs, Cultuur en Wetenschap (OCW) werkt aan een slim, vaardig en creatief Nederland. OCW wil dat iedereen goed onderwijs volgt en zich voorbereidt op zelfstandigheid en verantwoordelijkheid. Verder wil het ministerie dat iedereen cultuur kan beleven en dat leraren, kunstenaars en wetenschappers hun werk kunnen doen.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-onderwijs-cultuur-en-wetenschap",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_szw = Ministry::create([
            "name" => "Ministerie van Sociale Zaken en Werkgelegenheid", 
            "abbreviation" => "SZW",
            "description" => "Werk en bestaanszekerheid voor iedereen: samen werken voor samen leven. Het ministerie van Sociale Zaken en Werkgelegenheid werkt aan eerlijk, gezond en veilig werk in Nederland. Iedereen moet de kans krijgen om mee te doen en zich te ontwikkelen zodat je zelf kan bijdragen aan je eigen toekomst. Als het tegenzit zorgen we voor een vangnet, en als je met pensioen gaat voor een inkomen. Dat kan alleen in een land waar mensen er voor elkaar zijn.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-sociale-zaken-en-werkgelegenheid",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        $min_vws = Ministry::create([
            "name" => "Ministerie van Volksgezondheid, Welzijn en Sport", 
            "abbreviation" => "VWS",
            "description" => "We worden met z’n allen steeds ouder en blijven steeds langer vitaal. Daarnaast kunnen we steeds meer ziektes behandelen. Dat is prachtig. Tegelijk maken mensen zich zorgen over de toekomst van de zorg, die veel geld kost en met een tekort aan werknemers kampt. Het ministerie van Volksgezondheid, Welzijn en Sport wil dat mensen erop kunnen vertrouwen dat de zorg goed, betaalbaar en beschikbaar is en blijft.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-volksgezondheid-welzijn-en-sport",
            "logo_url" => "storage/images/ministries/algemenezaken.jpg"
        ]);

        //
        // Organization types
        //

        $extern = OrganizationType::create([
            "name" => "extern",
            "label" => "Extern",
            "description" => "Ingehuurd via een extern bedrijf dat samenwerkt met de rijksoverheid.",
        ]);

        $agentschap = OrganizationType::create([
            "name" => "agency",
            "label" => "Agentschap",
            "description" => "Een agentschap is een zelfstandig onderdeel van een ministerie dat een eigen beheer voert. Er zijn in totaal 30 agentschappen die het beleid van de Rijksoverheid uitvoeren."
        ]);

        $zbo = OrganizationType::create([
            "name" => "zbo",
            "label" => "Zelfstandig bestuursorgaan",
            "description" => "Een zelfstandig bestuursorgaan (zbo) voert een overheidstaak uit. Zbo’s hebben een bijzondere positie binnen de Rijksoverheid. Zbo’s oefenen ten eerste meestal openbaar gezag uit. Dit houdt in dat een zbo iets kan gebieden of verbieden. Zij zijn ten tweede zelfstandig; ze zijn niet hiërarchisch ondergeschikt aan een minister."
        ]);

        $dienst = OrganizationType::create([
            "name" => "service",
            "label" => "Dienst",
            "description" => "Onder de verantwoordelijkheid van de ministeries vallen veel ambtelijke organisaties en uitvoerende diensten. Denk aan de Belastingdienst.",
        ]);
        
        //
        // Organizations
        //
            // DPC
        $dpc = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_az->id,
            "name" => "Dienst Publiek en Communicatie",
            "abbreviation" => "DPC",
            "description" => "DPC ondersteunt alle departementen en uitvoeringsorganisaties van de Rijksoverheid in de communicatie met publiek en professionals.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-algemene-zaken/organisatie/organogram/dienst-publiek-en-communicatie"
        ]);

        OrganizationLocation::create([
            "organization_id" => $dpc->id,
            "address" => "Laan van Eik en Duinen 189, 2564 GR Den Haag, Netherlands",
            "latitude" => "52.07187016290026",
            "longitude" => "4.263480907022426"
        ]);
        
        // RvIG
        $rvig = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_bzk->id,
            "name" => "Rijksdienst voor Identiteitsgegevens",
            "abbreviation" => "RvIG",
            "description" => "RvIG beheert de Basisregistratie Personen (BRP) en de Persoonsinformatievoorziening Nederlandse Antillen en Aruba (PIVA) en is verantwoordelijk voor de technische systemen voor de opslag en uitwisseling van persoonsgegevens. We geven de burgerservicenummers uit, die de basis vormen voor de uitwisseling van die gegevens. En we beheren de reisdocumenten van het koninkrijk.",
            "website_url" => "https://www.rvig.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $rvig->id,
            "address" => "Druivenstraat 44, 2564 VJ Den Haag, Netherlands",
            "latitude" => "52.071370019331226",
            "longitude" => "4.259826843163751"
        ]);

        // FMH
        $fmh = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_bzk->id,
            "name" => "FMHaaglanden",
            "abbreviation" => "FMH",
            "description" => "FMH levert facilitaire producten en diensten binnen het Rijk in de Haagse regio. Dit doen zij met ruim 500 medewerkers rondom de werkplekken van circa 30.000 rijkscollega's zodat zij comfortabel kunnen werken.",
            "website_url" => "https://www.rijksoverheid.nl/ministeries/ministerie-van-binnenlandse-zaken-en-koninkrijksrelaties/organisatie/organogram/directoraat-generaal-vastgoed-en-bedrijfsvoering-rijk-dgvbr/shared-service-organisaties"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $fmh->id,
            "address" => "Tomatenstraat 141, 2564 CN Den Haag, Netherlands",
            "latitude" => "52.06970810013497",
            "longitude" => "4.254229277317108"
        ]);

        // DHC
        $dhc = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_bzk->id,
            "name" => "Dienst Huurcommissie",
            "abbreviation" => "DHC",
            "description" => "De Huurcommissie is een onpartijdige organisatie voor het voorkomen, helpen oplossen en formeel beslechten van geschillen tussen huurders en verhuurders.",
            "website_url" => "https://www.huurcommissie.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $dhc->id,
            "address" => "Tomatenstraat 176, 2564 CX Den Haag, Netherlands",
            "latitude" => "52.06872686426975",
            "longitude" => "4.252250476751669"
        ]);

        // LOGIUS
        $logius = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_bzk->id,
            "name" => "Logius",
            "description" => "Logius zorgt voor producten en diensten voor de digitale overheid. We bieden ICT-oplossingen en standaarden die alle overheidsorganisaties gebruiken in hun digitale dienstverlening. Zo helpen we overheid, bedrijven en burgers om snel, eenvoudig en veilig met elkaar te communiceren.",
            "website_url" => "https://www.logius.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $logius->id,
            "address" => "Dotterbloemlaan 2, 2555 TN Den Haag, Netherlands",
            "latitude" => "52.0686213411834",
            "longitude" => "4.239290042791708"
        ]);

        // P-DIREKT
        $pdirekt = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_bzk->id,
            "name" => "P-Direkt",
            "description" => "P-Direkt staat voor het leveren van een efficiënte en kwalitatief hoogwaardige salaris- en personeelsadministratie. Wij ondersteunen meer dan 120.000 werkgevers en werknemers binnen de sector Rijk met innovatieve technologie bij het gemakkelijk regelen van hun personeelszaken.",
            "website_url" => "https://www.p-direkt.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $pdirekt->id,
            "address" => "Van Speijkstraat 1, 2518 EV Den Haag, Netherlands",
            "latitude" => "52.08423604549163",
            "longitude" => "4.29568080512081"
        ]);
        
        // RVB
        $rvb = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_bzk->id,
            "name" => "Rijksvastgoedbedrijf",
            "abbreviation" => "RVB",
            "description" => "Het Rijksvastgoedbedrijf is de vastgoedorganisatie van en voor de Rijksoverheid en is onderdeel van het ministerie van Binnenlandse Zaken en Koninkrijksrelaties. Wij zijn verantwoordelijk voor het beheer en de instandhouding van de grootste en meest diverse vastgoedportefeuille van Nederland.",
            "website_url" => "https://www.rijksvastgoedbedrijf.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $rvb->id,
            "address" => "Burgemeester de Monchyplein 215, 2585 DJ Den Haag, Netherlands",
            "latitude" => "52.089193626562",
            "longitude" => "4.302289768133505"
        ]);
        
        // SSC-ICT
        $ssc = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_bzk->id,
            "name" => "Shared Service Center ICT",
            "abbreviation" => "SSC-ICT",
            "description" => "Wij zijn Shared Service Center-ICT (SSC-ICT), partner van en voor het Rijk. Wij zijn er altijd en onze ambities zijn hoog. Samen met acht ministeries werken we aan een eigentijdse en compacte Rijksdienst. We zijn als een spin in het web en verbinden onze ICT-kennis met kennis van de ministeries.",
            "website_url" => "https://www.ssc-ict.nl/"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $ssc->id,
            "address" => "Javastraat 135, 2585 AJ Den Haag, Netherlands",
            "latitude" => "52.08866625049951",
            "longitude" => "4.31190280524288"
        ]);

        // UBR
        $ubr = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_bzk->id,
            "name" => "Uitvoeringsorganisatie Bedrijfsvoering Rijk",
            "abbreviation" => "UBR",
            "description" => "UBR innoveert en werkt voortdurend aan de kwaliteit van de dienstverlening. We werken aan een beter, sterker en slimmere rijksoverheid. Als het gaat om specifieke producten of specialistische dienstverlening hebben wij mooie opdrachten gedaan. De voorbeelden op deze pagina geven een beeld van de brede dienstverlening van UBR.",
            "website_url" => "https://www.ubrijk.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $ubr->id,
            "address" => "Vondelstraat 65, 2513 EP Den Haag, Netherlands",
            "latitude" => "52.08054387201227",
            "longitude" => "4.300144000921591"
        ]);

        // PARESTO (MET BEETJE PESTO)
        $paresto = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_def->id,
            "name" => "Paresto",
            "description" => "Verzorgt de catering op ongeveer 90 Defensielocaties in Nederland en Duitsland. Dat doet Paresto ook voor militairen op oefening of uitzending. Het bedrijf ontwikkelt rantsoenen voor verschillende inzetgebieden. En adviseert commandanten over de voeding voor militairen.",
            "website_url" => "https://www.defensie.nl/organisatie/defensieondersteuningscommando/eenheden/divisie-facilitair-logistiek-en-beveiliging"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $paresto->id,
            "address" => "Slijkeinde 23, 2513 VC Den Haag, Netherlands",
            "latitude" => "52.077589913312124",
            "longitude" => "4.302118106756552"
        ]);
        
        // RVO
        $rvo = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Rijksdienst voor Ondernemend Nederland",
            "abbreviation" => "RVO",
            "description" => "De Rijksoverheid zet zich in voor een uitstekend ondernemersklimaat. Ministeries stippelen daar beleid voor uit. De taak om dit beleid uit te voeren ligt bij de Rijksdienst voor Ondernemend Nederland (RVO).",
            "website_url" => "https://www.rvo.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $rvo->id,
            "address" => "Korte Lombardstraat 9, 2512 VR Den Haag, Netherlands",
            "latitude" => "52.075901849142724",
            "longitude" => "4.30392055121456"
        ]);

        // AT
        $at = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Agentschap Telecom",
            "abbreviation" => "AT",
            "description" => "Agentschap Telecom voert beleid uit en houdt toezicht op frequentiegebruik van radio en televisie, telefonie en allerlei radiozendapparatuur. Ook houdt het agentschap toezicht op veilig gebruik van internet en op meetapparatuur zoals weegschalen en benzinepompen.",
            "website_url" => "https://www.agentschaptelecom.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $at->id,
            "address" => "Lange Beestenmarkt 21J, 2512 EB Den Haag, Netherlands",
            "latitude" => "52.07389719003285",
            "longitude" => "4.30692462531124"
        ]);

        // NVWA
        $nvwa = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Nederlandse Voedsel- en Warenautoriteit",
            "abbreviation" => "NVWA",
            "description" => "De NVWA bewaakt de veiligheid van voedsel en consumentenproducten, de gezondheid van dieren en planten, het dierenwelzijn en handhaaft de natuurwetgeving.",
            "website_url" => "https://www.nvwa.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $nvwa->id,
            "address" => "Koningstraat 271, 2515 JJ Den Haag, Netherlands",
            "latitude" => "52.070415199737724",
            "longitude" => "4.312503620062216"
        ]);

        // DICTU
        $dictu = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Dienst ICT Uitvoering",
            "abbreviation" => "DICTU",
            "description" => "De Dienst ICT Uitvoering (DICTU) levert ICT en digitale diensten voor het Ministerie van Economische Zaken en Klimaat, het Ministerie van Landbouw, Natuur en Voedselkwaliteit en een aantal andere ministeries. We zetten onze kennis en ervaring in om de beleids- en organisatiedoelen van onze opdrachtgevers optimaal te ondersteunen.",
            "website_url" => "https://www.dictu.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $dictu->id,
            "address" => "Lyonnetstraat 108, 2522 NH Den Haag, Netherlands",
            "latitude" => "52.064611279120605",
            "longitude" => "4.327867313299521"
        ]);

        // NEa
        $nea = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Nederlandse Emissieautoriteit",
            "abbreviation" => "NEa",
            "description" => "De Nederlandse Emissieautoriteit (NEa)  is uitvoeringsorganisatie en toezichthouder binnen het beleidsthema Klimaat en draagt bij aan de reductie van CO2 uitstoot en energietransitie in Nederland. Zij borgt dat bedrijven, die deelnemen aan het Europese emissiehandelssysteem (EU ETS) of vallen onder de wet- en regelgeving voor hernieuwbare energie vervoer en brandstoffen luchtverontreiniging, voldoen aan hun verplichtingen.",
            "website_url" => "https://www.emissieautoriteit.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $nea->id,
            "address" => "Wenckebachstraat 25, 2522 CH Den Haag, Netherlands",
            "latitude" => "52.062183961358016",
            "longitude" => "4.33138637152706"
        ]);

        // DSTA
        $dsta = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_fin->id,
            "name" => "Agentschap van de Generale Thesaurie",
            "abbreviation" => "DSTA",
            "description" => "De minister van Financiën is verantwoordelijk voor een solide en efficiënte financiering van de staatsschuld. Het Agentschap, in het buitenland en in de financiële markten bekend als Dutch State Treasury Agency, vervult deze rol voor de minister door het financieringsbeleid voor te bereiden. Daarnaast vertegenwoordigt het Agentschap de minister bij het afsluiten van transacties op de financiële markten. Sinds 1 januari 2009 is het Agentschap ook verantwoordelijk voor het beleid en de uitvoering van schatkistbankieren en voor (de aanbesteding van) het betalingsverkeer van het Rijk.",
            "website_url" => "https://www.dsta.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $dsta->id,
            "address" => "Goeverneurlaan 127, 2523 BD Den Haag, Netherlands",
            "latitude" => "52.060073143027076",
            "longitude" => "4.325120731268271"
        ]);

        // RWS
        $rws = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_iw->id,
            "name" => "Rijkswaterstaat",
            "abbreviation" => "RWSz",
            "description" => "Rijkswaterstaat is de uitvoeringsorganisatie van het ministerie van Infrastructuur en Waterstaat en werkt dagelijks aan een veilig, leefbaar en bereikbaar Nederland.",
            "website_url" => "https://www.rijkswaterstaat.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $rws->id,
            "address" => "Marcellus Emantslaan 19, 2274 XL Voorburg, Netherlands",
            "latitude" => "52.0743192310102",
            "longitude" => "4.357822223577841"
        ]);

        // KNMI
        $knmi = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_iw->id,
            "name" => "Koninklijk Nederlands Meteorologisch Instituut",
            "abbreviation" => "KNMI",
            "description" => "Het KNMI is een agentschap van het ministerie van Infrastructuur en Waterstaat en adviseert en waarschuwt de samenleving om risico’s in de geofysische leefomgeving terug te dringen en kansen te creëren, ten behoeve van de veiligheid, duurzaamheid en economie van Nederland.",
            "website_url" => "https://www.knmi.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $knmi->id,
            "address" => "Zuidwerfplein 16, 2594 CV Den Haag, Netherlands",
            "latitude" => "52.10164415671545",
            "longitude" => "4.359453006658896"
        ]);

        // ILT
        $ilt = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_iw->id,
            "name" => "Inspectie Leefomgeving en Transport",
            "abbreviation" => "ILT",
            "description" => "De Inspectie Leefomgeving en Transport (ILT) is de toezichthouder van het ministerie van Infrastructuur en Waterstaat. Ruim 1100 medewerkers werken dagelijks aan veiligheid, zekerheid en vertrouwen in transport, infrastructuur, milieu en wonen.",
            "website_url" => "https://www.ilent.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $ilt->id,
            "address" => "Van Neckstraat 135, 2597 SE Den Haag, Netherlands",
            "latitude" => "52.10428020771785",
            "longitude" => "4.323489948187216"
        ]);

        // DUO
        $duo = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_ocw->id,
            "name" => "Dienst Uitvoering Onderwijs",
            "abbreviation" => "DUO",
            "description" => "DUO is de uitvoeringsorganisatie van de Rijksoverheid voor het onderwijs. DUO financiert en informeert onderwijsdeelnemers en onderwijsinstellingen en organiseert examens. Zo maakt DUO goed onderwijs mogelijk.",
            "website_url" => "https://www.duo.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $duo->id,
            "address" => "Renbaanstraat 81, 2586 EZ Den Haag, Netherlands",
            "latitude" => "52.108602994078275",
            "longitude" => "4.28169040289913"
        ]);

        // NA
        $na = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_ocw->id,
            "name" => "Nationaal Archief",
            "abbreviation" => "NA",
            "description" => "In het Nationaal Archief vind je antwoorden op vragen over jouw leven, onze geschiedenis en onze samenleving. We helpen je aan nieuwe inzichten door je altijd en overal toegang te geven tot ons nationaal geheugen.",
            "website_url" => "https://www.nationaalarchief.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $na->id,
            "address" => "Frankenslag 342, 2582 JB Den Haag, Netherlands",
            "latitude" => "52.09236401767946",
            "longitude" => "4.272506519232138"
        ]);

        // aSZW
        $aszw = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_szw->id,
            "name" => "Agentschap SZW",
            "abbreviation" => "aSZW",
            "description" => "Wij voeren (Europese) subsidieregelingen en andere complexe opdrachten uit voor opdrachtgevers en aanvragers op het gebied van sociaal economisch beleid, in het bijzonder werk en inkomen. Denk hierbij aan Europese subsidies zoals het Europees Sociaal Fonds (ESF), het Europees Globaliseringsfonds (EGF) en de Europese migratie- en veiligheidsfondsen.",
            "website_url" => "https://www.uitvoeringvanbeleidszw.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $aszw->id,
            "address" => "Haanplein 10, 2566 VD Den Haag, Netherlands",
            "latitude" => "52.08097211738867",
            "longitude" => "4.25422458258663"
        ]);
            
        // CJIB
        $cjib = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_jv->id,
            "name" => "Centraal Justitieel Incassobureau",
            "abbreviation" => "CJIB",
            "description" => "Het Centraal Justitieel Incassobureau (CJIB) is een uitvoeringsorganisatie van het ministerie van Justitie en Veiligheid. We zijn gevestigd in Leeuwarden en verzorgen de inning voor veel overheidsorganisaties.",
            "website_url" => "https://www.cjib.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $cjib->id,
            "address" => "Lupineweg 34, 2555 RM Den Haag, Netherlands",
            "latitude" => "52.07063250586862",
            "longitude" => "4.24074916449581"
        ]);

        // DJI
        $dji = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_jv->id,
            "name" => "Dienst Justitiële Inrichtingen",
            "abbreviation" => "DJI",
            "description" => "De Dienst Justitiële Inrichtingen levert een bijdrage aan de veiligheid van de samenleving door de tenuitvoerlegging van vrijheidsstraffen en vrijheidsbenemende maatregelen en door de aan onze zorg toevertrouwde personen de kans te bieden een maatschappelijk aanvaardbaar bestaan op te bouwen.",
            "website_url" => "https://www.dji.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $dji->id,
            "address" => "Schapenlaan 136, 2512 HX Den Haag, Netherlands",
            "latitude" => "52.07242628362748",
            "longitude" => "4.296024127874716"
        ]);

        // IND
        $ind = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_jv->id,
            "name" => "Immigratie- en Naturalisatiedienst",
            "abbreviation" => "IND",
            "description" => "Van vluchtelingen die niet veilig zijn in eigen land tot specialistische kenniswerkers: de IND beoordeelt alle verblijfsaanvragen van mensen die in Nederland willen wonen of die Nederlander willen worden. Dat doen we zorgvuldig, want ons werk doet ertoe.",
            "website_url" => "https://ind.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $ind->id,
            "address" => "Nunspeetlaan 52, 2573 HH Den Haag, Netherlands",
            "latitude" => "52.068311039440026",
            "longitude" => "4.284608646307333"
        ]);

        // Justis
        $justis = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_jv->id,
            "name" => "Justis",
            "description" => "De screeningsautoriteit Justis werkt aan een veiligere en rechtvaardigere samenleving door het screenen van personen en organisaties.",
            "website_url" => "https://www.justis.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $justis->id,
            "address" => "Hendrik van Deventerstraat 39, 2563 XP Den Haag, Netherlands",
            "latitude" => "52.072637311567206",
            "longitude" => "4.27345065680538"
        ]);

        // NFI
        $nfi = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_ocw->id,
            "name" => "Nederlands Forensisch Instituut",
            "abbreviation" => "NFI",
            "description" => "Het Nederlands Forensisch Instituut (NFI) streeft ernaar de meest innovatieve en klantgerichte leverancier van forensische producten en diensten te zijn. Het NFI voorziet nationale en internationale organisaties die zich inzetten voor vrede, recht en veiligheid van betrouwbare informatie uit sporen. Door continu te investeren in kennis en innovatie speelt het NFI in op actuele maatschappelijke, technologische en wetenschappelijke ontwikkelingen. Het NFI loopt hierin ook internationaal gezien voorop.",
            "website_url" => "https://www.forensischinstituut.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $nfi->id,
            "address" => "Vlierboomstraat 286, 2564 HS Den Haag, Netherlands",
            "latitude" => "52.07042146845329",
            "longitude" => "4.261863513861044"
        ]);

        // RIVM
        $rivm = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_vws->id,
            "name" => "Rijksinstituut voor Volksgezondheid en Milieu",
            "abbreviation" => "RIVM",
            "description" => "Het Rijksinstituut voor Volksgezondheid en Milieu (RIVM) zet zich al meer dan 100 jaar in voor een gezonde bevolking in een gezonde leefomgeving. Met een centrale rol in de infectieziektebestrijding en in landelijke preventie- en screeningsprogramma's. En via onafhankelijk (wetenschappelijk) onderzoek op het vlak van Volksgezondheid en Zorg, en Milieu en Veiligheid. Als 'trusted advisor' voor de samenleving. Zo ondersteunt het RIVM burgers, professionals en overheden bij de uitdaging onszelf en onze leefomgeving gezond te houden.",
            "website_url" => "https://www.rivm.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $rivm->id,
            "address" => "Aronskelkweg 1, 2555 GA Den Haag, Netherlands",
            "latitude" => "52.0685220868299",
            "longitude" => "4.24049167243038"
        ]);

        // CIBG
        $cibg = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_vws->id,
            "name" => "CIBG",
            "abbreviation" => "CIBG",
            "description" => "Het CIBG is een uitvoeringsorganisatie en valt onder de verantwoordelijkheid van het ministerie van Volksgezondheid, Welzijn en Sport. We werken vanuit twee vestigingen in Den Haag en Heerlen.",
            "website_url" => "https://www.cibg.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $cibg->id, 
            "address" => "Traviatastraat 178, 2555 VK Den Haag, Netherlands",
            "latitude" => "52.06419541596692",
            "longitude" => "4.242637439642294"
        ]);

        // aCBG
        $acbg = Organization::create([
            "organization_type_id" => $agentschap->id,
            "ministry_id" => $min_vws->id,
            "name" => "Agentschap CBG",
            "abbreviation" => "CBG",
            "description" => "Als onafhankelijke autoriteit reguleert het CBG de kwaliteit, werking en veiligheid van een medicijn, en stimuleert het CBG het juiste gebruik door de juiste patiënt. Van een pijnstiller bij de drogist tot een door een medisch specialist voorgeschreven behandeling. Van traditionele tot geheel nieuwe middelen. Voor de medicijnen in Nederland en - samen met Europese collega's - voor de medicijnen in Europa.",
            "website_url" => "https://www.cbg-meb.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $acbg->id,
            "address" => "Plantentuinen 3, 2551 VT Den Haag, Netherlands",
            "latitude" => "52.057651849134174",
            "longitude" => "4.2463281592467865"
        ]);

        // ----------------------------------------------------
        // Organizations (Zelfstandige bestuursorganen)
        // ----------------------------------------------------
            
        // ACM
        $acm = Organization::create([
            "organization_type_id" => $zbo->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Autoriteit Consument en Markt",
            "abbreviation" => "ACM",
            "description" => "De Autoriteit Consument & Markt (ACM) is een onafhankelijke toezichthouder die zich sterk maakt voor consumenten en bedrijven. ACM houdt toezicht op de mededinging, een aantal specifieke sectoren en het consumentenrecht. Met als doel een gelijk speelveld met bedrijven die zich aan de regels houden, en goedgeïnformeerde consumenten die voor hun recht opkomen.",
            "website_url" => "https://www.acm.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $acm->id,
            "address" => "Dekkershoek 31, 2552 DA Den Haag, Netherlands",
            "latitude" => "52.0470956890043",
            "longitude" => "4.242980762396201"
        ]);

        // AFM
        $afm = Organization::create([
            "organization_type_id" => $zbo->id,
            "ministry_id" => $min_fin->id,
            "name" => "Autoriteit Financiële Markten",
            "abbreviation" => "AFM",
            "description" => "De Autoriteit Financiële Markten (AFM) houdt toezicht op de financiële markten: op sparen, beleggen, verzekeren, lenen, pensioenen, kapitaalmarkten, asset management en accountantsorganisaties en verslaggeving. Het is belangrijk dat het publiek, het bedrijfsleven en de overheid vertrouwen hebben in de financiële markten. En dat de markten op een duidelijke en eerlijke manier werken.",
            "website_url" => "http://www.afm.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $afm->id,
            "address" => "Marterrade 248, 2544 ME Den Haag, Netherlands",
            "latitude" => "52.04603993581833",
            "longitude" => "4.258430286321982"
        ]);

        // ANVS
        $anvs = Organization::create([
            "organization_type_id" => $zbo->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Autoriteit Nucleaire Veiligheid en Stralingsbescherming",
            "abbreviation" => "ANVS",
            "description" => "De Autoriteit Nucleaire Veiligheid en Stralingsbescherming (ANVS) ziet er op toe dat de nucleaire veiligheid en stralingsbescherming in Nederland voldoen aan de hoogste eisen. De ANVS stelt daarvoor regels op, verleent vergunningen, ziet toe op de naleving daarvan en kan handhavend optreden. Door de vorming van de ANVS in 2015 zijn kennis en expertise gebundeld die nodig is om deze taken uit te voeren.",
            "website_url" => "https://www.autoriteitnvs.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $anvs->id,
            "address" => "Bouwlustlaan 58, 2544 JT Den Haag, Netherlands",
            "latitude" => "52.0445618394562",
            "longitude" => "4.257142825994833"
        ]);

        // AP
        $ap = Organization::create([
            "organization_type_id" => $zbo->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Autoriteit Persoonsgegevens",
            "abbreviation" => "AP",
            "description" => "De Autoriteit Persoonsgegevens is de onafhankelijke toezichthouder in Nederland die de bescherming van persoonsgegevens bevordert en bewaakt.",
            "website_url" => "https://www.autoriteitpersoonsgegevens.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $ap->id,
            "address" => "Oosterhesselenstraat 582, 2545 SL Den Haag, Netherlands",
            "latitude" => "52.04688454036241",
            "longitude" => "4.272163196478232"
        ]);
        
        // KVK
        $kvk = Organization::create([
            "organization_type_id" => $zbo->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Kamer van Koophandel",
            "abbreviation" => "KvK",
            "description" => "KVK biedt informatie, voorlichting en ondersteuning aan ondernemers bij de belangrijkste thema’s op ondernemersgebied. Onze wettelijke taken zijn gericht op het registreren, informeren en adviseren van ondernemers.",
            "website_url" => "http://www.kvk.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $kvk->id,
            "address" => "Kerketuinenweg 65, 2544 CZ Den Haag, Netherlands",
            "latitude" => "52.048995981887344",
            "longitude" => "4.253881259832724"
        ]);

        // KNAW
        $knaw = Organization::create([
            "organization_type_id" => $zbo->id,
            "ministry_id" => $min_ezk->id,
            "name" => "Koninklijke Nederlandse Akademie van Wetenschappen",
            "abbreviation" => "KNAW",
            "description" => "De KNAW is zowel genootschap van excellente wetenschappers als institutenorganisatie. Het bestuur van de KNAW stelt de hoofdlijnen van het beleid en de begroting vast. De dagelijkse leiding is in handen van de algemeen directeur.",
            "website_url" => "http://www.knaw.nl"
        ]);
        
        OrganizationLocation::create([
            "organization_id" => $knaw->id,
            "address" => "Maartensdijklaan 498, 2541 VK Den Haag, Netherlands",
            "latitude" => "52.05005067605177",
            "longitude" => "4.279370159427231"
        ]);

        //        
        // Departments        
        //        
        
        OrganizationDepartment::create([
            "organization_id" => $ssc->id,
            "name" => "Innovatie"
        ]);

        OrganizationDepartment::create([
            "organization_id" => $ssc->id,
            "name" => "Mobile Development"
        ]);

        //
        // Assignment types
        //

        AssignmentType::create([
            "name" => "fulltime",
            "label" => "Fulltime",
        ]);
        
        AssignmentType::create([
            "name" => "partime",
            "label" => "Parttime",
        ]);
        
        AssignmentType::create([
            "name" => "zzp",
            "label" => "ZZP",
        ]);

        AssignmentType::create([
            "name" => "freelance",
            "label" => "Freelance werk",
        ]);
        
        AssignmentType::create([
            "name" => "contract",
            "label" => "Contractwerk",
        ]);

        AssignmentType::create([
            "name" => "bbl",
            "label" => "BBL",
        ]);

        AssignmentType::create([
            "name" => "internship",
            "label" => "Stageplek",
        ]);

        AssignmentType::create([
            "name" => "traineeship",
            "label" => "Traineeship",
        ]);
    }
}
