<?php

namespace App\Repositories;

use App\Models\Conveyance;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ConveyanceRepositoryInterface {
    public function createForDate( User $user, string $date, array $rows ): Conveyance;

    public function update( Conveyance $conveyance, string $date, array $rows ): Conveyance;

    public function paginate( User $user, array $filters = [], int $perPage = 10 ): LengthAwarePaginator;

    public function findByDate( User $user, string $date ): ?Conveyance;

    public function delete( Conveyance $conveyance ): void;
}
