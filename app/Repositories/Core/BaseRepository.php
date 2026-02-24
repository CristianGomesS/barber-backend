<?php
namespace App\Repositories\Core;

use App\Repositories\Contracts\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface
{
    protected $entity;
    public function __construct(object $entity)
    {
        $this->entity = $entity;
    }
    public function getAll()
    {
        return $this->entity->all();
    }

    public function findById(int $id)
    {
        return $this->entity->findOrFail($id);
    }
    public function findWhereFirst(string $column, string $value)
    {
        return $this->entity->where($column, $value)->first();
    }

    public function store(array $data)
    {
        return $this->entity->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function destroy(int $id)
    {
        return $this->entity->destroy($id);
    }
    public function restore(int $id)
    {
        $this->entity->where('id', $id)->withTrashed()->restore();
    }
}