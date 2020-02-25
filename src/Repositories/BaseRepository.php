<?php

namespace AmineAbri\BaseRepository\Repositories;

use AmineAbri\BaseRepository\Exceptions\ModelNotDeletedException;
use AmineAbri\BaseRepository\Interfaces\RepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * OrganisationRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $conditions): ?Model
    {
        if (!isset($conditions['where'])) {
            throw new InvalidArgumentException('The where condition is missing.');
        }

        $query = $this->model->newQuery();

        // Generic conditions
        if (isset($conditions['where']) && is_array($conditions['where'])) {
            foreach ($conditions['where'] as $conditionData) {
                $query->where($conditionData[0], $conditionData[1], $conditionData[2]);
            }
        }

        if (isset($conditions['whereHas']) && is_array($conditions['whereHas'])) {
            foreach ($conditions['whereHas'] as $conditionData) {
                $query->whereHas(key($conditionData), function ($query) use ($conditionData) {
                    $query->where(
                        $conditionData[key($conditionData)][0],
                        $conditionData[key($conditionData)][1],
                        $conditionData[key($conditionData)][2]
                    );
                });
            }
        }

        if (isset($conditions['with']) && is_array($conditions['with'])) {
            foreach ($conditions['with'] as $conditionData) {
                $query->with([key($conditionData) => function ($query) use ($conditionData) {
                    if (!empty($conditionData[key($conditionData)])) {
                        $query->where(
                            $conditionData[key($conditionData)][0],
                            $conditionData[key($conditionData)][1],
                            $conditionData[key($conditionData)][2]
                        );
                    }
                }]);
            }
        }

        // Apply ordering
        $query->orderBy($conditions['sortBy'] ?? 'id', $conditions['orderBy'] ?? 'asc');

        return $query->first();
    }

    /**
     * @inheritDoc
     */
    public function findAll(array $conditions = []): Collection
    {
        $query = $this->model->newQuery();

        if (isset($conditions['where']) && is_array($conditions['where'])) {
            foreach ($conditions['where'] as $conditionData) {
                $query->where($conditionData[0], $conditionData[1], $conditionData[2]);
            }
        }

        if (isset($conditions['whereHas']) && is_array($conditions['whereHas'])) {
            foreach ($conditions['whereHas'] as $conditionData) {
                $query->whereHas(key($conditionData), function ($query) use ($conditionData) {
                    $query->where(
                        $conditionData[key($conditionData)][0],
                        $conditionData[key($conditionData)][1],
                        $conditionData[key($conditionData)][2]
                    );
                });
            }
        }

        if (isset($conditions['with']) && is_array($conditions['with'])) {
            foreach ($conditions['with'] as $conditionData) {
                $query->with([key($conditionData) => function ($query) use ($conditionData) {
                    if (!empty($conditionData[key($conditionData)])) {
                        $query->where(
                            $conditionData[key($conditionData)][0],
                            $conditionData[key($conditionData)][1],
                            $conditionData[key($conditionData)][2]
                        );
                    }
                }]);
            }
        }

        // Apply ordering
        $query->orderBy($conditions['sortBy'] ?? 'id', $conditions['orderBy'] ?? 'asc');
        return $query->take($conditions['limit'] ?? 50)->offset($conditions['offset'] ?? 0)->get();
    }

    /**
     * @inheritDoc
     */
    public function countAll(array $conditions = []): int
    {
        $query = $this->model->newQuery();

        if (isset($conditions['where']) && is_array($conditions['where'])) {
            foreach ($conditions['where'] as $conditionData) {
                $query->where($conditionData[0], $conditionData[1], $conditionData[2]);
            }
        }

        return $query->count();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(Model $model): bool
    {
        if (!$model->delete()) {
            throw new ModelNotDeletedException();
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    abstract public function create(array $data): Model;

    /**
     * @inheritDoc
     */
    abstract public function update(Model $model, array $data): Model;
}
