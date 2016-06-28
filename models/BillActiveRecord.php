<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\db\Expression;
use app\library\bill\Constant;

class BillActiveRecord extends ActiveRecord
{
    public function insert($runValidation = true, $attributes = null)
    {
        list($data, ) = $this->_getSaveDataAndWhere();
        $this->_writeLog('insert', $data);
        return parent::insert($runValidation, $attributes);
    }

    public static function updateAll($attributes, $condition = '', $params = [])
    {
        self::_writeLogFromCommand('update', $attributes, $condition, $params);
        return parent::updateAll($attributes, $condition, $params);
    }

    public static function updateAllCounters($counters, $condition = '', $params = [])
    {
        $n = 0;
        $tempCounters = $counters;
        foreach ($tempCounters as $name => $value) {
            $tempCounters[$name] = new Expression("[[$name]]+:bp{$n}", [":bp{$n}" => $value]);
            $n++;
        }
        self::_writeLogFromCommand('update', $tempCounters, $condition, $params);
        return parent::updateAllCounters($counters, $condition, $params);
    }

    public static function deleteAll($condition = '', $params = [])
    {
        self::_writeLogFromCommand('delete', array(), $condition, $params);
        return parent::deleteAll($condition, $params);
    }

    private function _writeLog($type, $data, $where = '')
    {
        $sql = '';
        $tableName = $this->tableName();
        switch($type) {
            case 'insert':
                $sql = $this->_getInsertSQL($tableName, $data);
                break;
            case 'update':
                $sql = $this->_getUpdateSQL($tableName, $data, $where);
                break;
            case 'delete':
                $sql = $this->_getDeleteSQL($tableName, $where);
                break;
            default:
                break;
        }

        self::_writeLogBase($type, $tableName, $sql);
    }

    private static function _writeLogFromCommand($type, $attributes, $condition, $params)
    {
        $tempParams = $params;
        $command = static::getDb()->createCommand();
        if ($type == 'insert') {
            $commandObj = $command->insert(static::tableName(), $attributes);
        } else if ($type == 'update') {
            $commandObj = $command->update(static::tableName(), $attributes, $condition, $tempParams);
        } else if ($type == 'delete') {
            $commandObj = $command->delete(static::tableName(), $condition, $tempParams);
        } else {
            $commandObj = null;
        }

        if ($commandObj instanceof Command) {
            $sql = $commandObj->getRawSql();
            self::_writeLogBase($type, static::tableName(), $sql);
        }
    }

    private static function _writeLogBase($type, $tableName, $sql)
    {
        $userId = isset(Yii::$app->user->identity->buid) ? Yii::$app->user->identity->buid : '';
        $dateTime = date('Y-m-d H:i:s');
        $insertData = [
            'type' => $type,
            'table' => $tableName,
            'content' => $sql,
            'buid' => $userId,
            'status' => Constant::VALID_STATUS,
            'create_time' => $dateTime,
            'update_time' => $dateTime
        ];
        Yii::$app->getDb()->createCommand()->insert(BackendLog::tableName(), $insertData)->execute();
    }

    private function _getSaveDataAndWhere()
    {
        $data = $this->getDirtyAttributes();
        $where = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $data[$lock] = $this->$lock + 1;
            $where[$lock] = $this->$lock;
        }

        return [
            $data,
            $where,
        ];
    }

    private function _getInsertSQL($table, array $bind)
    {
        $sql = 'insert into ' . $table . '(';
        $names = '';
        $values = '';
        foreach ($bind as $columnName => $columnValue) {
            $names .= $columnName . ',';
            $values .= '\'' . addslashes($columnValue) . '\',';
        }
        $names = substr($names, 0, -1);
        $values = substr($values, 0, -1);
        $sql = $sql . $names . ') values (' . $values . ');';

        return $sql;
    }

    private function _getUpdateSQL($table, array $bind, $where)
    {
        $sql = 'update ' . $table . ' set ';
        $setSql = '';
        foreach ($bind as $columnName => $columnValue) {
            $setSql .= $columnName . '=\'' . addslashes($columnValue) . '\',';
        }
        $setSql = substr($setSql, 0, -1);
        $sql .= $setSql . ' where ' . $this->_processWhere($where) . ';';

        return $sql;
    }

    private function _getDeleteSQL($table, $where)
    {
        $sql = 'delete from ' . $table . ' where ' . $this->_processWhere($where) . ';';

        return $sql;
    }

    private function _processWhere($where)
    {
        if (empty($where)) {
            return $where;
        }
        if (!is_array($where)) {
            $where = array($where);
        }
        foreach ($where as $cond => &$term) {
            $term = '(' . $cond . '=' . $term  . ')';
        }

        $where = implode(' AND ', $where);
        return $where;
    }
}