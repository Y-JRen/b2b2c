<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Store */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'province_code')->widget(
                \common\widgets\vue\Cascade::className(),
                [
                    'attributes' => ['province_code', 'city_code', 'area_code'],
                    'cascadeData' => \common\logic\AreaLogic::instance()->getAreaTree(2)
                ]
        )->label('地址') ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'lon')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->textInput() ?>

        <?= $form->field($model, 'foreign_service')->textInput()->label("是否对外服务") ?>

        <?= $form->field($model, 'partner_id')->textInput()->label('合作商') ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
