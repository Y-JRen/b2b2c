<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */

$this->title = 'Update Spu Form: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Spu Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spu-form-update">

    <?= $this->render('_form_base', [
        'model' => $model,
    ]) ?>

</div>
