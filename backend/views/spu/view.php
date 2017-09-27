<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\form\SpuForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Spu Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spu-form-view box box-primary">
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'create_time',
                'update_time',
                'name',
                'type_id',
            ],
        ]) ?>
    </div>
</div>
