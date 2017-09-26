<?php

use yii\helpers\Html;
use common\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Store */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '门店管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('新增门店', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'province_code',
                'province_name',
                'city_code',
                // 'city_name',
                // 'area_code',
                // 'area_name',
                // 'address',
                // 'contact_person',
                // 'contact_phone',
                // 'lon',
                // 'lat',
                // 'status',
                // 'foreign_service',
                // 'partner_id',
                // 'create_time',
                // 'update_time',
                // 'is_delete',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
