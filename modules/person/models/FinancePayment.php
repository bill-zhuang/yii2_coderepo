<?php

namespace app\modules\person\models;

use Yii;
use app\models\BillActiveRecord;
use app\library\bill\Constant;
/**
 * This is the model class for table "finance_payment".
 *
 * @property string $fpid
 * @property double $payment
 * @property string $payment_date
 * @property string $detail
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class FinancePayment extends BillActiveRecord
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
            [['payment', 'payment_date'], 'required'],
            [['payment'], 'number'],
            [['payment_date', 'create_time', 'update_time'], 'safe'],
            [['status'], 'integer'],
            [['detail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fpid' => 'Fpid',
            'payment' => 'Payment',
            'payment_date' => 'Payment Date',
            'detail' => 'Detail',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getFinancePaymentCount(array $conditions)
    {
        $select = FinancePayment::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $count = $select->count();
        return $count;
    }

    public static function getFinancePaymentData(array $conditions, $start, $pageLength, $orderBy)
    {
        $select = FinancePayment::find();
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

    public static function getFinancePaymentByID($fpid)
    {
        return FinancePayment::find()
            ->andWhere(['fpid' => $fpid])
            ->asArray()
            ->one();
    }

    public static function getTotalPaymentHistoryGroupData($startDate, $endDate)
    {
        $select = FinancePayment::find()
            ->select(['date_format(payment_date, "%Y-%m") as period', 'sum(payment) as payment'])
            ->andWhere(['status' => Constant::VALID_STATUS]);
        if ($startDate !== '') {
            $select->andWhere(['>=', 'payment_date', $startDate]);
        }
        if ($endDate !== '') {
            $select->andWhere(['<=', 'payment_date', $endDate]);
        }
        return $select
            ->groupBy(['date_format(payment_date, "%Y%m")'])
            ->orderBy(['payment_date' => SORT_ASC])
            ->asArray()->all();
    }

    public static function getTotalPaymentHistoryDataByDay($startDate, $endDate, $fcid, $ignoreMoney = 0)
    {
        if ($fcid == Constant::INVALID_PRIMARY_ID) {
            $select = FinancePayment::find()
                ->select(['payment_date as period', 'sum(payment) as payment'])
                ->andWhere(['status' => Constant::VALID_STATUS])
                ->andWhere(['>=', 'payment_date', $startDate])
                ->andWhere(['<=', 'payment_date', $endDate]);
            if ($ignoreMoney > 0) {
                $select->andWhere(['<?', 'payment', $ignoreMoney]);
            }
            return $select
                ->groupBy(['payment_date'])
                ->orderBy(['payment_date' => SORT_ASC])
                ->asArray()->all();
        } else {
            $select = FinancePayment::find()
                ->select(['payment_date as period', 'sum(payment) as payment'])
                ->innerJoin('finance_payment_map', 'finance_payment.fpid=finance_payment_map.fpid', [])
                ->andWhere([FinancePayment::tableName() . '.status' => Constant::VALID_STATUS])
                ->andWhere(['>=', FinancePayment::tableName() . '.payment_date', $startDate])
                ->andWhere(['<=', FinancePayment::tableName() . '.payment_date', $endDate])
                ->andWhere(['finance_payment_map.fcid' => $fcid]);
            if ($ignoreMoney > 0) {
                $select->andWhere(['<?', 'payment', $ignoreMoney]);
            }
            return $select
                ->groupBy([FinancePayment::tableName() . '.payment_date'])
                ->orderBy([FinancePayment::tableName() . '.payment_date' => SORT_ASC])
                ->asArray()->all();
        }
    }

    public static function getTotalPaymentHistoryDataByCategory($startDate)
    {
        return FinancePayment::find()
            ->select(['sum(payment) as payment', 'fcid'])
            ->innerJoin('finance_payment_map', 'finance_payment.fpid=finance_payment_map.fpid', [])
            ->andWhere([FinancePayment::tableName() . '.status' => Constant::VALID_STATUS])
            ->andWhere(['>=', FinancePayment::tableName() . '.payment_date', $startDate])
            ->andWhere(['finance_payment_map.status' => Constant::VALID_STATUS])
            ->groupBy(['finance_payment_map.fcid'])
            ->orderBy(['payment' => SORT_DESC])
            ->asArray()->all();
    }

    public static function getAllPaymentDataForTransfer()
    {
        return FinancePayment::find()
            ->select(['fpid', 'fcid', 'create_time', 'update_time'])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()->all();
    }

    public static function getSumPaymentByDate($startDate)
    {
        $data = FinancePayment::find()
            ->select('sum(payment) as total')
            ->andWhere(['>=', 'payment_date', $startDate])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()->all();
        return floatval($data[0]['total']);
    }
}