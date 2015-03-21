<?php

namespace app\modules\person\models;

use Yii;
/**
 * This is the model class for table "finance_category".
 *
 * @property string $fc_id
 * @property string $fc_name
 * @property string $fc_parent_id
 * @property string $fc_weight
 * @property integer $fc_status
 * @property string $fc_create_time
 * @property string $fc_update_time
 */
class FinanceCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'finance_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fc_name'], 'string'],
            [['fc_parent_id', 'fc_weight', 'fc_status'], 'integer'],
            [['fc_create_time', 'fc_update_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fc_id' => 'Fc ID',
            'fc_name' => 'Fc Name',
            'fc_parent_id' => 'Fc Parent ID',
            'fc_weight' => 'Fc Weight',
            'fc_status' => 'Fc Status',
            'fc_create_time' => 'Fc Create Time',
            'fc_update_time' => 'Fc Update Time',
        ];
    }

    public static function getFinanceCategoryCount(array $conditions)
    {
        $select = FinanceCategory::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $count = $select->count();
        return $count;
    }

    public static function getFinanceCategoryData(array $conditions, $limit, $offset, $order_by)
    {
        $select = FinanceCategory::find();
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

    public static function getFinanceCategoryByID($fc_id)
    {
        return FinanceCategory::find()
            ->where(['fc_id' => $fc_id])
            ->asArray()
            ->one();
    }

    public static function getAllParentCategory()
    {
        $parent_data = FinanceCategory::find()
            ->select(['fc_id', 'fc_name'])
            ->where(['fc_parent_id' => 0])
            ->where(['fc_status' => 1])
            ->orderBy('fc_weight desc')
            ->asArray()
            ->all();
        $data = [];
        foreach ($parent_data as $parent_value)
        {
            $data[$parent_value['fc_id']] = $parent_value['fc_name'];
        }

        return $data;
    }

    public static function isFinanceCategoryExist($name, $fc_id)
    {
        $count = FinanceCategory::find()
            ->where(['fc_name' => $name])
            ->andWhere(['!=', 'fc_id', $fc_id])
            ->andWhere(['fc_status' => 1])
            ->count();
        return $count == 0 ? false : true;
    }

    public static function getFinaceCategoryName($fc_id)
    {
        $data = FinanceCategory::find()
            ->select('fc_name')
            ->where(['fc_id' => $fc_id])
            ->asArray()
            ->one();
        return isset($data['fc_name']) ? $data['fc_name'] : '';
    }

    public static function getFinanceSubcategory($parent_id)
    {
        $subcategory_data = FinanceCategory::find()
            ->select(['fc_id', 'fc_name'])
            ->where(['fc_parent_id' => $parent_id])
            ->andWhere(['fc_status' => 1])
            ->asArray()
            ->all();
        $data = [];
        foreach ($subcategory_data as $subcategory_value)
        {
            $data[$subcategory_value['fc_id']] = $subcategory_value['fc_name'];
        }

        return $data;
    }

    public function getFinanceParentCategory($fc_id)
    {
        $data = FinanceCategory::find()
            ->select('fc_parent_id')
            ->where(['fc_id' => $fc_id])
            ->asArray()
            ->one();
        return isset($data['fc_parent_id']) ? $data['fc_parent_id'] : 0;
    }
}