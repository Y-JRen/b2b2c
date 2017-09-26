<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = '新建门店';
$this->params['breadcrumbs'][] = ['label' => '门店管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
