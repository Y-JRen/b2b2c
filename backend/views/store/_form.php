<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use common\widgets\vue\Cascade;
use common\logic\AreaLogic;

/* @var $this yii\web\View */
/* @var $model common\models\Store */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'province_code')->widget(
                Cascade::className(),
                [
                    'attributes' => ['province_code', 'city_code', 'area_code'],
                    'cascadeData' => AreaLogic::instance()->getAreaTree(2)
                ]
        )->label('地址')?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <div class="row">
                <label class="control-label col-lg-1" for="store-province_code"></label>
                <div class="col-lg-4">
                    <?= Html::button('获取地址经纬度', ['class' => 'btn btn-info', 'id' => 'get-address'])?>
                </div>
            </div>
        </div>

        <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'lon')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->dropDownList([-1 => '删除', '无效', '有效'])->label('状态') ?>

        <?= $form->field($model, 'foreign_service')->dropDownList(['否', '是'])->label("是否对外服务") ?>

        <?= $form->field($model, 'partner_id')->textInput()->label('合作商') ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->beginBlock('javascript'); ?>
<script>
    $(function(){
       $("#get-address").click(function(){
           alert(123);
       });
    })
</script>
<?php $this->endBlock(); ?>
