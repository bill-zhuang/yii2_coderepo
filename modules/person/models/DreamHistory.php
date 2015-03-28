<?php

namespace app\modules\person\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "dream_history".
 *
 * @property string $dh_id
 * @property string $dh_happen_date
 * @property integer $dh_count
 * @property integer $dh_status
 * @property string $dh_create_time
 * @property string $dh_update_time
 */
class DreamHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dream_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dh_happen_date', 'dh_create_time', 'dh_update_time'], 'safe'],
            [['dh_count', 'dh_status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dh_id' => 'Dh ID',
            'dh_happen_date' => 'Dh Happen Date',
            'dh_count' => 'Dh Count',
            'dh_status' => 'Dh Status',
            'dh_create_time' => 'Dh Create Time',
            'dh_update_time' => 'Dh Update Time',
        ];
    }

    public static function getDreamHistoryCount(array $conditions)
    {
        $select = DreamHistory::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $count = $select->count();
        return $count;
    }

    public static function getDreamHistoryData(array $conditions, $limit, $offset, $order_by)
    {
        $select = DreamHistory::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $data = $select
            ->limit($limit)
            ->offset($offset)
            ->orderBy($order_by)
            ->asArray()
            ->all();
        return $data;
    }

    public static function getDreamHistoryByID($dh_id)
    {
        return DreamHistory::find()
            ->where(['dh_id' => $dh_id])
            ->asArray()
            ->one();
    }

    public static function getTotalDreamHistoryGroupData()
    {
        return DreamHistory::find()
            ->select(['date_format(dh_happen_date, "%Y-%m") as period', 'count(dh_count) as number'])
            ->where(['dh_status' => 1])
            ->groupBy(['date_format(dh_happen_date, "%Y%m")'])
            ->asArray()
            ->all();
    }

    public static function getTotalDreamHistoryGroupDataByYearMonth($select_date)
    {
        return DreamHistory::find()
            ->select(['dh_happen_date as period', 'dh_count as number'])
            ->where(['dh_status' => 1])
            ->andWhere(['date_format(dh_happen_date, "%Y-%m")' => $select_date])
            ->asArray()
            ->all();
    }

    public static function getTotalDreamHistoryDataByDay()
    {
        return DreamHistory::find()
            ->select(['dh_happen_date as period', 'dh_count as number'])
            ->where(['dh_status' => 1])
            ->asArray()
            ->all();
    }
}