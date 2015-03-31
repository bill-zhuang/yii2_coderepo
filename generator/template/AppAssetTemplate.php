<?php
/* @var $module_name string module name */
/* @var $controller_name string controller name */
/* @var $css array css */
/* @var $js array js */

echo "<?php\n";
?>

namespace app\assets<?php echo ($module_name === '') ? '' : ('\\' . strtolower($module_name)); ?>;

use yii\web\AssetBundle;
class AppAsset<?php echo $controller_name; ?> extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

<?php
    foreach ($css as $value)
    {
        echo str_repeat(' ', 4 * 2) . "'" , $value, "'", ",\n";
    }
?>
    ];
    public $js = [
<?php
    foreach ($js as $value)
    {
        echo str_repeat(' ', 4 * 2) . "'" , $value, "'", ",\n";
    }
?>
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset', //bootstrap css
        'yii\bootstrap\BootstrapPluginAsset', //bootstrap js
        'app\assets\plugins\PaginationAsset',
    ];
}