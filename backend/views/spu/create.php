<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */

$this->title = '新增商品';
$this->params['breadcrumbs'][] = ['label' => 'Spu Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spu-form-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
