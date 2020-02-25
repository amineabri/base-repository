<?php

namespace AmineAbri\BaseRepository\Interfaces;

use AmineAbri\BaseRepository\Exceptions\ModelNotDeletedException;
use AmineAbri\BaseRepository\Exceptions\ModelNotUpdatedException;
use AmineAbri\BaseRepository\Exceptions\ModelNotCreatedException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

interface RepositoryInterface
{
    /**
     * Find a record by $conditions.
     *
     * @param array $conditions
     *
     * @return Model|null
     *
     * @throws InvalidArgumentException
     */
    public function findBy(array $conditions): ?Model;

    /**
     * Find all records by provided conditions.
     *
     * @param array $conditions
     *
     * @return Collection
     */
    public function findAll(array $conditions = []): Collection;

    /**
     * Count all records by provided conditions.
     *
     * @param array $conditions
     *
     * @return int
     */
    public function countAll(array $conditions = []): int;

    /**
     * Delete a record.
     *
     * @param Model $model
     *
     * @return bool
     *
     * @throws ModelNotDeletedException
     */
    public function delete(Model $model): bool;

    /**
     * Create a new record.
     *
     * @param array $data
     *
     * @return Model
     *
     * @throws ModelNotCreatedException
     */
    public function create(array $data): Model;

    /**
     * Update the existing record.
     *
     * @param Model $model
     * @param array $data
     *
     * @return Model
     *
     * @throws ModelNotUpdatedException
     */
    public function update(Model $model, array $data): Model;
}
