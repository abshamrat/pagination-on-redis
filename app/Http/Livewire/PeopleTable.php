<?php

namespace App\Http\Livewire;

use App\Models\Person;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberFilter;

class PeopleTable extends DataTableComponent
{

    protected $model = Person::class;
    protected $previousFilterKey = "people:previousFilterKey";
    protected $totalFilteredRecordKey = "people:totalFilteredRecordKey";

    private function filterRecordKey() {
        $month = $this->getAppliedFilterWithValue('month');
        $year = $this->getAppliedFilterWithValue('year');
        return "$month:$year";
    }

    private function isAnyFilterApplied() {
        return $this->filterRecordKey() != ":";
    }

    public function isFilterKeyExist() {
        $key = $this->filterRecordKey();
        $previousKey = Redis::get($this->previousFilterKey);
        $isMatched = $previousKey == $key;

        if (!$isMatched) {
            Redis::delete($key);
            Redis::delete($this->totalFilteredRecordKey);
            Redis::delete($this->previousFilterKey);

            Redis::set($this->previousFilterKey, $key);
            Redis::expire($this->previousFilterKey, 60);
        }
        return $isMatched;
    }

    public function getRecordsFromRedis() {
        $key = $this->filterRecordKey();
        $start = ($this->page - 1) * $this->getPerPage();
        $end = $start + $this->getPerPage();

        $rows = Redis::zrange($key, $start, $end);
            
        for($i=0; $i < count($rows); $i++) {
            $rows[$i] = json_decode($rows[$i], true);
        }
        return Person::hydrate($rows);
    }

    public function populateRecordsToRedis($records) {
        $data = array();
        $key = $this->filterRecordKey();
        foreach($records as $row) {
            $data[json_encode($row)] = $row->id;
        }

        Redis::set($this->totalFilteredRecordKey, count($records));
        Redis::expire($this->totalFilteredRecordKey, 60);

        Redis::zadd($key, $data);
        Redis::expire($key, 60);
    }

    public function getPaginator($records) {
        $total = Redis::get($this->totalFilteredRecordKey);
        return new LengthAwarePaginator(
            $records,
            $total,
            $this->getPerPage(),
            $this->page,
            [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            ]
        );
    }

    public function getExecutedQueryResult($startPaginationStatus=true, $endPaginationStatus=true) {
        $this->setPaginationStatus($startPaginationStatus);
        $this->baseQuery();
        $results = $this->executeQuery();
        $this->setPaginationStatus($endPaginationStatus);
        return $results;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            // ->setDebugEnabled()
            ->setPerPageAccepted([20])
            ->setSearchStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),
            Column::make('EMAIL', 'email'),
            Column::make('FULL NAME', 'full_name'),
            Column::make('COUNTRY', 'country'),
            Column::make('BIRTHDAY', 'birthday'),
            Column::make('PHONE', 'phone'),
            Column::make('IP', 'ip'),
        ];
    }

    public function getRows(){
        if (!$this->isAnyFilterApplied()) {
            return $this->getExecutedQueryResult();
        }

        $records = null;
        if ($this->isFilterKeyExist()) {
            $records = $this->getRecordsFromRedis();
        } else {
            $records=$this->getExecutedQueryResult(false);
            $this->populateRecordsToRedis($records);
            $records = $records->splice(0, 20);
        }

        return $this->getPaginator($records);
    }
    public function builder():Builder
    {
        return Person::query();
    }

    public function filters(): array
    {
        return [
            NumberFilter::make('Month')
                ->config([
                    'min' => 1,
                    'max' => 12,
                ])
                ->filter(function($builder, string $value) {
                    $builder->whereMonth('birthday', '=', $value);
                }),
            NumberFilter::make('Year')
                ->config([
                    'min' => 1900,
                    'max' => 2022,
                ])
                ->filter(function($builder, string $value) {
                    $builder->whereYear('birthday', '=', $value);
                }),
        ];
    }
}