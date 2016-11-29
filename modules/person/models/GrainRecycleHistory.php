<?php

namespace app\modules\person\models;

use Yii;
use app\models\BillActiveRecord;
use app\library\bill\Constant;
/**
 * This is the model class for table "grain_recycle_history".
 *
 * @property string $grhid
 * @property string $happen_date
 * @property integer $count
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class GrainRecycleHistory extends BillActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grain_recycle_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['happen_date'], 'required'],
            [['happen_date', 'create_time', 'update_time'], 'safe'],
            [['count', 'status'], 'integer'],
            [['happen_date'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'grhid' => 'Grhid',
            'happen_date' => 'Happen Date',
            'count' => 'Count',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getGrainRecycleHistoryByID($grhid)
    {
        return GrainRecycleHistory::find()
            ->where(['grhid' => $grhid])
            ->asArray()
            ->one();
    }

    public static function getTotalGrainRecycleHistoryGroupDataByYearMonth($selectDate)
    {
        return GrainRecycleHistory::find()
            ->select(array('happen_date as period', 'count as number'))
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->andWhere(['date_format(happen_date, "%Y-%m")' => $selectDate])
            ->asArray()
            ->all();
    }

    public static function getTotalGrainRecycleHistoryGroupData($startDate, $endDate)
    {
        $select = GrainRecycleHistory::find()
            ->select(array('date_format(happen_date, "%Y-%m") as period', 'sum(count) as number'))
            ->andWhere(['status' => Constant::VALID_STATUS]);
        if ($startDate !== '') {
            $select->andWhere(['>=', 'happen_date', $startDate]);
        }
        if ($endDate !== '') {
            $select->andWhere(['<=', 'happen_date', $endDate]);
        }

        return $select
            ->groupBy(array('date_format(happen_date, "%Y%m")'))
            ->asArray()
            ->all();
    }

    public static function getTotalGrainRecycleHistoryDataByDay($startDate, $endDate)
    {
        $select = GrainRecycleHistory::find()
            ->select(array('happen_date as period', 'count as number'))
            ->andWhere(['status' => Constant::VALID_STATUS]);
        if ($startDate !== '') {
            $select->andWhere(['>=', 'happen_date', $startDate]);
        }
        if ($endDate !== '') {
            $select->andWhere(['<=', 'happen_date', $endDate]);
        }

        return $select
            ->asArray()
            ->all();
    }
}