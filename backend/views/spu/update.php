<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */

$this->title = '编辑基本信息: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="spu-form-update">

    <?= $this->render('_form_base', [
        'model' => $model,
    ]) ?>

</div>
