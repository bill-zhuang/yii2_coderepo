<?php

namespace app\modules\person\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "finance_payment_map".
 *
 * @property string $fpmid
 * @property string $fp_id
 * @property string $fc_id
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class FinancePaymentMap extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'finance_payment_map';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fp_id', 'fc_id'], 'required'],
            [['fp_id', 'fc_id', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fpmid' => 'Fpmid',
            'fp_id' => 'Fp ID',
            'fc_id' => 'Fc ID',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getFinancePaymentMapCount(array $conditions)
    {
        $select = FinancePaymentMap::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $count = $select->count();
        return $count;
    }

    public static function getFinancePaymentMapData(array $conditions, $limit, $offset, $order_by)
    {
        $select = FinancePaymentMap::find();
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

    public static function getFinancePaymentMapByID($fpmid)
    {
        return FinancePaymentMap::find()
            ->where(['fpmid' => $fpmid])
            ->asArray()
            ->one();
    }

    public static function getFinanceCategoryIDs($fpid)
    {
        $data = FinancePaymentMap::find()
            ->select(['fc_id'])
            ->where(['fp_id' => $fpid])
            ->andWhere(['status' => 1])
            ->asArray()
            ->all();
        $fc_ids = [];
        foreach ($data as $value)
        {
            $fc_ids[] = $value['fc_id'];
        }

        return $fc_ids;
    }
}