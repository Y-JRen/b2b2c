<?php

namespace backend\widgets;

use yii\helpers\Html;
use yii\base\Widget;

/**
 * Class MeTable meTables 的小部件信息
 * @package backend\widgets
 */
class MeTable extends  Widget
{
    /**
     * @var string 定义资源路径
     */
    public $resourcePath = '@web/adminlte/plugins/datatables/';

    /**
     * @var array 资源加载配置信息
     */
    public $resourceOptions = [
        'depends' => ['backend\assets\AppAsset']
    ];

    /**
     * @var array 按钮的配置
     */
    public $buttons = [];

    /**
     * @var array 表格的配置
     */
    public $table = [];

    /**
     * @var string 按钮容器目标
     */
    public $buttonsTemplate = '<p {options}></p>';

    /**
     * @var string 表格目标
     */
    public $tableTemplate = '<table {options}></table>';

    /**
     * @var array 定义表格默认的配置信息
     */
    private $defaultOptions = [
        'class' => 'table table-striped table-bordered table-hover',
        'id' => 'show-table'
    ];

    /**
     * @var array 默认按钮的配置
     */
    private $defaultButtons = [
        'id' => 'me-table-buttons',
    ];

    public function init()
    {
        parent::init();

        // 默认表格配置覆盖
        if ($this->table) {
            $this->defaultOptions = array_merge($this->defaultOptions, $this->table);
        }

        // 默认按钮配置覆盖
        if ($this->buttons) {
            $this->defaultButtons = array_merge($this->defaultButtons, $this->buttons);
        }

    }

    public function run()
    {
        // 注入指定的 js 和 css
        $view = $this->getView();
        $view->registerJsFile($this->resourcePath.'jquery.dataTables.min.js', $this->resourceOptions);
        $view->registerJsFile($this->resourcePath.'dataTables.bootstrap.min.js', $this->resourceOptions);
        $view->registerJsFile($this->resourcePath.'meTables.js', $this->resourceOptions);
        $view->registerCssFile($this->resourcePath.'dataTables.bootstrap.css', $this->resourceOptions);
        $view->registerJsFile($this->resourcePath.'jquery.validate.min.js', $this->resourceOptions);
        $view->registerJsFile($this->resourcePath.'validate.message.js', $this->resourceOptions);

        // 载入数据
        return str_replace('{options}', Html::renderTagAttributes($this->defaultButtons), $this->buttonsTemplate).
            str_replace('{options}', Html::renderTagAttributes($this->defaultOptions), $this->tableTemplate);
    }
}