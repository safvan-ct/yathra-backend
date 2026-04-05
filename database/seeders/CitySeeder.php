<?php
namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\State;
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

        $rto_locations = [
            // Thiruvananthapuram
            ['district_code' => 'TVM', 'name' => 'Thiruvananthapuram City', 'code' => 'KL-01'],
            ['district_code' => 'TVM', 'name' => 'KSRTC', 'code' => 'KL-15'],
            ['district_code' => 'TVM', 'name' => 'Attingal', 'code' => 'KL-16'],
            ['district_code' => 'TVM', 'name' => 'Parassala', 'code' => 'KL-19'],
            ['district_code' => 'TVM', 'name' => 'Neyyattinkara', 'code' => 'KL-20'],
            ['district_code' => 'TVM', 'name' => 'Nedumangad', 'code' => 'KL-21'],
            ['district_code' => 'TVM', 'name' => 'Kazhakkoottam', 'code' => 'KL-22'],
            ['district_code' => 'TVM', 'name' => 'Kattakada', 'code' => 'KL-74'],
            ['district_code' => 'TVM', 'name' => 'Varkala', 'code' => 'KL-75'],

            // Kollam
            ['district_code' => 'KLM', 'name' => 'Kollam', 'code' => 'KL-02'],
            ['district_code' => 'KLM', 'name' => 'Karunagappally', 'code' => 'KL-23'],
            ['district_code' => 'KLM', 'name' => 'Kottarakkara', 'code' => 'KL-24'],
            ['district_code' => 'KLM', 'name' => 'Punalur', 'code' => 'KL-25'],
            ['district_code' => 'KLM', 'name' => 'Kunnathur', 'code' => 'KL-61'],
            ['district_code' => 'KLM', 'name' => 'Pathanapuram', 'code' => 'KL-80'],
            ['district_code' => 'KLM', 'name' => 'Chadayamangalam', 'code' => 'KL-81'],
            ['district_code' => 'KLM', 'name' => 'Chathannoor', 'code' => 'KL-82'],

            // Pathanamthitta
            ['district_code' => 'PTA', 'name' => 'Pathanamthitta', 'code' => 'KL-03'],
            ['district_code' => 'PTA', 'name' => 'Adoor', 'code' => 'KL-26'],
            ['district_code' => 'PTA', 'name' => 'Thiruvalla', 'code' => 'KL-27'],
            ['district_code' => 'PTA', 'name' => 'Mallappally (Old)', 'code' => 'KL-28'],
            ['district_code' => 'PTA', 'name' => 'Ranni', 'code' => 'KL-62'],
            ['district_code' => 'PTA', 'name' => 'Konni', 'code' => 'KL-83'],
            ['district_code' => 'PTA', 'name' => 'Mallappally', 'code' => 'KL-89'],

            // Alappuzha
            ['district_code' => 'ALP', 'name' => 'Alappuzha', 'code' => 'KL-04'],
            ['district_code' => 'ALP', 'name' => 'Kayamkulam', 'code' => 'KL-29'],
            ['district_code' => 'ALP', 'name' => 'Chengannur', 'code' => 'KL-30'],
            ['district_code' => 'ALP', 'name' => 'Mavelikkara', 'code' => 'KL-31'],
            ['district_code' => 'ALP', 'name' => 'Cherthala', 'code' => 'KL-32'],
            ['district_code' => 'ALP', 'name' => 'Kuttanad', 'code' => 'KL-66'],

            // Kottayam
            ['district_code' => 'KTM', 'name' => 'Kottayam', 'code' => 'KL-05'],
            ['district_code' => 'KTM', 'name' => 'Changanassery', 'code' => 'KL-33'],
            ['district_code' => 'KTM', 'name' => 'Kanjirappally', 'code' => 'KL-34'],
            ['district_code' => 'KTM', 'name' => 'Pala', 'code' => 'KL-35'],
            ['district_code' => 'KTM', 'name' => 'Vaikom', 'code' => 'KL-36'],
            ['district_code' => 'KTM', 'name' => 'Uzhavoor', 'code' => 'KL-67'],

            // Idukki
            ['district_code' => 'IDK', 'name' => 'Idukki', 'code' => 'KL-06'],
            ['district_code' => 'IDK', 'name' => 'Vandiperiyar', 'code' => 'KL-37'],
            ['district_code' => 'IDK', 'name' => 'Thodupuzha', 'code' => 'KL-38'],
            ['district_code' => 'IDK', 'name' => 'Devikulam', 'code' => 'KL-68'],
            ['district_code' => 'IDK', 'name' => 'Udumbanchola', 'code' => 'KL-69'],

            // Ernakulam
            ['district_code' => 'EKM', 'name' => 'Ernakulam', 'code' => 'KL-07'],
            ['district_code' => 'EKM', 'name' => 'Muvattupuzha', 'code' => 'KL-17'],
            ['district_code' => 'EKM', 'name' => 'Thripunithura', 'code' => 'KL-39'],
            ['district_code' => 'EKM', 'name' => 'Perumbavoor', 'code' => 'KL-40'],
            ['district_code' => 'EKM', 'name' => 'Aluva', 'code' => 'KL-41'],
            ['district_code' => 'EKM', 'name' => 'North Paravur', 'code' => 'KL-42'],
            ['district_code' => 'EKM', 'name' => 'Mattancherry', 'code' => 'KL-43'],
            ['district_code' => 'EKM', 'name' => 'Kothamangalam', 'code' => 'KL-44'],
            ['district_code' => 'EKM', 'name' => 'Angamaly', 'code' => 'KL-63'],
            ['district_code' => 'EKM', 'name' => 'Kunnathunad', 'code' => 'KL-87'],

            // Thrissur
            ['district_code' => 'TCR', 'name' => 'Thrissur', 'code' => 'KL-08'],
            ['district_code' => 'TCR', 'name' => 'Irinjalakuda', 'code' => 'KL-45'],
            ['district_code' => 'TCR', 'name' => 'Guruvayur', 'code' => 'KL-46'],
            ['district_code' => 'TCR', 'name' => 'Kodungallur', 'code' => 'KL-47'],
            ['district_code' => 'TCR', 'name' => 'Wadakkanchery', 'code' => 'KL-48'],
            ['district_code' => 'TCR', 'name' => 'Chalakudy', 'code' => 'KL-64'],
            ['district_code' => 'TCR', 'name' => 'Kechery', 'code' => 'KL-88'],

            // Palakkad
            ['district_code' => 'PKD', 'name' => 'Palakkad', 'code' => 'KL-09'],
            ['district_code' => 'PKD', 'name' => 'Alathur', 'code' => 'KL-49'],
            ['district_code' => 'PKD', 'name' => 'Mannarkkad', 'code' => 'KL-50'],
            ['district_code' => 'PKD', 'name' => 'Ottapalam', 'code' => 'KL-51'],
            ['district_code' => 'PKD', 'name' => 'Pattambi', 'code' => 'KL-52'],
            ['district_code' => 'PKD', 'name' => 'Chittur', 'code' => 'KL-70'],

            // Malappuram
            ['district_code' => 'MLP', 'name' => 'Malappuram', 'code' => 'KL-10'],
            ['district_code' => 'MLP', 'name' => 'Perinthalmanna', 'code' => 'KL-53'],
            ['district_code' => 'MLP', 'name' => 'Ponnani', 'code' => 'KL-54'],
            ['district_code' => 'MLP', 'name' => 'Tirur', 'code' => 'KL-55'],
            ['district_code' => 'MLP', 'name' => 'Tirurangadi', 'code' => 'KL-65'],
            ['district_code' => 'MLP', 'name' => 'Nilambur', 'code' => 'KL-71'],
            ['district_code' => 'MLP', 'name' => 'Kondotty', 'code' => 'KL-84'],
            ['district_code' => 'MLP', 'name' => 'Valanchery', 'code' => 'KL-85'],

            // Kozhikode
            ['district_code' => 'CLT', 'name' => 'Kozhikode', 'code' => 'KL-11'],
            ['district_code' => 'CLT', 'name' => 'Vadakara', 'code' => 'KL-18'],
            ['district_code' => 'CLT', 'name' => 'Koyilandy', 'code' => 'KL-56'],
            ['district_code' => 'CLT', 'name' => 'Koduvally', 'code' => 'KL-57'],
            ['district_code' => 'CLT', 'name' => 'Nanminda', 'code' => 'KL-76'],
            ['district_code' => 'CLT', 'name' => 'Perambra', 'code' => 'KL-77'],

            // Wayanad
            ['district_code' => 'WYD', 'name' => 'Kalpetta', 'code' => 'KL-12'],
            ['district_code' => 'WYD', 'name' => 'Mananthavady', 'code' => 'KL-72'],
            ['district_code' => 'WYD', 'name' => 'Sulthan Bathery', 'code' => 'KL-73'],

            // Kannur
            ['district_code' => 'KNR', 'name' => 'Kannur', 'code' => 'KL-13'],
            ['district_code' => 'KNR', 'name' => 'Thaliparamba', 'code' => 'KL-58'],
            ['district_code' => 'KNR', 'name' => 'Thalassery', 'code' => 'KL-59'],
            ['district_code' => 'KNR', 'name' => 'Iritty', 'code' => 'KL-78'],
            ['district_code' => 'KNR', 'name' => 'Payyannur', 'code' => 'KL-86'],
            ['district_code' => 'KNR', 'name' => 'Alakode', 'code' => 'KL-79'],

            // Kasaragod
            ['district_code' => 'KSD', 'name' => 'Kasaragod', 'code' => 'KL-14'],
            ['district_code' => 'KSD', 'name' => 'Kanhangad', 'code' => 'KL-60'],
        ];

        foreach ($rto_locations as $rto_location) {
            City::create([
                'name'        => $rto_location['name'],
                'code'        => $rto_location['code'],
                'district_id' => District::where('code', $rto_location['district_code'])->first()->id,
            ]);
        }
    }
}
