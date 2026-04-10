<?php
namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\State;
use App\Models\Station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Fetch Kerala state
        $state = State::where('code', 'KL')->first();

        if (! $state) {
            $this->command->error('Kerala state not found. Run StateSeeder first.');
            return;
        }

        $data = [
            // KASARAGOD DISTRICT
            1   => [
                "name"     => "Manjeshwaram",
                "district" => "KSD",
                "lsgs"     => ["Manjeshwaram", "Vorkady", "Meenja", "Paivalike", "Enmakaje", "Puthige", "Kumbla", "Mangalpady"],
            ],
            2   => [
                "name"     => "KASARAGOD",
                "district" => "KSD",
                "lsgs"     => ["KASARAGOD", "Mogral Puthur", "Madhur", "Badiadka", "Kumbadaje", "Bellur", "Karadka", "Chengala"],
            ],
            3   => [
                "name"     => "Udma",
                "district" => "KSD",
                "lsgs"     => ["Chemnad", "Udma", "Pallikkara", "Pullur-Periya", "Bediadka", "Kuttikole", "Delampady", "Muliyar"],
            ],
            4   => [
                "name"     => "Kanhangad",
                "district" => "KSD",
                "lsgs"     => ["Kanhangad", "Ajanoor", "Madikai", "Kinanoor Karindalam", "Kallar", "Panathady", "Balal", "Kodom-Bellur"],
            ],
            5   => [
                "name"     => "Thrikaripur",
                "district" => "KSD",
                "lsgs"     => ["Nileshwar", "Cheruvathur", "Pilicode", "Padne", "Valiyaparamba", "Thrikaripur", "Kayyur Cheemeni", "West Eleri", "East Eleri"],
            ],

            // KANNUR DISTRICT
            6   => [
                "name"     => "Payyanur",
                "district" => "KNR",
                "lsgs"     => ["Payyanur", "Cherupuzha", "Eramam Kuttoor", "Kangol Alapadamba", "Karivellur Peralam", "Peringome Vayakkara", "Ramanthali"],
            ],
            7   => [
                "name"     => "Kalliasseri",
                "district" => "KNR",
                "lsgs"     => ["Cherukunnu", "Ezhome", "Kalliasseri", "Kannapuram", "Cheruthazham", "Kunhimangalam", "Mattool", "Pattuvam", "Kadannappalli Panapuzha", "Madayi"],
            ],
            8   => [
                "name"     => "Taliparamba",
                "district" => "KNR",
                "lsgs"     => ["Anthoor", "Taliparamba", "Chapparapadavu", "Kolachery", "Kurumathur", "Kuttiattur", "Malappattam", "Mayyil", "Pariyaram"],
            ],
            9   => [
                "name"     => "Irikkur",
                "district" => "KNR",
                "lsgs"     => ["Sreekandapuram", "Alakode", "Chengalayi", "Eruvessi", "Irikkur", "Naduvil", "Payyavoor", "Udayagiri", "Ulikkal"],
            ],
            10  => [
                "name"     => "Azhikode",
                "district" => "KNR",
                "lsgs"     => ["Azhikode", "Chirakkal", "Valapattanam", "Pappinisseri", "Narath"],
            ],
            11  => [
                "name"     => "KANNUR",
                "district" => "KNR",
                "lsgs"     => ["KANNUR", "Munderi"],
            ],
            12  => [
                "name"     => "Dharmadom",
                "district" => "KNR",
                "lsgs"     => ["Anjarakandy", "Chembilode", "Kadambur", "Muzhappilangad", "Peralassery", "Dharmadom", "Pinarayi", "Vengad"],
            ],
            13  => [
                "name"     => "Thalassery",
                "district" => "KNR",
                "lsgs"     => ["Thalassery", "Chokli", "Eranholi", "Kadirur", "New Mahe", "Panniyannur"],
            ],
            14  => [
                "name"     => "Kuthuparamba",
                "district" => "KNR",
                "lsgs"     => ["Kuthuparamba", "Kunnothuparamba", "Kottayam Malabar", "Mokeri", "Panoor", "Pattiom", "Thrippangottur"],
            ],
            15  => [
                "name"     => "Mattanur",
                "district" => "KNR",
                "lsgs"     => ["Mattanur", "Chittariparamba", "Keezhallur", "Kolayad", "Koodali", "Maloor", "Mangattidam", "Padiyoor Kalliad", "Thillenkeri"],
            ],
            16  => [
                "name"     => "Peravoor",
                "district" => "KNR",
                "lsgs"     => ["Iritty", "Aralam", "Ayyankunnu", "Kanichar", "Kelakam", "Kottiyoor", "Muzhakkunnu", "Payam", "Peravoor"],
            ],

            // WAYANAD DISTRICT
            17  => [
                "name"     => "Mananthavady",
                "district" => "WYD",
                "lsgs"     => ["Mananthavady", "Edavaka", "Panamaram", "Thavinhal", "Thirunelly", "Thondernad", "Vellamunda"],
            ],
            18  => [
                "name"     => "Sulthan Bathery",
                "district" => "WYD",
                "lsgs"     => ["Sulthan Bathery", "Ambalavayal", "Meenangadi", "Mullankolly", "Nenmeni", "Noolpuzha", "Poothadi", "Pulpally"],
            ],
            19  => [
                "name"     => "Kalpetta",
                "district" => "WYD",
                "lsgs"     => ["Kalpetta", "Kaniyambetta", "Kottathara", "Meppadi", "Muppainad", "Muttil", "Padinjarathara", "Pozhuthana", "Thariode", "Vengappally", "Vythiri"],
            ],

            // KOZHIKODE DISTRICT
            20  => [
                "name"     => "Vatakara",
                "district" => "CLT",
                "lsgs"     => ["Vatakara", "Chorode", "Eramala", "Onchiyam", "Azhiyur"],
            ],
            21  => [
                "name"     => "Kuttiady",
                "district" => "CLT",
                "lsgs"     => ["Ayancheri", "Kunnummal", "Kuttiady", "Purameri", "Velom", "Thiruvallur", "Maniyur", "Villiappally"],
            ],
            22  => [
                "name"     => "Nadapuram",
                "district" => "CLT",
                "lsgs"     => ["Chekkiad", "Edacheri", "Kavillumpara", "Kayakkodi", "Maruthonkara", "Nadapuram", "Narippatta", "Thuneri", "Valayam", "Vanimal"],
            ],
            23  => [
                "name"     => "Koyilandy",
                "district" => "CLT",
                "lsgs"     => ["Koyilandy", "Payyoli", "Thikkodi", "Moodadi", "Chemancheri"],
            ],
            24  => [
                "name"     => "Perambra",
                "district" => "CLT",
                "lsgs"     => ["Perambra", "Thurayur", "Koothali", "Chakkittapara", "Changaroth", "Cheruvannur", "Nochad", "Meppayur", "Keezhariyur", "Arikkulam"],
            ],
            25  => [
                "name"     => "Balussery",
                "district" => "CLT",
                "lsgs"     => ["Balussery", "Atholi", "Koorachundu", "Kottur", "Naduvannur", "Panangad", "Ulliyeri", "Unnikulam", "Kayanna"],
            ],
            26  => [
                "name"     => "Elathur",
                "district" => "CLT",
                "lsgs"     => ["Chelannur", "Kakkodi", "Kakkur", "Kuruvattur", "Nanmanda", "Thalakulathur"],
            ],
            27  => [
                "name"     => "Kozhikode North",
                "district" => "CLT",
                "lsgs"     => ["Kozhikode North", "Kozhikode"],
            ],
            28  => [
                "name"     => "Kozhikode South",
                "district" => "CLT",
                "lsgs"     => ["Kozhikode South"],
            ],
            29  => [
                "name"     => "Beypore",
                "district" => "CLT",
                "lsgs"     => ["Kadalundi", "Ramanattukara", "Feroke"],
            ],
            30  => [
                "name"     => "Kunnamangalam",
                "district" => "CLT",
                "lsgs"     => ["Kunnamangalam", "Chathamangalam", "Mavoor", "Olavanna", "Peruvayal", "Perumanna"],
            ],
            31  => [
                "name"     => "Koduvally",
                "district" => "CLT",
                "lsgs"     => ["Kizhakkoth", "Koduvally", "Madavoor", "Narikkuni", "Omassery", "Kattippara", "Thamarassery"],
            ],
            32  => [
                "name"     => "Thiruvambady",
                "district" => "CLT",
                "lsgs"     => ["Thiruvambady", "Kodencheri", "Koodaranji", "Puduppadi", "Karassery", "Kodiyathur", "Mukkom"],
            ],

            // MALAPPURAM DISTRICT
            33  => [
                "name"     => "Kondotty",
                "district" => "MLP",
                "lsgs"     => ["Kondotty", "Cheekode", "Cherukavu", "Muthuvallur", "Pulikkal", "Vazhakkad", "Vazhayur"],
            ],
            34  => [
                "name"     => "Eranad",
                "district" => "MLP",
                "lsgs"     => ["Chaliyar", "Areacode", "Edavanna", "Kuzhimanna", "Urngattiri", "Kavanoor", "Keezhuparamba", "Eranad"],
            ],
            35  => [
                "name"     => "Nilambur",
                "district" => "MLP",
                "lsgs"     => ["Nilambur", "Amarambalam", "Edakkara", "Karulai", "Moothadam", "Pothukal", "Vazhikkadavu", "Chungathara"],
            ],
            36  => [
                "name"     => "Wandoor",
                "district" => "MLP",
                "lsgs"     => ["Wandoor", "Chokkad", "Kalikavu", "Karuvarakundu", "Mampad", "Porur", "Thiruvali", "Tuvvur"],
            ],
            37  => [
                "name"     => "Manjeri",
                "district" => "MLP",
                "lsgs"     => ["Manjeri", "Edappatta", "Pandikkad", "Trikkalangode", "Keezhattur"],
            ],
            38  => [
                "name"     => "Perinthalmanna",
                "district" => "MLP",
                "lsgs"     => ["Perinthalmanna", "Aliparamba", "Elamkulam", "Melattur", "Pulamanthole", "Tazhekkode", "Vettathur"],
            ],
            39  => [
                "name"     => "Mankada",
                "district" => "MLP",
                "lsgs"     => ["Kuruva", "Mankada", "Moorkanad", "Makkaraparamba", "Puzhakkattiri", "Angadippuram", "Koottilangadi"],
            ],
            40  => [
                "name"     => "MALAPPURAM",
                "district" => "MLP",
                "lsgs"     => ["MALAPPURAM", "Morayur", "Pookkottur", "Anakkayam", "Pulpatta", "Kodur"],
            ],
            41  => [
                "name"     => "Vengara",
                "district" => "MLP",
                "lsgs"     => ["Vengara", "Kannamangalam", "A R Nagar", "Parappur", "Othukkungal", "Ooramakam"],
            ],
            42  => [
                "name"     => "Vallikkunnu",
                "district" => "MLP",
                "lsgs"     => ["Vallikkunnu", "Chelembra", "Moonniyur", "Thenhipalam", "Pallikkal", "Peruvallur"],
            ],
            43  => [
                "name"     => "Tirurangadi",
                "district" => "MLP",
                "lsgs"     => ["Tirurangadi", "Parappanangadi", "Nannambra", "Thennala", "Edarikode", "Perumanna Kalari"],
            ],
            44  => [
                "name"     => "Tanur",
                "district" => "MLP",
                "lsgs"     => ["Tanur", "Tanalur", "Niramarutur", "Ozhur", "Ponmundam", "Cheriyamundam"],
            ],
            45  => [
                "name"     => "Tirur",
                "district" => "MLP",
                "lsgs"     => ["Tirur", "Vettom", "Thalakkad", "Valavannur", "Athavanad", "Thirunavaya", "Kalpakanchery"],
            ],
            46  => [
                "name"     => "Kottakkal",
                "district" => "MLP",
                "lsgs"     => ["Kottakkal", "Valanchery", "Marakkara", "Edayur", "Irimbiliyam", "Kuttippuram", "Ponmala"],
            ],
            47  => [
                "name"     => "Thavanur",
                "district" => "MLP",
                "lsgs"     => ["Thavanur", "Kalady", "Vattamkulam", "Edappal", "Mangalam", "Purathur", "Triprangode"],
            ],
            48  => [
                "name"     => "Ponnani",
                "district" => "MLP",
                "lsgs"     => ["Ponnani", "Maranchery", "Alamkode", "Nannamukku", "Veliyankode", "Perumpadappa"],
            ],

            // PALAKKAD DISTRICT
            49  => [
                "name"     => "Thrithala",
                "district" => "PKD",
                "lsgs"     => ["Thrithala", "Nagallassery", "Pattithara", "Parudur", "Anakkara", "Kappur", "Chalissery", "Thirumittacode"],
            ],
            50  => [
                "name"     => "Pattambi",
                "district" => "PKD",
                "lsgs"     => ["Pattambi", "Muthuthala", "Koppam", "Ongallur", "Vallapuzha", "Kulukkallur", "Vilayur", "Thiruvegappura"],
            ],
            51  => [
                "name"     => "Shornur",
                "district" => "PKD",
                "lsgs"     => ["Shornur", "Cherpulassery", "Chalavara", "Ananganadi", "Nellaya", "Vaniamkulam", "Trikkadeeri", "Vellinezhi"],
            ],
            52  => [
                "name"     => "Ottapalam",
                "district" => "PKD",
                "lsgs"     => ["Ottapalam", "Ambalapara", "Kadampazhipuram", "Karimpuzha", "Lakkidi Perur", "Pookkottukavu", "Sreekrishnapuram", "Thachanattukara"],
            ],
            53  => [
                "name"     => "Kongad",
                "district" => "PKD",
                "lsgs"     => ["Keralassery", "Kongad", "Mankara", "Tachampara", "Mannur", "Parli", "Kanjirappuzha", "Karimba", "Karakurussi"],
            ],
            54  => [
                "name"     => "Mannarkkad",
                "district" => "PKD",
                "lsgs"     => ["Mannarkkad", "Agali", "Alanallur", "Kottoppadam", "Kumaramputhur", "Pudur", "Sholayur", "Thenkara"],
            ],
            55  => [
                "name"     => "Malampuzha",
                "district" => "PKD",
                "lsgs"     => ["Akathethara", "Elappully", "Kodumba", "Malampuzha", "Marutharode", "Mundur", "Puduppariyaram", "Pudussery"],
            ],
            56  => [
                "name"     => "PALAKKAD",
                "district" => "PKD",
                "lsgs"     => ["PALAKKAD", "Kannadi", "Pirayiri", "Mathur"],
            ],
            57  => [
                "name"     => "Tarur",
                "district" => "PKD",
                "lsgs"     => ["Tarur", "Kavassery", "Kottayi", "Kuthannoor", "Peringottukurissi", "Puducode", "Kannambra", "Vadakkencherry"],
            ],
            58  => [
                "name"     => "Chittur",
                "district" => "PKD",
                "lsgs"     => ["Chittur Thathamangalam", "Eruthempathy", "Kozhinjampara", "Nalleppilly", "Pattanchery", "Perumatty", "Vadakarapathy", "Peruvemba", "Polpully"],
            ],
            59  => [
                "name"     => "Nenmara",
                "district" => "PKD",
                "lsgs"     => ["Nenmara", "Nelliampathi", "Elavanchery", "Koduvayur", "Kollengode", "Muthalamada", "Pallassana", "Ayiloor", "Puthunagaram", "Vadavannur"],
            ],
            60  => [
                "name"     => "Alathur",
                "district" => "PKD",
                "lsgs"     => ["Alathur", "Erimayur", "Kizhakkancherry", "Kuzhalmannam", "Melarcode", "Thenkurissi", "Vandazhi"],
            ],

            // THRISSUR DISTRICT
            61  => [
                "name"     => "Chelakkara",
                "district" => "TCR",
                "lsgs"     => ["Chelakkara", "Desamangalam", "Kondazhy", "Mullurkara", "Pazhayannur", "Thiruvilwamala", "Panjal", "Vallathol Nagar", "Varavoor"],
            ],
            62  => [
                "name"     => "Kunnamkulam",
                "district" => "TCR",
                "lsgs"     => ["Kunnamkulam", "Chowannur", "Kadavallur", "Kattakampal", "Porkulam", "Velur", "Erumapetty", "Kadangode"],
            ],
            63  => [
                "name"     => "Guruvayur",
                "district" => "TCR",
                "lsgs"     => ["Guruvayur", "Chavakkad", "Engandiyur", "Kadappuram", "Orumanayur", "Punnayur", "Punnayurkulam", "Vadakkekad"],
            ],
            64  => [
                "name"     => "Manalur",
                "district" => "TCR",
                "lsgs"     => ["Arimpur", "Manalur", "Elavally", "Pavaratty", "Choondal", "Vadanappally", "Pavaratty", "Mullassery", "Venkitangu", "Kandanassery"],
            ],
            65  => [
                "name"     => "Wadakkanchery",
                "district" => "TCR",
                "lsgs"     => ["Wadakkanchery", "Adat", "Thekkumkara", "Kolazhy", "Mulankunnathukavu", "Avanur", "Kaiparamba", "Tholur"],
            ],
            66  => [
                "name"     => "Ollur",
                "district" => "TCR",
                "lsgs"     => ["Madakkathara", "Nadathara", "Pananchery", "Puthur"],
            ],
            67  => [
                "name"     => "THRISSUR",
                "district" => "TCR",
                "lsgs"     => ["THRISSUR"],
            ],
            68  => [
                "name"     => "Nattika",
                "district" => "TCR",
                "lsgs"     => ["Nattika", "Thalikulam", "Valapad", "Thanniyam", "Paralam", "Cherpu", "Anthikad", "Avinissery", "Chazhoor"],
            ],
            69  => [
                "name"     => "Kaipamangalam",
                "district" => "TCR",
                "lsgs"     => ["Kaipamangalam", "Edavilangu", "Eriyad", "Mathilakam", "Perinjanam", "Sreenarayanapuram", "Edathiruthy"],
            ],
            70  => [
                "name"     => "Irinjalakuda",
                "district" => "TCR",
                "lsgs"     => ["Irinjalakuda", "Aloor", "Karalam", "Kattur", "Muriyad", "Padiyur", "Poomangalam", "Vellookkara"],
            ],
            71  => [
                "name"     => "Puthukkad",
                "district" => "TCR",
                "lsgs"     => ["Alagappa nagar", "Vallachira", "Mattathur", "Nenmanikkara", "Parappukkara", "Puthukkad", "Trikkur", "Varandarappilly"],
            ],
            72  => [
                "name"     => "Chalakudy",
                "district" => "TCR",
                "lsgs"     => ["Chalakudy", "Athirappilly", "Kadukutty", "Kodakara", "Kodassery", "Koratty", "Melur", "Pariyaram"],
            ],
            73  => [
                "name"     => "Kodungallur",
                "district" => "TCR",
                "lsgs"     => ["Kodungallur", "Mala", "Annamanada", "Kuzhur", "Poyya", "Puthenchira", "Vellangallur"],
            ],

            // ERNAKULAM DISTRICT
            74  => [
                "name"     => "Perumbavoor",
                "district" => "EKM",
                "lsgs"     => ["Perumbavoor", "Asamannoor", "Koovappady", "Mudakuzha", "Okkal", "Rayamangalam", "Vengola", "Vengoor"],
            ],
            75  => [
                "name"     => "Angamaly",
                "district" => "EKM",
                "lsgs"     => ["Angamaly", "Ayyampuzha", "Kalady", "Karukutty", "Malayattoor Neeleeswaram", "Manjapra", "Mookkannoor", "Parakkadavu", "Thuravoor"],
            ],
            76  => [
                "name"     => "Aluva",
                "district" => "EKM",
                "lsgs"     => ["Aluva", "Chengamanad", "Choornikkara", "Edathala", "Kanjoor", "Keezhmad", "Nedumbassery", "Sreemoolanagaram"],
            ],
            77  => [
                "name"     => "Kalamassery",
                "district" => "EKM",
                "lsgs"     => ["Kalamassery", "Eloor", "Alangad", "Kadungallur", "Karumalloor", "Kunnukara"],
            ],
            78  => [
                "name"     => "Paravur",
                "district" => "EKM",
                "lsgs"     => ["Paravur", "Chendamangalam", "Chittattukara", "Ezhikkara", "Kottuvally", "Puthenvelikkara", "Varapuzha", "Vadakkekara"],
            ],
            79  => [
                "name"     => "Vypin",
                "district" => "EKM",
                "lsgs"     => ["Edavanakkad", "Elamkunnapuzha", "Kuzhuppilly", "Nayarambalam", "Njarakkal", "Pallippuram", "Kadamakkudy", "Mulavukad"],
            ],
            80  => [
                "name"     => "Kochi",
                "district" => "EKM",
                "lsgs"     => ["Kochi", "Chellanam", "Kumbalangi"],
            ],
            81  => [
                "name"     => "Thrippunithura",
                "district" => "EKM",
                "lsgs"     => ["Thrippunithura", "Maradu", "Kumbalam", "Udayamperoor"],
            ],
            82  => [
                "name"     => "Ernakulam",
                "district" => "EKM",
                "lsgs"     => ["Cheranallur"],
            ],
            83  => [
                "name"     => "Thrikkakara",
                "district" => "EKM",
                "lsgs"     => ["Thrikkakara"],
            ],
            84  => [
                "name"     => "Kunnathunad",
                "district" => "EKM",
                "lsgs"     => ["Kizhakkambalam", "Kunnathunad", "Mazhuvannoor", "Aikaranad", "Poothrikka", "Thiruvaniyoor", "Vadavucode-Puthencruz", "Vazhakulam"],
            ],
            85  => [
                "name"     => "Piravom",
                "district" => "EKM",
                "lsgs"     => ["Piravom", "Amballur", "Edakkattuvayal", "Elanji", "Koothattukulam", "Maneed", "Pampakuda", "Ramamangalam", "Thirumarady", "Chottanikkara", "Mulanthuruthy", "Thiruvankulam"],
            ],
            86  => [
                "name"     => "Muvattupuzha",
                "district" => "EKM",
                "lsgs"     => ["Muvattupuzha", "Arakuzha", "Avoly", "Ayavana", "Kalloorkkad", "Manjalloor", "Marady", "Paipra", "Palakkuzha", "Valakom", "Paingottoor", "Pothanikkad"],
            ],
            87  => [
                "name"     => "Kothamangalam",
                "district" => "EKM",
                "lsgs"     => ["Kothamangalam", "Kavalangad", "Keerampara", "Kottappady", "Kuttampuzha", "Nellikuzhi", "Pallarimangalam", "Pindimana", "Varappetty"],
            ],

            // IDUKKI DISTRICT
            88  => [
                "name"     => "Devikulam",
                "district" => "IDK",
                "lsgs"     => ["Adimali", "Edamalakkudy", "Bisonvalley", "Chinnakanal", "Devikulam", "Kanthalloor", "Mankulam", "Marayoor", "Munnar", "Pallivasal", "Vattavada", "Vellathuval"],
            ],
            89  => [
                "name"     => "Udumbanchola",
                "district" => "IDK",
                "lsgs"     => ["Udumbanchola", "Erattayar", "Karunapuram", "Nedumkandam", "Pampadumpara", "Rajakkad", "Rajakumari", "Senapathy", "Vandanmedu", "Santhanpara"],
            ],
            90  => [
                "name"     => "Thodupuzha",
                "district" => "IDK",
                "lsgs"     => ["Thodupuzha", "Alacode", "Edavetty", "Karimannoor", "Karimkunnam", "Kodikulam", "Kumaramangalam", "Manakkad", "Muttom", "Purapuzha", "Udumbannoor", "Vannappuram", "Velliyamattom"],
            ],
            91  => [
                "name"     => "IDUKKI",
                "district" => "IDK",
                "lsgs"     => ["Arakulam", "IDUKKI", "Kanjikuzhy", "Kamakkshy", "Kanchiyar", "Kattappana", "Konnathady", "Mariapuram", "Vathikudy", "Vazhathope"],
            ],
            92  => [
                "name"     => "Peerumade",
                "district" => "IDK",
                "lsgs"     => ["Peerumade", "Chakkupallam", "Kumily", "Kokkayar", "Upputhara", "Vandiperiyar", "Elappara", "Peruvanthanam", "Ayyappancoil"],
            ],

            // KOTTAYAM DISTRICT
            93  => [
                "name"     => "Pala",
                "district" => "KTM",
                "lsgs"     => ["Pala", "Bharananganam", "Kadanad", "Karoor", "Kozhuvanal", "Meenachil", "Melukavu", "Moonnilavu", "Mutholy", "Ramapuram", "Thalanad", "Thalappalam", "Elikkulam"],
            ],
            94  => [
                "name"     => "Kaduthuruthy",
                "district" => "KTM",
                "lsgs"     => ["Kaduthuruthy", "Manjoor", "Mulakulam", "Njeezhoor", "Kadaplamattom", "Kanakkary", "Kidangoor", "Kuravilangad", "Marangattupilly", "Uzhavoor", "Veliyannoor"],
            ],
            95  => [
                "name"     => "Vaikom",
                "district" => "KTM",
                "lsgs"     => ["Vaikom", "Chempu", "Maravanthuruthu", "T V Puram", "Udayanapuram", "Vechoor", "Velloor", "Thalayazham", "Kallara", "Thalayolaparambu"],
            ],
            96  => [
                "name"     => "Ettumanoor",
                "district" => "KTM",
                "lsgs"     => ["Ettumanoor", "Aymanam", "Athirampuzha", "Thiruvarpu", "Arpookara", "Kumarakom", "Neendoor"],
            ],
            97  => [
                "name"     => "KOTTAYAM",
                "district" => "KTM",
                "lsgs"     => ["KOTTAYAM", "Panachikkad", "Vijayapuram"],
            ],
            98  => [
                "name"     => "Puthuppally",
                "district" => "KTM",
                "lsgs"     => ["Akallakunnam", "Ayarkunnam", "Kooroppada", "Manarcadu", "Meenadom", "Pampady", "Puthuppally", "Vakathanam"],
            ],
            99  => [
                "name"     => "Changanassery",
                "district" => "KTM",
                "lsgs"     => ["Changanassery", "Kurichy", "Madappally", "Paippad", "Thrickodithanam", "Vazhappally"],
            ],
            100 => [
                "name"     => "Kanjirappally",
                "district" => "KTM",
                "lsgs"     => ["Kanjirappally", "Chirakkadavu", "Pallickathode", "Kangazha", "Karukachal", "Manimala", "Nedumkunnam", "Vazhoor", "Vellavoor"],
            ],
            101 => [
                "name"     => "Poonjar",
                "district" => "KTM",
                "lsgs"     => ["Erattupetta", "Erumely", "Kootickal", "Mundakayam", "Parathodu", "Poonjar", "Thekkekara", "Teekoy", "Thidanad", "Koruthodu"],
            ],

            // ALAPPUZHA DISTRICT
            102 => [
                "name"     => "Aroor",
                "district" => "ALP",
                "lsgs"     => ["Arookutty", "Aroor", "Chennam Pallippuram", "Ezhupunna", "Kodamthuruthu", "Kuthiathode", "Panavally", "Perumbalam", "Thaikattussery", "Thuravoor"],
            ],
            103 => [
                "name"     => "Cherthala",
                "district" => "ALP",
                "lsgs"     => ["Cherthala", "Cherthala South", "Kanjikkuzhi", "Kadakkarappally", "Pattanakkad", "Muhamma", "Thanneermukkom", "Vayalar"],
            ],
            104 => [
                "name"     => "ALAPPUZHA",
                "district" => "ALP",
                "lsgs"     => ["ALAPPUZHA", "Aryad", "Mararikulam North", "Mannanchery", "Mararikulam South"],
            ],
            105 => [
                "name"     => "Ambalappuzha",
                "district" => "ALP",
                "lsgs"     => ["Ambalappuzha North", "Ambalappuzha South", "Punnapra North", "Punnapra South", "Purakkad"],
            ],
            106 => [
                "name"     => "Kuttanad",
                "district" => "ALP",
                "lsgs"     => ["Champakulam", "Edathua", "Kainakary", "Kavalam", "Muttar", "Nedumudi", "Neelamperoor", "Pulincunnoo", "Ramankary", "Thalavady", "Veliyanad", "Veeyapuram"],
            ],
            107 => [
                "name"     => "Haripad",
                "district" => "ALP",
                "lsgs"     => ["Haripad", "Arattupuzha", "Cheppad", "Cheruthana", "Chingoli", "Karuvatta", "Kumarapuram", "Pallippad", "Thrikkunnapuzha", "Muthukulam", "Karthikappally"],
            ],
            108 => [
                "name"     => "Kayamkulam",
                "district" => "ALP",
                "lsgs"     => ["Kayamkulam", "Devikulangara", "Kandalloor", "Krishnapuram", "Pathiyoor", "Bharanikkavu", "Chettikulangara"],
            ],
            109 => [
                "name"     => "Mavelikara",
                "district" => "ALP",
                "lsgs"     => ["Mavelikara", "Chennithala", "Thekkekara", "Thamarakkulam", "Vallikunnam", "Nooranad", "Palamel", "Thazhakara"],
            ],
            110 => [
                "name"     => "Chengannur",
                "district" => "ALP",
                "lsgs"     => ["Chengannur", "Ala", "Budhanoor", "Cheriyanad", "Mannar", "Mulakuzha", "Pandanad", "Puliyoor", "Thiruvanvandoor", "Venmony", "Chennithala Thriperumthura"],
            ],

            // PATHANAMTHITTA DISTRICT
            111 => [
                "name"     => "Thiruvalla",
                "district" => "PTA",
                "lsgs"     => ["Thiruvalla", "Kadapra", "Kaviyoor", "Kuttoor", "Nedumpuram", "Niranam", "Peringara", "Anicadu", "Kallooppara", "Mallapally", "Puramattam", "Kunnamthanam"],
            ],
            112 => [
                "name"     => "Ranni",
                "district" => "PTA",
                "lsgs"     => ["Kottangal", "Vechoochira", "Ranni", "Naranammoozhy", "Pazhavangadi", "Angadi", "Kottanad", "Ezhumattoor", "Cherukole", "Perunad", "Vadasserikkara", "Ayiroor"],
            ],
            113 => [
                "name"     => "Aranmula",
                "district" => "PTA",
                "lsgs"     => ["Pathanamthitta", "Aranmula", "Chenneerkkara", "Elanthur", "Kozhencherry", "Kulanada", "Mallapuzhassery", "Mezhuveli", "Naranganam", "Omalloor", "Eraviperoor", "Koipuram", "Thottapuzhassery"],
            ],
            114 => [
                "name"     => "Konni",
                "district" => "PTA",
                "lsgs"     => ["Konni", "Aruvappulam", "Chittar", "Enadimangalam", "Kalanjoor", "Malayalapuzha", "Mylapra", "Pramadom", "Seethathodu", "Vallicode", "Thannithodu"],
            ],
            115 => [
                "name"     => "Adoor",
                "district" => "PTA",
                "lsgs"     => ["Adoor", "Pandalam", "Ezhamkulam", "Kadampanad", "Kodumon", "Pallickal", "Pandalam Thekkekara", "Thumpamon"],
            ],

            // KOLLAM DISTRICT
            116 => [
                "name"     => "Karunagapally",
                "district" => "KLM",
                "lsgs"     => ["Karunagapally", "Alappad", "Clappana", "Kulasekharapuram", "Oachira", "Thazhava", "Thevalakkara", "Thodiyoor"],
            ],
            117 => [
                "name"     => "Chavara",
                "district" => "KLM",
                "lsgs"     => ["Chavara", "Neendakara", "Panmana", "Thekkumbhagom", "Thevalakkara"],
            ],
            118 => [
                "name"     => "Kunnathur",
                "district" => "KLM",
                "lsgs"     => ["Kunnathur", "East Kallada", "Mynagappally", "Poruvazhy", "Sasthamcotta", "Sooranad North", "Sooranad South", "West Kallada", "Munroethuruth", "Pavithreswaram"],
            ],
            119 => [
                "name"     => "Kottarakkara",
                "district" => "KLM",
                "lsgs"     => ["Kottarakkara", "Ezhukone", "Kareepra", "Kulakkada", "Mylom", "Neduvathur", "Ummannur", "Veliyam"],
            ],
            120 => [
                "name"     => "Pathanapuram",
                "district" => "KLM",
                "lsgs"     => ["Pathanapuram", "Pattazhy", "Pattazhy Vadakkekara", "Piravanthur", "Thalavoor", "Vilakkudy", "Melila", "Vettikkavala"],
            ],
            121 => [
                "name"     => "Punalur",
                "district" => "KLM",
                "lsgs"     => ["Punalur", "Anchal", "Aryankavu", "Edamulakkal", "Eroor", "Karavaloor", "Kulathupuzha", "Thenmala"],
            ],
            122 => [
                "name"     => "Chadayamangalam",
                "district" => "KLM",
                "lsgs"     => ["Alayamon", "Chadayamangalam", "Chithara", "Elamad", "Ittiva", "Kadakkal", "	Kummil", "Nilamel", "Velinallur"],
            ],
            123 => [
                "name"     => "Kundara",
                "district" => "KLM",
                "lsgs"     => ["Elampalloor", "Ezhome", "Kottamkara", "Kundara", "Nedumpana", "Perayam", "Perinad", "Thrikkovilvattom"],
            ],
            124 => [
                "name"     => "KOLLAM",
                "district" => "KLM",
                "lsgs"     => ["KOLLAM"],
            ],
            125 => [
                "name"     => "Eravipuram",
                "district" => "KLM",
                "lsgs"     => ["Mayyanad"],
            ],
            126 => [
                "name"     => "Chathannoor",
                "district" => "KLM",
                "lsgs"     => ["Adichanalloor", "Chathannoor", "Kalluvathukkal", "Chirakkara", "Poothakkulam", "Pooyappally."],
            ],

            // THIRUVANANTHAPURAM DISTRICT
            127 => [
                "name"     => "Varkala",
                "district" => "TVM",
                "lsgs"     => ["Varkala", "Edava", "Elakamon", "Madavoor", "Pallickal", "Chemmaruthy", "Navaikulam", "Vettoor"],
            ],
            128 => [
                "name"     => "Attingal",
                "district" => "TVM",
                "lsgs"     => ["Attingal", "Karavaram", "Kilimanoor", "Nagaroor", "Pazhayakunnummel", "Pulimath", "Vakkom", "Cherunniyoor", "Manamboor", "Ottoor"],
            ],
            129 => [
                "name"     => "Chirayinkeezhu",
                "district" => "TVM",
                "lsgs"     => ["Chirayinkeezhu", "Anchuthengu", "Kadakkavoor", "Kizhuvalam", "Mudakkal", "Azhoor", "Kadinamkulam", "Mangalapuram"],
            ],
            130 => [
                "name"     => "Nedumangad",
                "district" => "TVM",
                "lsgs"     => ["Nedumangad", "Andoorkonam", "Manickal", "Pothencode", "Vembayam", "Karakulam"],
            ],
            131 => [
                "name"     => "Vamanapuram",
                "district" => "TVM",
                "lsgs"     => ["Vamanapuram", "Anad", "Kallara", "Nanniyode", "Nellanad", "Pangode", "Pullampara", "Panavoor", "Peringamala"],
            ],
            132 => [
                "name"     => "Kazhakkoottam",
                "district" => "TVM",
                "lsgs"     => [],
            ],
            133 => [
                "name"     => "Vattiyoorkavu",
                "district" => "TVM",
                "lsgs"     => [],
            ],
            134 => [
                "name"     => "THIRUVANANTHAPURAM",
                "district" => "TVM",
                "lsgs"     => ["THIRUVANANTHAPURAM"],
            ],
            135 => [
                "name"     => "Nemom",
                "district" => "TVM",
                "lsgs"     => [],
            ],
            136 => [
                "name"     => "Aruvikkara",
                "district" => "TVM",
                "lsgs"     => ["Aruvikkara", "Aryanad", "Kuttichal", "Poovachal", "Tholicode", "Vithura", "Uzhamalakkal", "Vellanad"],
            ],
            137 => [
                "name"     => "Parassala",
                "district" => "TVM",
                "lsgs"     => ["Parassala", "Amboori", "Aryancode", "Kunnathukal", "Kollayil", "Perumkadavila", "Vellarada", "Kallikkad", "Ottasekharamangalam"],
            ],
            138 => [
                "name"     => "Kattakkada",
                "district" => "TVM",
                "lsgs"     => ["Kattakkada", "Malayinkeezhu", "Maranalloor", "Pallichal", "Vilappil", "Vilavoorkal"],
            ],
            139 => [
                "name"     => "Kovalam",
                "district" => "TVM",
                "lsgs"     => ["Balaramapuram", "Kalliyoor", "Kanjiramkulam", "Kottukal", "Poovar", "Venganoor"],
            ],
            140 => [
                "name"     => "Neyyattinkara",
                "district" => "TVM",
                "lsgs"     => ["Neyyattinkara", "Athiyannur", "Chenkal", "Karode", "Kulathoor", "Thirupuram"],
            ],
        ];

        foreach ($data as $city) {
            $dt  = District::where('code', $city['district'])->first()->id;
            $res = City::create(['name' => $city['name'], 'district_id' => $dt]);

            foreach ($city['lsgs'] as $station) {
                Station::create(['city_id' => $res->id, 'name' => $station, 'is_parent' => true]);
            }
        }
    }
}
