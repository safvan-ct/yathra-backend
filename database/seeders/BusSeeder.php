<?php
namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $buses = [
            ['name' => 'Arafa', 'number' => 'NF 50 2001'],
            ['name' => 'Ambadath', 'number' => 'NF 50 2002'],
            ['name' => 'Aysha', 'number' => 'NF 50 2003'],
            ['name' => 'Aysha Kurikkal', 'number' => 'NF 50 2004'],
            ['name' => 'Bai', 'number' => 'NF 50 2005'],
            ['name' => 'Fathima', 'number' => 'NF 50 2006'],
            ['name' => 'Fifa', 'number' => 'NF 50 2007'],
            ['name' => 'Kairali', 'number' => 'NF 50 2008'],
            ['name' => 'Kalabham', 'number' => 'NF 50 2009'],
            ['name' => 'Madeena', 'number' => 'NF 50 2010'],
            ['name' => 'Vengalath', 'number' => 'NF 50 2011'],
            ['name' => 'Vengalath (Bungalow)', 'number' => 'NF 50 2012'],

            ['name' => 'Ambadath', 'number' => 'NF 50 2013'],
            ['name' => 'Madeena', 'number' => 'NF 50 2014'],
        ];

        foreach ($buses as $bus) {
            Bus::create([
                'operator_id'     => 2,
                'bus_name'        => $bus['name'],
                'bus_number'      => $bus['number'],
                'bus_number_code' => trim(str_replace(' ', '', $bus['number'])),
            ]);
        }
    }
}
