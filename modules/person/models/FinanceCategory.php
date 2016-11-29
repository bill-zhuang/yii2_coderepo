<?php

namespace app\modules\person\models;

use Yii;
use app\models\BillActiveRecord;
use app\library\bill\Constant;
/**
 * This is the model class for table "finance_category".
 *
 * @property string $fcid
 * @property string $name
 * @property string $parent_id
 * @property string $weight
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 */
class FinanceCategory extends BillActiveRecord
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
            [['parent_id', 'weight', 'status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fcid' => 'Fcid',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'weight' => 'Weight',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function getFinanceCategoryByID($fcid)
    {
        return FinanceCategory::find()
            ->where(['fcid' => $fcid])
            ->asArray()
            ->one();
    }

    public static function getAllParentCategory($isKeyValueFormat = false)
    {
        $parentData = FinanceCategory::find()
            ->select(['fcid', 'name'])
            ->where(['parent_id' => 0])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->orderBy(['weight' => SORT_DESC])
            ->asArray()
            ->all();
        if ($isKeyValueFormat) {
            $data = [];
            foreach ($parentData as $parentValue) {
                $data[$parentValue['fcid']] = $parentValue['name'];
            }
            return $data;
        }
        return $parentData;
    }

    public static function isFinanceCategoryExist($name, $fcid)
    {
        $count = FinanceCategory::find()
            ->where(['name' => $name])
            ->andWhere(['!=', 'fcid', $fcid])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->count();
        return $count;
    }

    public static function getFinanceCategoryName($fcid)
    {
        $data = FinanceCategory::find()
            ->select('name')
            ->where(['fcid' => $fcid])
            ->asArray()
            ->one();
        return isset($data['name']) ? $data['name'] : '';
    }

    public static function getFinanceCategoryNames(array $fcids)
    {
        $data = FinanceCategory::find()
            ->select('name')
            ->where(['in', 'fcid', $fcids])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()
            ->all();
        $names = [];
        foreach ($data as $value) {
            $names[] = $value['name'];
        }

        return $names;
    }

    public static function getFinanceSubcategory($parentId)
    {
        $subcategoryData = FinanceCategory::find()
            ->select(['fcid', 'name'])
            ->where(['parent_id' => $parentId])
            ->andWhere(['status' => Constant::VALID_STATUS])
            ->asArray()
            ->all();
        $data = [];
        foreach ($subcategoryData as $subcategoryValue) {
            $data[$subcategoryValue['fcid']] = $subcategoryValue['name'];
        }

        return $data;
    }

    public static function getFinanceParentCategory($fcid)
    {
        $data = FinanceCategory::find()
            ->select('parent_id')
            ->where(['fcid' => $fcid])
            ->asArray()
            ->one();
        return isset($data['parent_id']) ? $data['parent_id'] : 0;
    }

    public static function getParentCategoryName($fcid)
    {
        $data = FinanceCategory::find()
            ->select('name')
            ->where(['fcid' => $fcid])
            ->asArray()
            ->one();
        return isset($data['name']) ? $data['name'] : '';
    }
}