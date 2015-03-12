<?php

namespace app\modules\person\models;

use Yii;
/**
 * This is the model class for table "finance_payment".
 *
 * @property string $fp_id
 * @property double $fp_payment
 * @property string $fp_payment_date
 * @property string $fc_id
 * @property string $fp_detail
 * @property integer $fp_status
 * @property string $fp_create_time
 * @property string $fp_update_time
 */
class FinancePayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'finance_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fp_payment'], 'number'],
            [['fp_payment_date', 'fp_create_time', 'fp_update_time'], 'safe'],
            [['fc_id', 'fp_status'], 'integer'],
            [['fp_detail'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fp_id' => 'Fp ID',
            'fp_payment' => 'Fp Payment',
            'fp_payment_date' => 'Fp Payment Date',
            'fc_id' => 'Fc ID',
            'fp_detail' => 'Fp Detail',
            'fp_status' => 'Fp Status',
            'fp_create_time' => 'Fp Create Time',
            'fp_update_time' => 'Fp Update Time',
        ];
    }

    public static function getFinancePaymentCount(array $conditions)
    {
        $select = FinancePayment::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $count = $select->count();
        return $count;
    }

    public static function getFinancePaymentData(array $conditions, $count, $offset, $order_by)
    {
        $select = FinancePayment::find();
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

    public static function getFinancePaymentByID($fp_id)
    {
        return FinancePayment::find()
            ->where(['fp_id' => $fp_id])
            ->asArray()
            ->one();
    }
}