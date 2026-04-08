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
            "TVM" => [
                "Aruvikkara"         => [],
                "Attingal"           => [],
                "Chirayinkeezhu"     => [],
                "Kattakkada"         => [],
                "Kazhakkoottam"      => [],
                "Kovalam"            => [],
                "Nedumangad"         => [],
                "Nemom"              => [],
                "Neyyattinkara"      => [],
                "Parassala"          => [],
                "Thiruvananthapuram" => [],
                "Vamanapuram"        => [],
                "Varkala"            => [],
                "Vattiyoorkavu"      => [],
            ],
            "KLM" => [
                "Chadayamangalam" => [],
                "Chathannoor"     => [],
                "Chavara"         => [],
                "Eravipuram"      => [],
                "Karunagapally"   => [],
                "Kollam"          => [],
                "Kottarakkara"    => [],
                "Kundara"         => [],
                "Kunnathur"       => [],
                "Pathanapuram"    => [],
                "Punalur"         => [],
            ],
            "PTA" => [
                "Adoor"      => [],
                "Aranmula"   => [],
                "Konni"      => [],
                "Ranni"      => [],
                "Thiruvalla" => [],
            ],
            "ALP" => [
                "Alappuzha"    => [],
                "Ambalappuzha" => [],
                "Aroor"        => [],
                "Chengannur"   => [],
                "Cherthala"    => [],
                "Haripad"      => [],
                "Kayamkulam"   => [],
                "Kuttanad"     => [],
                "Mavelikara"   => [],
            ],
            "KTM" => [
                "Changanassery" => [],
                "Ettumanoor"    => [],
                "Kaduthuruthy"  => [],
                "Kanjirappally" => [],
                "Kottayam"      => [],
                "Pala"          => [],
                "Poonjar"       => [],
                "Puthuppally"   => [],
                "Vaikom"        => [],
            ],
            "IDK" => [
                "Devikulam"    => [],
                "Idukki"       => [],
                "Peerumade"    => [],
                "Thodupuzha"   => [],
                "Udumbanchola" => [],
            ],
            "EKM" => [
                "Aluva"          => [],
                "Angamaly"       => [],
                "Ernakulam"      => [],
                "Kalamassery"    => [],
                "Kochi"          => [],
                "Kothamangalam"  => [],
                "Kunnathunad"    => [],
                "Muvattupuzha"   => [],
                "Paravur"        => [],
                "Perumbavoor"    => [],
                "Piravom"        => [],
                "Thrikkakara"    => [],
                "Thrippunithura" => [],
                "Vypin"          => [],
            ],
            "TCR" => [
                "Chalakudy"     => [],
                "Chelakkara"    => [],
                "Guruvayur"     => [],
                "Irinjalakuda"  => [],
                "Kaipamangalam" => [],
                "Kodungallur"   => [],
                "Kunnamkulam"   => [],
                "Manalur"       => [],
                "Nattika"       => [],
                "Ollur"         => [],
                "Puthukkad"     => [],
                "Thrissur"      => [],
                "Wadakkanchery" => [],
            ],
            "PKD" => [
                "Alathur"    => [],
                "Chittur"    => [],
                "Kongad"     => [],
                "Malampuzha" => [],
                "Mannarkkad" => ["Agali", "Alanallur", "Kottoppadam", "Kumaramputhur", "Thenkara", "Pudur", "Sholayur"],
                "Nenmara"    => [],
                "Ottapalam"  => [],
                "Palakkad"   => [],
                "Pattambi"   => [],
                "Shornur"    => [],
                "Tarur"      => [],
                "Thrithala"  => [],
            ],
            "MLP" => [
                "Eranad"         => [],
                "Kondotty"       => [],
                "Kottakkal"      => [],
                "Malappuram"     => [],
                "Manjeri"        => [],
                "Mankada"        => [],
                "Nilambur"       => [],
                "Perinthalmanna" => [],
                "Ponnani"        => [],
                "Tanur"          => [],
                "Thavanur"       => [],
                "Tirur"          => [],
                "Tirurangadi"    => [],
                "Vallikkunnu"    => [],
                "Vengara"        => [],
                "Wandoor"        => [],
            ],
            "CLT" => [
                "Balussery"       => [],
                "Beypore"         => [],
                "Elathur"         => [],
                "Koduvally"       => [],
                "Koyilandy"       => [],
                "Kozhikode North" => [],
                "Kozhikode South" => [],
                "Kunnamangalam"   => [],
                "Kuttiady"        => [],
                "Nadapuram"       => [],
                "Perambra"        => [],
                "Thiruvambady"    => [],
                "Vatakara"        => [],
            ],
            "WYD" => [
                "Kalpetta"        => [],
                "Mananthavady"    => [],
                "Sulthan Bathery" => [],
            ],
            "KNR" => [
                "Azhikode"     => [],
                "Dharmadom"    => [],
                "Irikkur"      => [],
                "Kalliasseri"  => [],
                "Kannur"       => [],
                "Kuthuparamba" => [],
                "Mattanur"     => [],
                "Payyanur"     => [],
                "Peravoor"     => [],
                "Taliparamba"  => [],
                "Thalassery"   => [],
            ],
            "KSD" => [
                "Kanhangad"    => [],
                "KASARAGOD"    => ["Badiyadka", "Bellur", "Chengala", "Karadka", "Kumbadaje", "Madhur", "Mogral Puthur"],
                "Manjeshwaram" => ["Enmakaje", "Kumbla", "Mangalpady", "Meenja", "Paivalike", "Puthige", "Vorkady"],
                "Trikaripur"   => [],
                "Udma"         => [],
            ],
        ];

        foreach ($data as $key => $cities) {
            $dt = District::where('code', $key)->first()->id;

            foreach ($cities as $city => $stations) {
                $cityCode = generateUniqueCode($city, \App\Models\City::class);

                if ($cityCode) {
                    $res = City::create(['name' => $city, 'code' => $cityCode, 'district_id' => $dt]);
                    Station::create(['city_id' => $res->id, 'name' => $city, 'code' => $cityCode, 'is_parent' => true]);

                    foreach ($stations as $station) {
                        $code = generateUniqueCode($station, \App\Models\Station::class);

                        if ($code) {
                            Station::create(['city_id' => $res->id, 'name' => $station, 'code' => $code, 'is_parent' => true]);
                        }
                    }
                }
            }
        }
    }
}
