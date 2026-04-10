<?php
namespace Database\Seeders;

use App\Models\City;
use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'city'       => 'Azhikode',
                'local_body' => 'Kannur',
                'stations'   => ["Palliyammoola", "Pallikunnu", "Podikundu", "Kakkad", "Shadulippalli", "Kunnav", "Talap", "Kottali", "Thulicheri", "Chalad"],
            ],

            [
                'city'       => 'Elathur',
                'local_body' => 'Kozhikode',
                'stations'   => ["Elathur", "Puthur", "Eranjikkal", "Puthiyappa"],
            ],
            [
                'city'       => 'Kozhikode North',
                'local_body' => 'Kozhikode',
                'stations'   => ["Kunduparamba", "Malaparamba", "Vengery", "Paropady", "Chevarambalam", "Moozhikkal", "Mayanad", "Medical College", "Kudilthode", "Parayanchery", "Thiruthiyad", "Nadakkavu", "Thoppayil", "Karaparamba", "Athanikkal", "Edakkad", "Karuvissery", "Thadambattuthazham", "Maalikadavu", "Civil Station", "Vellimadukunnu", "Chelavoor", "Medical College South", "Chevayur", "Kottooli", "Moonnalingal", "Eranhipalam", "Vellayil", "Chakkorathkulam", "East Hill", "West Hill", "Puthiyangadi"],
            ],
            [
                'city'       => 'Kozhikode South',
                'local_body' => 'Kozhikode',
                'stations'   => ["Kovoor", "Puthiyara", "Pottammal", "Kuttiyilthazham", "Kinassery", "Azhchavattom", "Panniyankara", "Thiruvannur", "Payyanakkal", "Mukhador", "Chalappuram", "Valiyangadi", "Nellikode", "Kuthiravattom", "Kommery", "Pokkunnu", "Mankavu", "Kallayi", "Meenchanda", "Kappakkal", "Chakkumkadavu", "Kuttichira", "Palayam"],
            ],
            [
                'city'       => 'Beypore',
                'local_body' => 'Kozhikode',
                'stations'   => ["Areekad North", "Areekad", "Nallalam", "Kolathara", "Kundayithodu", "Cheruvannur East", "Cheruvannur West", "Beypore Port", "Beypore", "Marad", "Naduvattam", "Punjappadam", "Arakkinar", "Mathottam"],
            ],

            [
                'city'       => 'Ollur',
                'local_body' => 'Thrissur',
                'stations'   => ["Mullakkara", "Mannuthy", "Valarkavu", "Kuriachira", "Ancheri", "Patavarad", "Edakkunni", "Thaikkattussery", "Ollur", "Chiyyaram South", "Koorkenchery", "Kanimangalam", "Panamukku", "Nedupuzha"],
            ],
            [
                'city'       => 'Thrissur',
                'local_body' => 'Thrissur',
                'stations'   => ["Punkunnam", "Kuttankulangara", "Patturaikkal", "Viyyoor", "Peringavu", "Ramavarmapuram", "Kuttumukku", "Villadam", "Cherur", "Mukkattukara", "Gandhinagar", "Chembukkavu", "Kizhakkumpattukara", "Paravattani", "Ollukkara", "Nettissery", "Krishnapuram", "Kalathodu", "Nadathara", "Chelakkottukara", "Mission Quarters", "Kuttanellur", "Chiyyaram North", "Kannamkulangara", "Pallikulam", "Thekkinkadu", "Kottappuram", "Poothole", "Kokkala", "Vadookkara", "Karyattukara", "Chettupuzha", "Pullazhi", "Olarikara", "Elthuruth", "Laloor", "Aranattukara", "Kanattukara", "Ayyanthole", "Civil Station", "Puthurkkara"],
            ],

            [
                'city'       => 'Kochi',
                'local_body' => 'Kochi',
                'stations'   => ["Fort Kochi", "Kalvathy", "Earavely", "Karippalam", "Mattancherry", "Kochangadi", "Cheralayi", "Panayapilly", "Chakkamadom", "Karuvelippady", "Thoppumpady", "Tharebhagam", "Pullardesam", "Mundamveli", "Manassery", "Mulamkuzhi", "Chullikkal", "Nazareth", "Fort Kochi Veli", "Amaravathy"],
            ],
            [
                'city'       => 'Thrippunithura',
                'local_body' => 'Kochi',
                'stations'   => ["Kadebhagam", "Thazhuppu", "Edakochi North", "Edakochi South", "Perumbadappu", "Konam", "Palluruthy-Kacheripady", "Nambyapuram"],
            ],
            [
                'city'       => 'Ernakulam',
                'local_body' => 'Kochi',
                'stations'   => ["Island North", "Island South", "Vaduthala West", "Vaduthala East", "Elamakkara North", "Puthukkalavattam", "Kunnumpuram", "Konthuruthy", "Thevara", "Perumanur", "Ravipuram", "Ernakulam South", "Gandhi Nagar", "Kathrikadavu", "Kaloor South", "Ernakulam Central", "Ernakulam North", "Ayyappankavu", "Thrikkanarvattom", "Kaloor North", "Elamakkara South", "Pottakuzhy", "Pachalam", "Thattazham"],
            ],
            [
                'city'       => 'Thrikkakara',
                'local_body' => 'Kochi',
                'stations'   => ["Ponekkara", "Edappally", "Devankulangara", "Karukapally", "Mamangalam", "Padivattom", "Vennala", "Palarivattom", "Karanakkodam", "Thammanam", "Chakkaraparambu", "Chalikkavattam", "Ponnurunni East", "Vyttila", "Chambakkara", "Poonithura", "Vyttila Janata", "Ponnurunni", "Elamkulam", "Giri Nagar", "Panampilly Nagar", "Kadavanthra"],
            ],

            [
                'city'       => 'Chavara',
                'local_body' => 'Kollam',
                'stations'   => ["Maruthadi", "Sakthikulangara", "Meenathuchery", "Kavanad", "Vallikeezhu", "Alattukavu", "Kannimel"],
            ],
            [
                'city'       => 'Kollam',
                'local_body' => 'Kollam',
                'stations'   => ["Kureepuzha West", "Kureepuzha", "Neeravil", "Anchalumoodu", "Kadavoor", "Mathilil", "Thevally", "Vadakkumbhagam", "Uliyakovil East", "Kadappakada", "Koickal", "Kallumthazham", "Mundakkal", "Pattathanam", "Cantonment", "Udayamarthandapuram", "Thamarakkulam", "Pallithottam", "Port Kollam"],
            ],
            [
                'city'       => 'Eravipuram',
                'local_body' => 'Kollam',
                'stations'   => ["Asramam", "Uliyakovil", "Mangadu", "Arunoottimangalam", "Chathinamkulam", "Karicode", "College Division", "Palkulangara", "Ammannada", "Vadakkevila", "Pallimukku", "Ayathil", "Kilikollur", "Punthalathazham", "Palathara", "Manakkadu", "Kollurvila", "Kayyalakkal", "Valathungal", "Akkolil", "Thekkumbhagam", "Eravipuram", "Bharanikkavu", "Thekkevila"],
            ],

            [
                'city'       => 'Kazhakkoottam',
                'local_body' => 'Thiruvananthapuram',
                'stations'   => ["Kazhakootam", "Sainik School", "Chanthavila", "Kattaikonam", "Njandoorkonam", "Powdikonam", "Chenkottukonam", "Chempazhanthy", "Kariavattom", "Pangappara", "Sreekariyam", "Chellamangalam", "Mannanthala", "Nalanchira", "Edavacode", "Ulloor", "Medical College", "Karikkakam", "Kadakampally", "Anamugham", "Akkulam", "Cheruvaikkal", "Alathara", "Kuzhivila", "Poundkadavu", "Kulathoor", "Attipra", "Pallithura"],
            ],
            [
                'city'       => 'Vattiyoorkavu',
                'local_body' => 'Thiruvananthapuram',
                'stations'   => ["Pathirapalli", "Ambalamukku", "Kudappanakunnu", "Thuruthummoola", "Nettayam", "Kachani", "Vazhottukonam", "Kodunganoor", "Vattiyoorkavu", "Kanjirampara", "Peroorkada", "Kowdiar", "Kuravankonam", "Muttada", "Chettivilakam", "Kinavoor", "Pattom", "Kesavadasapuram", "Gowreeshapattom", "Kunnukuzhy", "Nanthancode", "Sasthamangalam", "Pangode", "Valiyavila", "Kannammoola"],
            ],
            [
                'city'       => 'Thiruvananthapuram',
                'local_body' => 'Thiruvananthapuram',
                'stations'   => ["Palayam", "Vazhuthacaud", "Jagathy", "Thycaud", "Valiyasala", "Arannoor", "Poonthura", "Puthenppalli", "Beemapalli", "Valiyathura", "Vallakkadavu", "Sreevaraham", "Manacaud", "Chalai", "Fort", "Perunthanni", "Sreekanteswaram", "Thampanoor", "Vanchiyoor", "Pettah", "Chackai", "Vettukadu"],
            ],
            [
                'city'       => 'Nemom',
                'local_body' => 'Thiruvananthapuram',
                'stations'   => ["Thirumala", "Thrikkannapuram", "Punnakkamugal", "Poojappura", "Mudavanmugal", "Estate", "Nemom", "Ponnumangalam", "Melamcode", "Pappanamcode", "Karamana", "Nedumcaud", "Kaladi", "Karumom", "Punchakkari", "Poonkulam", "Vellar", "Thiruvallam", "Ambalathara", "Attukal", "Kalippankulam", "Kamaleswaram"],
            ],
            [
                'city'       => 'Kovalam',
                'local_body' => 'Thiruvananthapuram',
                'stations'   => ["Venganoor", "Port", "Vizhinjam", "Harbour"],
            ],
            [
                'city'       => 'Kovalam',
                'local_body' => 'Venganoor',
                'stations'   => ["Kovalam"],
            ],

            [
                'city'       => null,
                'local_body' => 'Kottoppadam',
                'stations'   => ['Thiruvizhamkunnu', 'Edathanattukara', 'Ambalappara'],
            ],
            [
                'city'       => null,
                'local_body' => 'Alanallur',
                'stations'   => ['Koomanchira'],
            ],
            [
                'city'       => null,
                'local_body' => 'Kumaramputhur',
                'stations'   => ['Payyanadam'],
            ],
        ];

        foreach ($data as $item) {
            $parent = Station::where('name', $item['local_body'])->where('is_parent', true)->first();
            $cityId = $parent->city_id;

            if ($item['city']) {
                $cityId = City::where('name', $item['city'])->first()->id;
            }

            if ($parent) {
                foreach ($item['stations'] as $station) {
                    Station::create(['city_id' => $cityId, 'parent_id' => $parent->id, 'name' => $station]);
                }
            }
        }
    }
}
