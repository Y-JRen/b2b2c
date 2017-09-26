<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Partner */

$this->title = '新增商户-基本信息';
$this->params['breadcrumbs'][] = ['label' => '商户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
