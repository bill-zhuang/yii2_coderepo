<?php

namespace app\modules\person\models;

use Yii;
/**
 * This is the model class for table "bad_history".
 *
 * @property string $bh_id
 * @property string $bh_happen_date
 * @property integer $bh_count
 * @property integer $bh_status
 * @property string $bh_create_time
 * @property string $bh_update_time
 */
class BadHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bad_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bh_happen_date', 'bh_create_time', 'bh_update_time'], 'safe'],
            [['bh_count', 'bh_status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bh_id' => 'Bh ID',
            'bh_happen_date' => 'Bh Happen Date',
            'bh_count' => 'Bh Count',
            'bh_status' => 'Bh Status',
            'bh_create_time' => 'Bh Create Time',
            'bh_update_time' => 'Bh Update Time',
        ];
    }

    public static function getBadHistoryCount(array $conditions)
    {
        $select = BadHistory::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $count = $select->count();
        return $count;
    }

    public static function getBadHistoryData(array $conditions, $count, $offset, $order_by)
    {
        $select = BadHistory::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $data = $select
            ->limit($count)
            ->offset($offset)
            ->orderBy($order_by)
            ->asArray()
            ->all();
        return $data;
    }

    public static function getBadHistoryByID($bh_id)
    {
        return BadHistory::find()
            ->where(['bh_id' => $bh_id])
            ->asArray()
            ->one();
    }

    public static function getTotalBadHistoryDataByDay()
    {
        return BadHistory::find()
            ->select(['bh_happen_date as period', 'bh_count as number'])
            ->where(['bh_status' => 1])
            ->asArray()
            ->all();
    }
}