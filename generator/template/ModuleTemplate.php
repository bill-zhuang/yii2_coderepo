<?php
/* @var $module_name string module name */

echo "<?php\n";
?>

namespace app\modules\<?php echo strtolower($module_name); ?>;

class <?php echo $module_name; ?>Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
    }
}