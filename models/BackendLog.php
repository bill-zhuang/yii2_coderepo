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
}