<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */
/* @var $form common\widgets\ActiveForm */
/* @var $image_model \common\models\SkuItemAttachment */

$model->images = \yii\helpers\ArrayHelper::getColumn(\common\models\SkuItemAttachment::findAll(['item_id' => $model->id]), 'url');
?>

<div class="spu-form-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">
        <input type="hidden" name="fm" value="introduce">
        <?= $form->field($model, 'name')->textInput() ?>
        
        <?= $form->field($model, 'subname')->textInput() ?>


        <?= $form->field($model, 'images')->widget(
            \common\widgets\vue\Upload::className(),
            [
                'uploadUrl' => \yii\helpers\Url::to(['/file-upload/item-image?id='.$model->id]),
                'limit' => 10,
                'deleteUrl' => \yii\helpers\Url::to(['/file-upload/del-item-image?id='.$model->id]),
                'multiple' => true
            ]
        )->label('橱窗图') ?>

        <?= $form->field($model, 'des')->widget('kucha\ueditor\UEditor', [
            'clientOptions'=>[
                'toolbars' => [
                    [
                        'fullscreen','source','simpleupload',/*'insertimage',*/
                    ],
                ],
                'elementPathEnabled' => false,
                'wordCount' => false,  //是否开启字数统计
            ]
        ])
        ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>