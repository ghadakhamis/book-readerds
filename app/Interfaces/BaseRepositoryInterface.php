<?php 

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    /**
     * @param  array $columns
     * @return array
     */
    public function all(Array $columns = array('*'));

    /**
     * @param  array $data
     * @param  bool $force
     * @return Model
     */
    public function create(array $data, bool $force = true);

    /**
     * @param  array $data
     * @param  int $id
     * @return Model
     */
    public function update(array $data, int $id);

    /**
     * @param  array $data
     * @param  array $ids
     * @return void
     */
    public function updateMultiple(Array $data, Array $ids);

    /**
     * @param  int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * @param  int $id
     * @param  array $columns
     * @return ?Model
     */
    public function find(int $id, Array $columns = array('*'));

    /**
     * @param  string $field
     * @param  string $value
     * @param  array $columns
     * @return ?Model
     */
    public function findBy(string $field, string $value, Array $columns = array('*'));
}