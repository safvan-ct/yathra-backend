<?php

if (! function_exists('generateUniqueCode')) {
    function generateUniqueCode(string $name, string $modelClass, string $column = 'code'): string
    {
        $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', $name));

        // Overrides
        $overrides = getKeralaDistricts();
        if (isset($overrides[$clean])) {
            return $overrides[$clean];
        }

        $clean = strtoupper(preg_replace('/[^A-Za-z]/', '', $name));
        $clean = preg_replace('/(.)\1+/', '$1', $clean); // Remove duplicate letters

        $chars = str_split($clean);
        $first = array_shift($chars);

        $rest = array_filter($chars, function ($c) {
            return ! in_array($c, ['A', 'E', 'I', 'O', 'U']);
        });

        $code = $first;

        foreach ($rest as $char) {
            if (! str_contains($code, $char)) {
                $code .= $char;
            }

            if (strlen($code) === 3) {
                break;
            }
        }

        if (strlen($code) < 3) {
            foreach ($chars as $char) {
                if (! str_contains($code, $char)) {
                    $code .= $char;
                }
                if (strlen($code) === 3) {
                    break;
                }
            }
        }

        $code = str_pad($code, 3, 'X');

        $reservedCodes = array_values($overrides);
        $exist         = $modelClass::where($column, $code)->exists();

        if (in_array($code, $reservedCodes) || $exist) {
            $base     = $code;
            $alphabet = range('A', 'Z');
            $i        = 0;

            do {
                if ($i < 26) {
                    $code = substr($base, 0, 2) . $alphabet[$i];
                } else {
                    $code = substr($base, 0, 1) . $alphabet[intval($i / 26) % 26] . $alphabet[$i % 26];
                }
                $i++;
            } while (in_array($code, $reservedCodes) || $exist);
        }

        return $code;
    }
}

function getKeralaDistricts(): array
{
    return [
        'THIRUVANANTHAPURAM' => 'TVM',
        'KOLLAM'             => 'KLM',
        'PATHANAMTHITTA'     => 'PTA',
        'ALAPPUZHA'          => 'ALP',
        'KOTTAYAM'           => 'KTM',
        'IDUKKI'             => 'IDK',
        'ERNAKULAM'          => 'EKM',
        'THRISSUR'           => 'TCR',
        'PALAKKAD'           => 'PKD',
        'MALAPPURAM'         => 'MLP',
        'KOZHIKODE'          => 'CLT',
        'WAYANAD'            => 'WYD',
        'KANNUR'             => 'KNR',
        'KASARAGOD'          => 'KSD',
    ];
}
