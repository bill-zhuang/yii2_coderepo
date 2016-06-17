<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\library\bill\Constant;
/**
 * This is the model class for table "backend_log".
 *
 * @property string $blid
 * @property string $type
 * @property string $table
 * @property string $content
 * @property integer $buid
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class BackendLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'backend_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'buid'], 'required'],
            [['content'], 'string'],
            [['buid', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['type'], 'string', 'max' => 32],
            [['table'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'blid' => 'Blid',
            'type' => 'Type',
            'table' => 'Table',
            'content' => 'Content',
            'buid' => 'Buid',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getBackendLogCount(array $conditions)
    {
        $select = BackendLog::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $count = $select->count();
        return $count;
    }

    public static function getBackendLogData(array $conditions, $start, $pageLength, $orderBy)
    {
        $select = BackendLog::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $data = $select
            ->limit($pageLength)
            ->offset($start)
            ->orderBy($orderBy)
            ->asArray()
            ->all();
        return $data;
    }

    public static function getBackendLogByID($blid)
    {
        return BackendLog::find()
            ->where(['blid' => $blid])
            ->asArray()
            ->one();
    }

    public function getAllBlidAndContent()
    {
        $data = BackendLog::find()
            ->select(['blid', 'content', 'update_time'])
            ->where(['status' => Constant::VALID_STATUS])
            ->asArray()->all();
        return $data;
    }

    public function writeLog($type, $tableName, $data, $where = '')
    {
        $sql = '';
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

        if ($sql != '') {
            //TODO get user id(buid)
            $userId = Constant::INVALID_PRIMARY_ID;
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
            parent::insert($insertData);
        }
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
            if ($columnValue instanceof Zend_Db_Expr) {
                $columnValue = $columnValue->__toString();
            }
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

    /*
     * get from zend db abstract _whereExpr method
     * */
    private function _processWhere($where)
    {
        if (empty($where)) {
            return $where;
        }
        if (!is_array($where)) {
            $where = array($where);
        }
        foreach ($where as $cond => &$term) {
            // is $cond an int? (i.e. Not a condition)
            if (is_int($cond)) {
                // $term is the full condition
                if ($term instanceof Zend_Db_Expr) {
                    $term = $term->__toString();
                }
            } else {
                // $cond is the condition with placeholder,
                // and $term is quoted into the condition
                $term = str_replace('?', $this->getAdapter()->quote($term, null), $cond);
            }
            $term = '(' . $term . ')';
        }

        $where = implode(' AND ', $where);
        return $where;
    }
}