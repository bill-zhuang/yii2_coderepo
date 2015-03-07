<?php
/* @var $module_name string module name */
/* @var $model_name string model name */
/* @var $table_name string table name */
/* @var $table_fields array table fields */
/* @var $labels array table field labels */
/* @var $rules array table rules */

echo "<?php\n";
?>

namespace app<?php echo ($module_name === '') ? '' : ('\\modules\\' . strtolower($module_name)); ?>\models;

use Yii;
/**
 * This is the model class for table "<?php echo $table_name; ?>".
 *
<?php foreach($table_fields as $field => $type){ ?>
 * @property <?php echo $type .  ' $' . $field . PHP_EOL; ?>
<?php } ?>
 */
class <?php echo $model_name; ?> extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?php echo $table_name; ?>';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $filed_name => $label)
{
    echo str_repeat(' ', 4 * 3) . "'" . $filed_name . "' => '" . $label . "'," . PHP_EOL;
}
?>
        ];
    }

    public static function get<?php echo $model_name; ?>Count(array $conditions)
    {
        $select = <?php echo $model_name; ?>::find();
        foreach ($conditions as $key => $content)
        {
            $select->andWhere([$content['compare_type'], $key, $content['value']]);
        }
        $count = $select->count();
        return $count;
    }

    public static function get<?php echo $model_name; ?>Data(array $conditions, $count, $offset, $order_by)
    {
        $select = <?php echo $model_name; ?>::find();
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

    public static function get<?php echo $model_name; ?>ByID($fc_id)
    {
        return <?php echo $model_name; ?>::find()
            ->where(['fc_id' => $fc_id])
            ->asArray()
            ->one();
    }
}