<?php

use yii\helpers\Html;
use common\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Spu */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spu-form-index">
    <?php echo $this->render('_search', ['model' => $searchModel]);  ?>
    <div class="box-header with-border">
        <?= Html::a('新增商品', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => '商户名称',
                    'value' => function($data) {
                        return \common\models\Partner::findOne($data->partner_id)->name;
                    }
                ],
                'spu_id',
                'name',
                'create_time',
                [
                    'label' => '操作',
                    'format' => 'raw',
                    'value' => function($data) {
                        $html = Html::a($data->status ? '下架' : '上架', ['update-status', 'id' => $data->id], ['style' => 'padding:0 5px;']);
                        if ($data->status == 0) {
                            $html .= Html::a('编辑', ['update', 'id' => $data->id]);
                        }
                        return $html;
                    },
                ],
            ],
        ]); ?>
    </div>
</div>
