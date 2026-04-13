<?php

namespace App\Repositories;

use App\Models\Conveyance;
use App\Models\User;

interface ConveyanceRepositoryInterface {
    public function createForDate( User $user, string $date, array $rows ): Conveyance;

    public function update( Conveyance $conveyance, string $date, array $rows ): Conveyance;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Conveyance>
     */
    public function all( User $user );

    public function findByDate( User $user, string $date ): ?Conveyance;

    public function delete( Conveyance $conveyance ): void;
}
