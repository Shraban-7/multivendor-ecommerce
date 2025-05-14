<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\Country;
use App\Models\ZipCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountryStateZipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/country_states.json'));
        $data = json_decode($json, true);

        foreach ($data['countries'] as $countryData) {
            $country = Country::create([
                'name' => $countryData['name'],
                'country_code' => $countryData['country_code'] ?? null,
                'phone_code' => $countryData['phone_code'] ?? null,
                'currency' => $countryData['currency'] ?? null,
                'currency_symbol' => $countryData['currency_symbol'] ?? null,
            ]);

            foreach ($countryData['states'] ?? [] as $stateData) {
                $state = State::create([
                    'country_id' => $country->id,
                    'name' => $stateData['name'],
                ]);

                foreach ($stateData['zip_codes'] ?? [] as $zip) {
                    ZipCode::create([
                        'state_id' => $state->id,
                        'zip_code' => $zip,
                    ]);
                }
            }
        }
    }
}
