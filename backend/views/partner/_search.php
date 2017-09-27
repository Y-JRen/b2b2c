<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\search\Partner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-search box advanced-search-form mb-lg">
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

    <?= $form->field($model, 'name') ?>
    
    <?= $form->field($model, 'partner_identity')->widget(\kartik\select2\Select2::className(), [
        'options' => ['multiple' => true, 'placeholder' => '请选择 ...'],
        'data' => \common\logic\PartnerBaseIdentityLogic::instance()->getMenu(),
        'maintainOrder' => true,
        'name' => 'partner_identity[]',
        'pluginOptions' => [
            'tags' => true,
            'maximumInputLength' => 10
        ]
    ]) ?>
    
    <?php echo $form->field($model, 'create_time')->label('入驻时间') ?>

    <?php  echo $form->field($model, 'update_time')->label('修改时间') ?>

    <?php // echo $form->field($model, 'description') ?>

</div>
    <div class="box-footer">
        <div class="pull-right">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('清楚', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
