<?php

namespace App\Domain\Product\Repositories\Contracts;

use App\Domain\Product\Models\Option;
use App\Domain\Product\Models\OptionValue;
use Illuminate\Database\Eloquent\Collection;

interface OptionRepositoryInterface
{
    public function findById(int $id): ?Option;

    public function getAll(): Collection;

    public function store(array $data): Option;

    public function update(Option $option, array $data): bool;

    public function delete(Option $option): bool;

    public function getValuesForOption(int $optionId): Collection;

    public function storeValue(array $data): OptionValue;

    public function deleteValue(OptionValue $value): bool;
}
