<?php
/* @var $module_name string module name */
/* @var $model_names array model name */
/* @var $controller_name string controller name */
/* @var $table_names array table names */
/* @var $primary_id string table primary key */
/* @var $table_data array table fields and default value */
/* @var $form_element_prefix string prefix of form element */
/* @var $model_param_name string main table model parameter name */
/* @var $controller_url string controller name in url format */

$table_keys = array_keys($table_data);
$status_name = '';
foreach ($table_keys as $table_key)
{
    if (strpos($table_key, 'status') !== false)
    {
        $status_name = $table_key;
        break;
    }
}
echo "<?php\n";
?>

namespace app\<?php echo ($module_name === '') ? '' : ('modules\\' . strtolower($module_name) . '\\'); ?>controllers;

use yii;
<?php if ($module_name !== ''){ ?>
use app\controllers\BillController;
<?php } ?>
<?php foreach($model_names as $model_name){ ?>
use app\<?php echo ($module_name === '') ? '' : ('modules\\' . strtolower($module_name) . '\\'); ?>models\<?php echo $model_name; ?>;<?php echo PHP_EOL; ?>
<?php } ?>
use app\library\bill\Constant;
use app\library\bill\Util;
use app\library\bill\JsMessage;
use yii\web\Response;

class <?php echo $controller_name; ?>Controller extends BillController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAjaxIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->_index();
    }

<?php if(!empty($model_names)){ ?>
    public function actionAdd<?php echo $controller_name; ?>()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $transaction = <?php echo $model_names[0]; ?>::getDb()->beginTransaction();
            try {
                $<?php echo lcfirst($controller_name); ?> = new <?php echo $model_names[0]; ?>();
<?php foreach ($table_data as $key => $default_value)
{
    if ($key != $primary_id)
    {
        echo str_repeat(' ', 4 * 4) . '$' . lcfirst($controller_name) . '->' . $key . " = " . $default_value . ";" . PHP_EOL;
    }
}
?>
                $affectedRows = intval($<?php echo lcfirst($controller_name); ?>->save());
                $transaction->commit();
                $jsonArray = [
                    'data' => [
                        'code' => $affectedRows,
                        'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                        ? JsMessage::ADD_SUCCESS : JsMessage::ADD_FAIL,
                    ],
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                Util::handleException($e, 'Error From add<?php echo $controller_name; ?>');
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionModify<?php echo $controller_name; ?>()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $transaction = <?php echo $model_names[0]; ?>::getDb()->beginTransaction();
            try {
                $<?php echo $primary_id; ?> = intval(yii::$app->request->post('<?php echo $primary_id; ?>'));
                $<?php echo lcfirst($controller_name); ?> = <?php echo $model_names[0]; ?>::findOne($<?php echo $primary_id; ?>);
                if ($<?php echo lcfirst($controller_name); ?> instanceof <?php echo $model_names[0]; ?>) {
<?php foreach ($table_data as $key => $default_value)
{
    if ($key !== $primary_id && $key != 'status' && $key != 'create_time')
    {
        echo str_repeat(' ', 4 * 5) . '$' . lcfirst($controller_name) . '->' . $key . " = " . $default_value . ";" . PHP_EOL;
    }
}
?>
                    $affectedRows = intval($<?php echo lcfirst($controller_name); ?>->save());
                }
                $transaction->commit();
                $jsonArray = [
                    'data' => [
                        'code' => $affectedRows,
                        'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                            ? JsMessage::MODIFY_SUCCESS : JsMessage::MODIFY_FAIL,
                    ],
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                Util::handleException($e, 'Error From modify<?php echo $controller_name; ?>');
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionDelete<?php echo $controller_name; ?>()
    {
        $jsonArray = [];
        if (yii::$app->request->isPost) {
            $affectedRows = Constant::INIT_AFFECTED_ROWS;
            $params = yii::$app->request->post('params', array());
            $transaction = <?php echo $model_names[0]; ?>::getDb()->beginTransaction();
            try {
                $<?php echo $primary_id; ?> = intval($params['<?php echo $primary_id; ?>']);
                $<?php echo lcfirst($controller_name); ?> = <?php echo $model_names[0]; ?>::findOne($<?php echo $primary_id; ?>);
                if ($<?php echo lcfirst($controller_name); ?> instanceof <?php echo $model_names[0]; ?>) {
                    //TODO set update data
                    $<?php echo lcfirst($controller_name); ?>->status = Constant::INVALID_STATUS;
                    $<?php echo lcfirst($controller_name); ?>->update_time = date('Y-m-d H:i:s');
                    $affectedRows = intval($<?php echo lcfirst($controller_name); ?>->save());
                }
                $transaction->commit();
                $jsonArray = [
                    'data' => [
                        'code' => $affectedRows,
                        'message' => ($affectedRows > Constant::INIT_AFFECTED_ROWS)
                            ? JsMessage::DELETE_SUCCESS : JsMessage::DELETE_FAIL,
                    ],
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                Util::handleException($e, 'Error From delete<?php echo $controller_name; ?>');
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    public function actionGet<?php echo $controller_name; ?>()
    {
        if (yii::$app->request->isGet) {
            $params = yii::$app->request->get('params', array());
            $<?php echo $primary_id; ?> = (isset($params['<?php echo $primary_id; ?>'])) ? intval($params['<?php echo $primary_id; ?>']) : Constant::INVALID_PRIMARY_ID;
            $data = <?php echo $model_names[0]; ?>::get<?php echo $controller_name; ?>ByID($<?php echo $primary_id; ?>);
            if (!empty($data)) {
                $jsonArray = [
                    'data' => $data,
                ];
            }
        }

        if (!isset($jsonArray['data'])) {
            $jsonArray = [
                'error' => Util::getJsonResponseErrorArray(200, Constant::ACTION_ERROR_INFO),
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }

    private function _index()
    {
<?php if(!empty($model_names)){ ?>
        $params = yii::$app->request->get('params', array());
        list($currentPage, $pageLength, $start) = Util::getPaginationParamsFromUrlParamsArray($params);
        $keyword = isset($params['keyword']) ? trim($params['keyword']) : '';

        $conditions = [
            ['status' => Constant::VALID_STATUS],
        ];
        $orderBy = ['<?php echo $primary_id; ?>' => SORT_DESC];
        $total = <?php echo $model_names[0]; ?>::get<?php echo $controller_name; ?>Count($conditions);
        $data = <?php echo $model_names[0]; ?>::get<?php echo $controller_name; ?>Data($conditions, $start, $pageLength, $orderBy);

        $jsonData = [
            'data' => [
                'totalPages' => Util::getTotalPages($total, $pageLength),
                'pageIndex' => $currentPage,
                'totalItems' => $total,
                'startIndex' => $start + 1,
                'itemsPerPage' => $pageLength,
                'currentItemCount' => count($data),
                'items' => $data,
            ],
        ];
        return $jsonData;
<?php }else{ ?>
        return [];
<?php } ?>
    }

<?php } ?>
}