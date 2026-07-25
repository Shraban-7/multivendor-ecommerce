<?php

namespace App\Domain\Product\Repositories;

use App\Domain\Product\Models\Option;
use App\Domain\Product\Models\OptionValue;
use App\Domain\Product\Repositories\Contracts\OptionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentOptionRepository implements OptionRepositoryInterface
{
    public function findById(int $id): ?Option
    {
        return Option::find($id);
    }

    public function getAll(): Collection
    {
        return Option::with('option_values')->get();
    }

    public function store(array $data): Option
    {
        return Option::create($data);
    }

    public function update(Option $option, array $data): bool
    {
        return $option->update($data);
    }

    public function delete(Option $option): bool
    {
        return $option->delete();
    }

    public function getValuesForOption(int $optionId): Collection
    {
        return OptionValue::where('option_id', $optionId)->get();
    }

    public function storeValue(array $data): OptionValue
    {
        return OptionValue::create($data);
    }

    public function deleteValue(OptionValue $value): bool
    {
        return $value->delete();
    }
}
