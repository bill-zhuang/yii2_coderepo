<?php
/* @var $content string */
use yii\helpers\Html;
use app\assets\AppAssetLayoutLogin;
AppAssetLayoutLogin::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-cn">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php Html::csrfMetaTags(); ?>
        <title>Login - Bill Coderepo</title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
        <div class="login">
            <?php echo $content; ?>
        </div>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
