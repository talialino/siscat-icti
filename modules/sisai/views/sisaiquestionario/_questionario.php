<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\SisaiAsset;
use app\modules\sisai\models\SisaiAlunoRespostaObjetiva;
use app\modules\sisai\models\SisaiProfessorRespostaObjetiva;
use app\modules\sisai\models\SisaiTecnicoRespostaObjetiva;
use app\modules\sisrh\models\SisrhSetor;
use yii\db\conditions\InCondition;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

SisaiAsset::register($this);
/* @var $model app\modules\sisai\models\SisaiQuestionario */
$contador=0;

?>

<div class='questionario-form box box-info'>
    <div class='questionario-titulo box-header with-border'>
        <h3 class="box-title"></h3>
    </div>

    <?php $form = ActiveForm::begin(
        ['options' => ['class' => 'form']]
    ); ?>

    <div class='questionario-questoes box-body'>
        <?php if($model->avaliacao != null && $model->tipoQuestionario == 18):?>
            <div class="mensagem-inicial">
                Neste questionário você poderá avaliar os colegiados individualmente. Ao avaliar um colegiado, esta tela irá aparecer novamente para que você possa avaliar outros colegiados.
                <br />Essa avaliação é opcional, caso não queira avaliar nenhum colegiado, ou já tenha avaliados todos que deseja, clique no botão <strong>Próxima Etapa</strong>.
                <?=Html::a('Próxima Etapa',['docente', 'pularavaliacaocolegiado' => 1],['class' => 'btn btn-warning btn-lg pull-right'])?>
                <div class="clearfix"></div>
            </div>
            
            <div class="clearfix"></div>
            <?php endif;?>
        <?php foreach($model->grupoPerguntas as $grupo):?>
            <?php if($grupo->titulo != null):?>
                <h4 class="titulo-grupo"><span><?=$grupo->titulo?></span></h4>
                <div class="corpo-grupo">
            <?php endif;?>
            <?php foreach($grupo->sisaiPerguntas as $pergunta):?>
                <div class='questionario-pergunta'>
                    <p><?=++$contador?>. <?=$pergunta->descricao?></p>
                </div>
                <div class='questionario-pergunta-resposta form-group'>
                    <?php switch($pergunta->tipo_pergunta){
                        case $pergunta::PADRAO: case $pergunta::OBJETIVA:
                            echo Html::radioList("pergunta_$pergunta->id_pergunta",isset($_POST["pergunta_$pergunta->id_pergunta"]) ?
                                $_POST["pergunta_$pergunta->id_pergunta"] : null, $pergunta->alternativas,
                                [
                                    'item' => function($index, $label, $name, $checked, $value) {
                                        $checked = $checked ? 'checked="1"' : '';
                                        $return = '<div class="checkbox"><label>';
                                        $return .= "<input type='radio' name='$name' value='$value' tabindex='3' $checked>";
                                        $return .= "<span>$label</span>";
                                        $return .= '</label></div>';

                                        return $return;
                                    }
                                ]
                        );
                        break;
                        case $pergunta::MULTIPLA_ESCOLHA:
                            echo Html::checkBoxList("pergunta_$pergunta->id_pergunta",isset($_POST["pergunta_$pergunta->id_pergunta"]) ?
                                $_POST["pergunta_$pergunta->id_pergunta"] : null, $pergunta->alternativas,
                                [
                                    'item' => function($index, $label, $name, $checked, $value) {
                                        $checked = $checked ? 'checked="1"' : '';
                                        $return = '<div class="checkbox"><label style="margin-left: 20px;">';
                                        $return .= "<input type='checkbox' name='$name' value='$value' tabindex='3' $checked>";
                                        $return .= "<span>$label</span>";
                                        $return .= '</label></div>';

                                        return $return;
                                    }
                                ]
                        );
                        break;
                        case $pergunta::ABERTA:
                            echo Html::textarea("pergunta_$pergunta->id_pergunta",isset($_POST["pergunta_$pergunta->id_pergunta"]) ?
                                $_POST["pergunta_$pergunta->id_pergunta"] : null, ['rows' => 5]);
                        break;
                        case $pergunta::COLEGIADO:
                            $colegiados = SisrhSetor::find()->where(['eh_colegiado' => 1]);
                            if($model->avaliacao != null)
                            {
                                $tabela = '';
                                switch($model->avaliacao->tipo_avaliacao)
                                {
                                    case 0:
                                        $tabela = SisaiAlunoRespostaObjetiva::tableName();
                                    break;
                                    case 1:
                                        $tabela = SisaiProfessorRespostaObjetiva::tableName();
                                    break;
                                    case 2:
                                        $tabela = SisaiTecnicoRespostaObjetiva::tableName();
                                    break;
                                }
                               $colegiados->andWhere(new Expression("id_setor NOT IN 
                                    (SELECT resposta FROM $tabela WHERE
                                    id_avaliacao={$model->avaliacao->id_avaliacao} AND id_pergunta = $pergunta->id_pergunta)"));
                            }
                            echo Html::dropDownList("pergunta_$pergunta->id_pergunta",isset($_POST["pergunta_$pergunta->id_pergunta"]) ?
                                $_POST["pergunta_$pergunta->id_pergunta"] : null,
                                ArrayHelper::map($colegiados->all(), 'id_setor','colegiado'),
                                   ['class' => 'form-control', 'prompt' => 'Selecione o colegiado']
                            );
                        break;
                    }?>
                </div>
            <?php endforeach;?>
            <?php if($grupo->titulo != null):?>
            </div>
            <?php endif;?>
        <?php endforeach;?>
        <?php if($model->avaliacao != null):?>
            <div class="form-group">
                <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
            </div>
        <?php endif;?>
    </div>
    <?php ActiveForm::end(); ?>

</div>