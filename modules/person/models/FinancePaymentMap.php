<?php

namespace app\modules\person\models;

use Yii;
use app\models\BillActiveRecord;
use app\library\bill\Constant;
/**
 * This is the model class for table "finance_payment_map".
 *
 * @property string $fpmid
 * @property string $fpid
 * @property string $fcid
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class FinancePaymentMap extends BillActiveRecord
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
            [['fpid', 'fcid'], 'required'],
            [['fpid', 'fcid', 'status'], 'integer'],
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
            'fpid' => 'Fpid',
            'fcid' => 'Fcid',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getFinancePaymentMapCount(array $conditions)
    {
        $select = FinancePaymentMap::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
        }
        $count = $select->count();
        return $count;
    }

    public static function getFinancePaymentMapData(array $conditions, $limit, $offset, $order_by)
    {
        $select = FinancePaymentMap::find();
        foreach ($conditions as $cond) {
            $select->andWhere($cond);
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
            ->select('fcid')
            ->where(['fpid' => $fpid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()
            ->all();
        $fcids = [];
        foreach ($data as $value) {
            $fcids[] = $value['fcid'];
        }

        return $fcids;
    }

    public static function getFpidByFcid($fcid, $orderBy, $start, $pageLength)
    {
        $data = FinancePaymentMap::find()
            ->select('fpid')
            ->where(['fcid' => $fcid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->orderBy($orderBy)
            ->limit($pageLength)
            ->offset($start)
            ->asArray()
            ->all();
        $fpids = [];
        foreach ($data as $value) {
            $fpids[] = $value['fpid'];
        }

        return $fpids;
    }

    public static function isPaymentExistUnderFcid($fcid)
    {
        $data = FinancePaymentMap::find()
            ->select('fpmid')
            ->where(['fcid' => $fcid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()
            ->one();

        return isset($data['fpmid']) ? true : false;
    }
}