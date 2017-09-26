<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = '编辑: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '门店管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="store-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
