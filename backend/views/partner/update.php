<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Partner */

$this->title = '编辑商户';
$this->params['breadcrumbs'][] = ['label' => '商户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新商户';
?>
<div class="box-body">
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs pull-left">
            <li class="active">
                <a href="#basic-info" data-toggle="tab" aria-expanded="true">基本信息</a>
            </li>
            <li class=""><a href="#dealer-info" data-toggle="tab" aria-expanded="false">经销商信息</a></li>
            <li class=""><a href="#store-info-div" data-toggle="tab" aria-expanded="false">门店信息</a></li>
            <li class=""><a href="#financial-info" data-toggle="tab" aria-expanded="false">金融管理</a></li>
        </ul>
        <div class="tab-content no-padding">
            <!-- 基本信息 -->
            <div class="chart tab-pane active" id="basic-info">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
            <!-- 经销商信息 -->
            <div class="chart tab-pane" id="dealer-info">
                <?=$this->render('_dealer', ['model' => $model]);?>
            </div>

            <!-- 门店信息 -->
            <div class="chart tab-pane" id="store-info-div">
                <?= $this->render('_store', [
                    'model' => $model,
                    'partner' => [$model->id => $model->name],
                ]) ?>
            </div>

            <!-- 金融管理 -->
            <div class="chart tab-pane" id="financial-info">

            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('javascript') ?>
<?=$this->blocks['javascript-dealer'] ?>
<?=$this->blocks['javascript-store'] ?>
<?php $this->endBlock(); ?>

