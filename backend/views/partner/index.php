<?php

use yii\helpers\Html;
use common\widgets\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Partner */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-index">
    <?php Pjax::begin(); ?>
                <?php echo $this->render('_search', ['model' => $searchModel]);  ?>     <div class="box-header with-border">
        <?= Html::a('新增商户', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'address',
                'logo',
                'contact_person',
                // 'contact_phone',
                // 'create_time',
                // 'update_time',
                // 'description:ntext',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}'
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
