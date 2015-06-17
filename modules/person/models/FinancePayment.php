<?php

namespace app\modules\person\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "finance_payment".
 *
 * @property string $fp_id
 * @property double $fp_payment
 * @property string $fp_payment_date
 * @property string $fp_detail
 * @property integer $fp_status
 * @property string $fp_create_time
 * @property string $fp_update_time
 */
class FinancePayment extends ActiveRecord
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
            [['fp_payment', 'fp_payment_date'], 'required'],
            [['fp_payment'], 'number'],
            [['fp_payment_date', 'fp_create_time', 'fp_update_time'], 'safe'],
            [['fp_status'], 'integer'],
            [['fp_detail'], 'string', 'max' => 255]
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

    public static function getFinancePaymentData(array $conditions, $limit, $offset, $order_by)
    {
        $select = FinancePayment::find();
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

    public static function getFinancePaymentByID($fp_id)
    {
        return FinancePayment::find()
            ->where(['fp_id' => $fp_id])
            ->asArray()
            ->one();
    }

    public static function getTotalPaymentHistoryGroupData()
    {
        return FinancePayment::find()
            ->select(['date_format(fp_payment_date, "%Y-%m") as period', 'sum(fp_payment) as payment'])
            ->where(['fp_status' => 1])
            ->groupBy(['date_format(fp_payment_date, "%Y%m")'])
            ->orderBy(['fp_payment_date' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getTotalPaymentHistoryDataByDay($start_date)
    {
        return FinancePayment::find()
            ->select(['fp_payment_date as period', 'sum(fp_payment) as payment'])
            ->where(['fp_status' => 1])
            ->andWhere(['>=', 'fp_payment_date', $start_date])
            ->groupBy(['fp_payment_date'])
            ->orderBy(['fp_payment_date' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getTotalPaymentHistoryDataByCategory($start_date)
    {
        return FinancePayment::find()
            ->select(['fc_id', 'sum(fp_payment) as payment'])
            ->where(['fp_status' => 1])
            ->andWhere(['>=', 'fp_payment_date', $start_date])
            ->groupBy(['fc_id'])
            ->orderBy(['payment' => SORT_DESC])
            ->asArray()
            ->all();
    }
}