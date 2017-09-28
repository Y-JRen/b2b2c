<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Spu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spu-form-search box advanced-search-form mb-lg">
<div class="box-body">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'form-horizontal',
        ],
        'fieldClass' => 'common\widgets\ActiveSearchField'
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'create_time') ?>


    <?= $form->field($model, 'name')->label('商品名称') ?>
    
</div>
    <div class="box-footer">
        <div class="pull-right">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('清除', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
