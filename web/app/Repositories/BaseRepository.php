<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Repository\Eloquent\BaseRepository as L5Repository;

/**
 * Class BaseRepository
 * @property Model|Builder $model
 * @package App\Repositories
 */
abstract class BaseRepository extends L5Repository implements RepositoryInterface
{
    /**
     * @param array $conditions
     * @param array $columns
     * @return mixed
     * @throws RepositoryException
     */
    public function findWhereForUpdate(array $conditions, $columns = ['*'])
    {
        $this->applyConditions($conditions);

        $results = $this->model->lockForUpdate()->first($columns);

        $this->resetModel();

        return $this->parserResult($results);
    }

    public function findWhereFirst(array $conditions, $columns = ['*'])
    {
        $this->applyConditions($conditions);

        return $this->first($columns);
    }

    /**
     * @param array $conditions
     * @param array $group
     * @param array $columns
     * @return mixed
     * @throws RepositoryException
     */
    public function findWhereGroup(array $conditions, array $group, array $columns = ['*'])
    {
        $this->applyConditions($conditions);

        $results = $this->model->groupBy($group)->get($columns);

        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * @param $records
     * @param array $exclude
     * @return bool
     * insert [[][]]
     * update ['id' => [], 'id2'=>[]]
     */
    public function insertOrUpdateBatch($records, array $exclude = [])
    {
        $columnsString = $valuesString = $updateString = '';
        $params = [];
        $size   = count($records);

        for ($i = 0; $i < $size; $i++) {
            $row = (array) $records[$i];
            if ($i == 0) {
                foreach ($row as $key => $value) {
                    $columnsString .= "$key,";
                    $updateString .= "$key=VALUES($key),";
                }
                $columnsString = rtrim($columnsString, ',');
                $updateString = rtrim($updateString, ',');
            } else {
                $valuesString .= ',';
            }

            $valuesString .= '(';

            foreach ($row as $key => $value) {
                $valuesString .= '?,';
                // CHANGE 20220510 条件文の変更
                //if (empty($value) && !is_float($value) && !is_int($value)) {
                if (empty($value) && $value !== "0" && $value !== 0 && !is_float($value) && !is_int($value)) {
                    $value = "";
                }
                array_push($params, $value);
            }

            $valuesString = rtrim($valuesString, ',');
            $valuesString .= ')';
        }

        $query = "INSERT INTO {$this->model->getTable()} ({$columnsString}) VALUES $valuesString ON duplicate KEY UPDATE $updateString";

        return $this->model->getConnection()->statement($query, $params);
    }

    /**
     * @param array $where
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                if (strtoupper($condition) == 'IN') {
                    $this->model = $this->model->whereIn($field, $val);
                } else if (strtoupper($condition) == 'NOT_IN') {
                    $this->model = $this->model->whereNotIn($field, $val);
                } else if (strtoupper($condition) == 'WHERE_BETWEEN') {
                    $this->model = $this->model->whereBetween($field, $val);
                } else if (strtoupper($condition) == 'LIKE') {
                    $this->model = $this->model->where($field, 'like', "%" . $val . "%");
                } else {
                    $this->model = $this->model->where($field, $condition, $val);
                }
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

    /**
     * Paginate with criteria filter
     *
     * @throws RepositoryException
     */
    public function criteriaPaginate(CriteriaInterface $criteria, int $limit = 10, array $columns = ['*'])
    {
        $this->pushCriteria($criteria);

        return $this->paginate($limit, $columns);
    }

    /**
     * Delete multiple record
     *
     * @param array $conditions
     * @return bool
     */
    public function deleteMultipleRecord(array $conditions): bool
    {
        $deleted =  $this->model->whereIn('id',$conditions)->delete();
        if ($deleted > 0 && $deleted == count($conditions)) {
            return true;
        }
        return false;
    }

    public function updateMultipleRecord(array $values)
    {
        $table = $this->model->getTable();
        $ids = [];
        $params = [];
        $columnsGroups = [];
        $queryStart = "UPDATE {$table} SET";
        $columnsNames = array_keys(array_values($values)[0]);
        foreach ($columnsNames as $columnName) {
            $cases = [];
            $columnGroup = " ".$columnName ." = CASE id ";
            foreach ($values as $id => $newData){
                $cases[] = "WHEN {$id} then ?";
                $params[] = $newData[$columnName];
                $ids[$id] = $id;
            }
            $cases = implode(' ', $cases);
            $columnsGroups[] = $columnGroup. "{$cases} END";
        }
        $columnsGroups = implode(',', $columnsGroups);
        $params[] = Carbon::now();
        $idd = " where id In(".implode(',',$ids).")";
        return DB::update($queryStart.$columnsGroups.$idd, $params);
    }

    /**
     * Update multiple record by condition
     *
     * @param array $conditions
     * @param array $value
     * @return bool
     */
    public function updateByWhere(array $conditions, array $value): bool
    {
        $updated = $this->model->where($conditions)->update($value);
        if ($updated > 0) {
            return true;
        }
        return false;
    }
}