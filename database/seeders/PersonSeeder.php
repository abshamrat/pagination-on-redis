<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
  
use App\Models\Person;

class PersonSeeder extends Seeder
{
    /**
     * Get row data.
     *
     * @return array
     */
    private function getRowData($data): array {
        return [
            "email" => $data['1'],
            "full_name" => $data['2'],
            "birthday" => $data['3'],
            "phone" => $data['4'],
            "ip" => $data['5'],
            "country" => $data['6']
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->comment('Seeding test data from csv...');
        try {
            Person::truncate();

            $testDataCSV = fopen(base_path("database/data/test-data.csv"), "r");    
            $firstLine = true;
            $bulkData = array();
            while (($data = fgetcsv($testDataCSV, 2000, ",")) !== FALSE) {
                if (!$firstLine) {
                    Person::insert($this->getRowData($data));
                    // array_push($bulkData, $this->getRowData($data));
                }
                $firstLine = false;
            }
            fclose($testDataCSV);
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}
