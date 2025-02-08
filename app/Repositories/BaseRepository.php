<?php

namespace App\Repositories;

class BaseRepository
{
    protected $model;

    /**
     * constructor.
     *
     * @param  Model  $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $this->model->where('id', $id)->update($data);

        return $this->find($id);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }
}
