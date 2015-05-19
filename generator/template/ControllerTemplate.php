<?php
/* @var $module_name string module name */
/* @var $model_names array model name */
/* @var $controller_name string controller name */
/* @var $table_names array table names */
/* @var $primary_id string table primary key */
/* @var $table_data array table fields and default value */
/* @var $form_element_prefix string prefix of form element */
/* @var $model_param_name string main table model parameter name */

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
use yii\web\Controller;
use yii\filters\AccessControl;
<?php foreach($model_names as $model_name){ ?>
use app\<?php echo ($module_name === '') ? '' : ('modules\\' . strtolower($module_name) . '\\'); ?>models\<?php echo $model_name; ?>;<?php echo PHP_EOL; ?>
<?php } ?>

class <?php echo $controller_name; ?>Controller extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'index',
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
<?php if(!empty($model_names)){ ?>
        $current_page = intval(yii::$app->request->get('current_page', yii::$app->params['init_start_page']));
        $page_length = intval(yii::$app->request->get('page_length', yii::$app->params['init_page_length']));
        $start = ($current_page - yii::$app->params['init_start_page']) * $page_length;
        $keyword = trim(yii::$app->request->get('keyword', ''));

        $conditions = [
            '<?php echo ($status_name === '') ? 'todo status' : $status_name; ?>' => [
                'compare_type' => '=',
                'value' => yii::$app->params['valid_status']
            ]
        ];
        $order_by = ['<?php echo $primary_id; ?>' => SORT_DESC];
        $total = <?php echo $model_names[0]; ?>::get<?php echo $controller_name; ?>Count($conditions);
        $data = <?php echo $model_names[0]; ?>::get<?php echo $controller_name; ?>Data($conditions, $page_length, $start, $order_by);

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
            'js_data' => $js_data,
        ];
        return $this->render('index', $view_data);
<?php }else{ ?>
        return $this->render('index');
<?php } ?>
    }

<?php if(!empty($model_names)){ ?>
    public function actionAdd<?php echo $controller_name; ?>()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['<?php echo $form_element_prefix; ?>_name']))
        {
            $transaction = <?php echo $model_names[0]; ?>::getDb()->beginTransaction();
            try
            {
                $<?php echo $model_param_name; ?> = new <?php echo $model_names[0]; ?>();
<?php foreach ($table_data as $key => $default_value)
{
    if ($key != $primary_id)
    {
        echo str_repeat(' ', 4 * 4) . '$' . $model_param_name . '->' . $key . " = " . $default_value . ";" . PHP_EOL;
    }
}
?>
                $affected_rows = intval($<?php echo $model_param_name; ?>->save());
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

    public function actionModify<?php echo $controller_name; ?>()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['<?php echo $form_element_prefix; ?>_<?php echo $primary_id; ?>']))
        {
            $transaction = <?php echo $model_names[0]; ?>::getDb()->beginTransaction();
            try
            {
                $<?php echo $primary_id; ?> = intval(yii::$app->request->post('<?php echo $primary_id; ?>'));
                $<?php echo $model_param_name; ?> = <?php echo $model_names[0]; ?>::findOne($<?php echo $primary_id; ?>);
                if ($<?php echo $model_param_name; ?> instanceof <?php echo $model_names[0]; ?>)
                {
<?php foreach ($table_data as $key => $default_value)
{
    if ($key !== $primary_id)
    {
        echo str_repeat(' ', 4 * 5) . '$' . $model_param_name . '->' . $key . " = " . $default_value . ";" . PHP_EOL;
    }
}
?>
                    $affected_rows = intval($<?php echo $model_param_name; ?>->save());
                }
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

    public function actionDelete<?php echo $controller_name; ?>()
    {
        $affected_rows = yii::$app->params['init_affected_rows'];
        if (isset($_POST['<?php echo $primary_id; ?>']))
        {
            $transaction = <?php echo $model_names[0]; ?>::getDb()->beginTransaction();
            try
            {
                $<?php echo $primary_id; ?> = intval(yii::$app->request->post('<?php echo $primary_id; ?>'));
                $<?php echo $model_param_name; ?> = <?php echo $model_names[0]; ?>::findOne($<?php echo $primary_id; ?>);
                if ($<?php echo $model_param_name; ?> instanceof <?php echo $model_names[0]; ?>)
                {
                    //TODO set update data
                    $affected_rows = intval($<?php echo $model_param_name; ?>->save());
                }
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

    public function actionGet<?php echo $controller_name; ?>()
    {
        $data = [];
        if (isset($_GET['<?php echo $primary_id; ?>']))
        {
            $<?php echo $primary_id; ?> = intval(yii::$app->request->get('<?php echo $primary_id; ?>'));
            if ($<?php echo $primary_id; ?> > yii::$app->params['invalid_primary_id'])
            {
                $data = <?php echo $model_names[0]; ?>::get<?php echo $controller_name; ?>ByID($<?php echo $primary_id; ?>);
            }
        }

        echo json_encode($data);
        exit;
    }

<?php } ?>
}