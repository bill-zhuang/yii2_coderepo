<?php
/* @var $module_name string module name */
/* @var $model_name string model name */
/* @var $table_name string table name */
/* @var $primary_id string table primary id */
/* @var $table_fields array table fields */
/* @var $labels array table field labels */
/* @var $rules array table rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace app<?php echo ($module_name === '') ? '' : ('\\modules\\' . strtolower($module_name)); ?>\models;

use Yii;
<?php if ($module_name !== ''){ ?>
use app\models\BillActiveRecord;
<?php } ?>
use app\library\bill\Constant;
/**
 * This is the model class for table "<?php echo $table_name; ?>".
 *
<?php foreach($table_fields as $field => $type){ ?>
 * @property <?php echo $type .  ' $' . $field . PHP_EOL; ?>
<?php } ?>
<?php if(!empty($relations)){foreach($relations as $name => $relation){ ?>
* @property <?php echo $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php }} ?>
 */
class <?php echo $model_name; ?> extends BillActiveRecord
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

    public static function get<?php echo $model_name; ?>ByID($<?php echo $primary_id; ?>)
    {
        return <?php echo $model_name; ?>::find()
            ->where(['<?php echo $primary_id; ?>' => $<?php echo $primary_id; ?>])
            ->asArray()
            ->one();
    }
}