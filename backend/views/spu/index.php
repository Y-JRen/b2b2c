<?php

use yii\helpers\Html;
use common\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Spu */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Spu Forms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spu-form-index">
                <?php echo $this->render('_search', ['model' => $searchModel]);  ?>     <div class="box-header with-border">
        <?= Html::a('Create Spu Form', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'create_time',
                'update_time',
                'name',
                'type_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
