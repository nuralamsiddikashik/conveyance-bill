<?php

namespace App\Repositories;

use App\Models\Conveyance;

interface ConveyanceRepositoryInterface {
    public function createForDate( string $date, array $rows ): Conveyance;

    public function update( Conveyance $conveyance, string $date, array $rows ): Conveyance;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Conveyance>
     */
    public function all();

    public function findByDate( string $date ): ?Conveyance;

    public function delete( Conveyance $conveyance ): void;
}
