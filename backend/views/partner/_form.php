<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


$model->partner_identity = ArrayHelper::getColumn($model->partnerIdentity, 'identity_id');

/* @var $this yii\web\View */
/* @var $model common\models\Partner */
/* @var $form common\widgets\ActiveForm */
?>

<div class="partner-form">
    <?php $form = ActiveForm::begin(); ?>
    
    <div class="box-body table-responsive">
    
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
    
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'logo')->widget(
                \common\widgets\vue\Upload::className(),
                [
                    'uploadUrl' => \yii\helpers\Url::to(['/file-upload/index'])
                ]
        ) ?>

        <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('确定', ['class' => 'btn btn-success btn-flat']) ?>
        &nbsp;
        <?= Html::a('取消',['index'], ['class' => 'btn btn-default btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
