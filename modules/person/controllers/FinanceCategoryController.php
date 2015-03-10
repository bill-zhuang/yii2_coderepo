<?php

namespace app\modules\person\controllers;

use yii;
use yii\web\Controller;
use app\modules\person\models\FinanceCategory;

class FinanceCategoryController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {

    }

    public function actionIndex()
    {
        $current_page = intval(yii::$app->request->get('current_page', yii::$app->params['init_start_page']));
        $page_length = intval(yii::$app->request->get('page_length', yii::$app->params['init_page_length']));
        $start = ($current_page - yii::$app->params['init_start_page']) * $page_length;
        $keyword = trim(yii::$app->request->get('keyword', ''));

        $conditions = [
            'fc_status' => [
                'compare_type' => '=',
                'value' => yii::$app->params['valid_status']
            ]
        ];
        if ('' !== $keyword)
        {
            $conditions['fc_name'] = [
                'compare_type' => 'like',
                'value' => $keyword
            ];
        }
        $order_by = 'fc_weight desc';
        $total = FinanceCategory::getFinanceCategoryCount($conditions);
        $data = FinanceCategory::getFinanceCategoryData($conditions, $page_length, $start, $order_by);

        $js_data = [
            'current_page' => $current_page,
            'page_length' => $page_length,
            'total_pages' => ceil($total / $page_length) ? ceil($total / $page_length) : yii::$app->params['init_total_page'],
            'total' => $total,
            'start' => $start,
            'keyword' => $keyword,
        ];
        $view_data = [
            'data' => $data,
            'parents' => FinanceCategory::getAllParentCategory(),
            'js_data' => $js_data,
        ];
        return $this->render('index', $view_data);
    }

    public function actionAddFinanceCategory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['finance_category_name']))
        {
            $transaction = FinanceCategory::getDb()->beginTransaction();
            try
            {
                $affected_rows = $this->_addFinanceCategory();
                $transaction->commit();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
                $transaction->rollBack();
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionModifyFinanceCategory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['finance_category_fc_id']))
        {
            $transaction = FinanceCategory::getDb()->beginTransaction();
            try
            {
                $affected_rows = $this->_updateFinanceCategory();
                $transaction->commit();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
                $transaction->rollBack();
            }
        }
        
        echo json_encode($affected_rows);
        exit;
    }

    public function actionDeleteFinanceCategory()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['fc_id']))
        {
            $transaction = FinanceCategory::getDb()->beginTransaction();
            try
            {
                $fc_id = intval(yii::$app->request->post('fc_id'));
                $update_data = [
                    'fc_status' => yii::$app->params['invalid_status'],
                    'fc_update_time' => date('Y-m-d H:i:s')
                ];
                $where = [
                    'and', 'fc_status=1', ['or', 'fc_id=' . $fc_id, 'fc_parent_id=' . $fc_id],
                ];
                $affected_rows = FinanceCategory::updateAll($update_data, $where);
                $transaction->commit();
            }
            catch (\Exception $e)
            {
                $affected_rows = yii::$app->params['init_affected_rows'];
                $transaction->rollBack();
            }
        }

        echo json_encode($affected_rows);
        exit;
    }

    public function actionGetFinanceCategory()
    {
        $data = [];
        if (isset($_GET['fc_id']))
        {
            $fc_id = intval(yii::$app->request->get('fc_id'));
            if ($fc_id > yii::$app->params['invalid_primary_id'])
            {
                $data = FinanceCategory::getFinanceCategoryByID($fc_id);
            }
        }

        echo json_encode($data);
        exit;
    }

    public function actionGetFinanceSubcategory()
    {
        $data = [];
        if (isset($_GET['parent_id']))
        {
            $parent_id = intval(yii::$app->request->get('parent_id'));
            $data = FinanceCategory::getFinanceSubcategory($parent_id);
        }

        echo json_encode($data);
        exit;
    }

    private function _addFinanceCategory()
    {
        $data = [
            'fc_name' => trim(yii::$app->request->post('finance_category_name')),
            'fc_parent_id' => intval(yii::$app->request->post('finance_category_parent_id')),
            'fc_weight' => intval(yii::$app->request->post('finance_category_weight')),
            'fc_status' => yii::$app->params['valid_status'],
            'fc_create_time' => date('Y-m-d H:i:s'),
            'fc_update_time' => date('Y-m-d H:i:s'),
        ];
        $adapter_finance_category = new FinanceCategory();
        foreach ($data as $key => $value)
        {
            $adapter_finance_category->$key = $value;
        }
        return $adapter_finance_category->insert();
    }

    private function _updateFinanceCategory()
    {
        $fc_id = intval(yii::$app->request->post('finance_category_fc_id'));
        $old_data = FinanceCategory::findOne($fc_id);

        $old_data->fc_name = trim(yii::$app->request->post('finance_category_name'));
        $old_data->fc_parent_id = intval(yii::$app->request->post('finance_category_parent_id'));
        $old_data->fc_weight = intval(yii::$app->request->post('finance_category_weight'));
        $old_data->fc_update_time = date('Y-m-d H:i:s');

        return $old_data->save();
    }

}