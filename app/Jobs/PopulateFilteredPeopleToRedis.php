<?php

namespace App\Jobs;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class PopulateFilteredPeopleToRedis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $people;
    public $filteredResultKey;
    public $totalFilteredRecordKey;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($people, $filteredResultKey, $totalFilteredRecordKey)
    {
        $this->people = $people;
        $this->filteredResultKey = $filteredResultKey;
        $this->totalFilteredRecordKey = $totalFilteredRecordKey;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = array();
        $key = $this->filteredResultKey;
        foreach($this->people as $person) {
            $data[json_encode($person)] = $person->id;
        }

        Redis::set($this->totalFilteredRecordKey, count($this->people));
        Redis::expire($this->totalFilteredRecordKey, 60);

        Redis::zadd($key, $data);
        Redis::expire($key, 60);
    }
}
