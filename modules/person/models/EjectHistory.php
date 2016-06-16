<?php

namespace app\modules\person\models;

use Yii;
use yii\db\ActiveRecord;
use app\library\bill\Constant;
/**
 * This is the model class for table "eject_history".
 *
 * @property string $ehid
 * @property string $happen_date
 * @property integer $count
 * @property integer $type
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class EjectHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eject_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['happen_date'], 'required'],
            [['happen_date', 'create_time', 'update_time'], 'safe'],
            [['count', 'type', 'status'], 'integer'],
            [['happen_date', 'type'], 'unique', 'targetAttribute' => ['happen_date', 'type'], 'message' => 'The combination of Happen Date and Type has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ehid' => 'Ehid',
            'happen_date' => 'Happen Date',
            'count' => 'Count',
            'type' => 'Type',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getEjectHistoryCount(array $conditions)
    {
        $select = EjectHistory::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $count = $select->count();
        return $count;
    }

    public static function getEjectHistoryData(array $conditions, $start, $pageLength, $orderBy)
    {
        $select = EjectHistory::find();
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

    public static function getEjectHistoryByID($ehid)
    {
        return EjectHistory::find()
            ->where(['ehid' => $ehid])
            ->asArray()
            ->one();
    }

    public static function isHistoryExistByHappenDateTypeEhid($happenDate, $type, $ehid = Constant::INVALID_PRIMARY_ID)
    {
        $count = EjectHistory::find()
            ->andWhere(['happen_date' => $happenDate])
            ->andWhere(['type' => $type])
            ->andWhere(['!=', 'ehid', $ehid])
            ->count();
        return ($count > 0) ? true : false;
    }

    public static function getTotalEjectHistoryDataByDay($startDate, $endDate, $type)
    {
        $select = EjectHistory::find()
            ->select(array('happen_date as period', 'count as number'))
            ->andWhere(['type' => $type])
            ->andWhere(['status' => Constant::VALID_STATUS]);
        if ($startDate !== '') {
            $select->where(['>=', 'happen_date', $startDate]);
        }
        if ($endDate !== '') {
            $select->where(['<=', 'happen_date', $endDate]);
        }

        return $select
            ->orderBy(['period' => SORT_ASC])
            ->asArray()->all();
    }

    public static function getTotalEjectHistoryGroupData($startDate, $endDate, $type)
    {
        $select = EjectHistory::find()
            ->select(array('date_format(happen_date, "%Y-%m") as period', 'sum(count) as number'))
            ->andWhere(['type' => $type])
            ->andWhere(['status' => Constant::VALID_STATUS]);
        if ($startDate !== '') {
            $select->where(['>=', 'happen_date', $startDate]);
        }
        if ($endDate !== '') {
            $select->where(['<=', 'happen_date', $endDate]);
        }
        return $select
            ->groupBy(['date_format(happen_date, "%Y%m")'])
            ->asArray()->all();
    }
}