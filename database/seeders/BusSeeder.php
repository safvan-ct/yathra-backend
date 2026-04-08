<?php
namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $buses = [
            ['name' => 'KSRTC', 'number' => 'NF 15 1001'],
            ['name' => 'KSRTC', 'number' => 'NF 15 1002'],
            ['name' => 'KSRTC', 'number' => 'NF 15 1003'],
            ['name' => 'KSRTC', 'number' => 'NF 15 1004'],
        ];

        foreach ($buses as $bus) {
            Bus::create([
                'operator_id'     => 1, 'bus_name' => $bus['name'], 'bus_number' => $bus['number'],
                'bus_number_code' => trim(str_replace(' ', '', $bus['number'])),
            ]);
        }

        $buses = [
            ['name' => 'ROSARIO', 'number' => 'NF 50 2001'],
            ['name' => 'MADEENA', 'number' => 'NF 50 2002'],
            ['name' => 'FATHIMA', 'number' => 'NF 50 2003'],
            ['name' => 'MRL', 'number' => 'NF 50 2004'],
            ['name' => 'SHASTHA', 'number' => 'NF 50 2005'],
            ['name' => 'PUNYAALAN', 'number' => 'NF 50 2006'],
            ['name' => 'ALAMEEN', 'number' => 'NF 50 2007'],
            ['name' => 'SUNDARIKKUTTY', 'number' => 'NF 50 2014'],

            ['name' => 'MADEENA', 'number' => 'NF 50 2008'],
            ['name' => 'MADEENA', 'number' => 'NF 50 2009'],
            ['name' => 'NISSAN', 'number' => 'NF 50 2010'],
            ['name' => 'YATHRA', 'number' => 'NF 50 2011'],
            ['name' => 'ARANGODAN (Bungalow)', 'number' => 'NF 50 2012'],

            ['name' => 'PTB', 'number' => 'NF 50 2013'],
        ];

        foreach ($buses as $bus) {
            Bus::create([
                'operator_id'     => 2, 'bus_name' => $bus['name'], 'bus_number' => $bus['number'],
                'bus_number_code' => trim(str_replace(' ', '', $bus['number'])),
            ]);
        }
    }
}
