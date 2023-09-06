<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use app\assets\SisrhAsset;

SisrhAsset::register($this);

$this->title = 'Relatório de Ocorrências';
$this->params['breadcrumbs'][] = ['label' => 'SISRH', 'url' => ['/sisrh']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sisrh-afastamento-index">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
<?php ?>

    <div class="table-responsive">
        <?= GridView::widget([
            'panel' => [
                'type' => 'primary',
                'heading' => 'Ocorrências',
            ],
            'toolbar' =>  [
                '{export}',
            ],
                'dataProvider' => $dataProvider,
                'columns' => [
                    'pessoa.siape',
                    'pessoa.nome',
                    'cargo.descricao',
                    'pessoa.dt_ingresso_orgao:date',
                    'pessoa.jornada',
                    'ocorrencia.justificativa',
                    'inicio:date',
                    'termino:date',
                ],
                'emptyText' => 'Não há resultados para essa pesquisa',
            ]); ?>
    </div>
</div>

