<?php

use app\assets\SisaiAsset;
use app\modules\sisai\models\SisaiAluno;
use app\modules\sisrh\models\SisrhSetor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
SisaiAsset::register($this);


/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Atualizar Tabela Aluno');
$this->params['breadcrumbs'][] = ['label' => strtoupper(Yii::t('app', 'sisai')), 'url' => ['/'.strtolower(Yii::t('app', 'sisai'))]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sisai-atualizar-aluno">

    <h1 class="cabecalho"><?= Html::encode($this->title) ?></h1>

    <div class="mensagem-inicial">Após atualizar BD, lembrar de colocar o(s) colegiado(s) na lista de liberados para ATUV em Colegiados para ATUV.</div>
    <div class="sisrh-cargo-form">

        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'],]); ?>
        <div class='col-md-12 tabs-conteudo' style="border: 1px solid #e2e2e2">
            <div class='col-md-12'>
                <?= $form->field($model, 'arquivo',['options' => ['class' => 'col-md-4']])->fileInput() ?>
            </div>

            <div class="form-group col-md-12">
                <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="clearfix"></div>
    </div>
    <div>
        <strong>Formato de arquivo de graduação</strong> (ver códigos no final da página)
        <pre>&lt;id_setor[-1]&gt;
matricula - nome_da_pessoa ano_entrada-semestre_letivo
.
.
.
&lt;id_setor[-1]&gt; 
matricula - nome_da_pessoa ano_entrada-semestre_letivo
.
.
.
        </pre>
        <strong>Formato de arquivo de pós-graduação</strong> (ver códigos no final da página)
        <pre>&lt;id_setor-nivel_escolaridade&gt; 
nome_da_pessoa matricula ano_entrada.semestre_letivo
.
.
.
&lt;id_setor-nivel_escolaridade&gt; 
nome_da_pessoa matricula ano_entrada.semestre_letivo
.
.
.
        </pre>
        <strong>Exemplo do formato de arquivo para graduação:</strong>
        <pre>&lt;11&gt;
219122988 - ALEXANDRE ARAUJO MATOS 2019-1
218120422 - ALINA GOES SILVA 2018-1
221119918 - ALISSON MEIRELES FLORES 2021-1
220116633 - AMANDA KELLE SANTOS NOVAES 2020-1
221116810 - ANA JULIA CORREA DA CRUZ 2021-1
221119917 - ANDRE VICENTE DOS SANTOS PIRES 2021-1
214004444 - Andrea Reis Santos Cerveira 2014-1
221116801 - ANGELO GABRIEL GOUVEIA SILVA 2021-1
219121021 - BEATRIZ GEORGIA DA SILVA LOPES 2019-1
220120456 - Beatriz Santos Machado 2020-1
219121035 - BIANCA SOUZA MARTINS 2019-1
217116986 - Breno Magalhaes Neves 2017-1
216117004 - BRUNO BARRETO MENDONÇA 2016-1
219122989 - BRUNO PAIVA DA SILVA 2019-1
219121010 - CAIO FERRAZ CABRAL DE ARAUJO 2019-1
221116809 - CARLOS ALBERTO MAFRA OLIVEIRA NETO 2021-1
219117516 - CARLOS DANIEL DE SOUSA ROCHA 2019-1
201518564 - Carolina Galdino Souza Andrade 2015-1
        </pre>

        <strong>Exemplo do formato de arquivo para pós-graduação:</strong>
        <pre>&lt;18-4&gt;
ANNE KAROLINE PEREIRA BRITO 218221980 2018.2
DÉBORAH CRUZ DOS SANTOS 218125417 2018.1
LAIS FERRAZ BRITO SOUSA 217126647 2017.1
MAIARA RAULINA DE JESUS DIAS 2020120485 2020.1
AMANDA ALVES DE ALMEIDA 2020126095 2020.1
ANNA CAROLINA SAUDE DANTAS 2020126077 2020.1
BRENDA OLIVEIRA LIMA 2021120149 2021.1
        </pre>
        <strong>Exemplo do formato de arquivo contendo os dois formatos juntos:</strong>
        <pre>&lt;11&gt;
219122988 - ALEXANDRE ARAUJO MATOS 2019-1
218120422 - ALINA GOES SILVA 2018-1
221119918 - ALISSON MEIRELES FLORES 2021-1
220116633 - AMANDA KELLE SANTOS NOVAES 2020-1
221116810 - ANA JULIA CORREA DA CRUZ 2021-1
221119917 - ANDRE VICENTE DOS SANTOS PIRES 2021-1
214004444 - Andrea Reis Santos Cerveira 2014-1
221116801 - ANGELO GABRIEL GOUVEIA SILVA 2021-1
219121021 - BEATRIZ GEORGIA DA SILVA LOPES 2019-1
220120456 - Beatriz Santos Machado 2020-1
219121035 - BIANCA SOUZA MARTINS 2019-1
217116986 - Breno Magalhaes Neves 2017-1
216117004 - BRUNO BARRETO MENDONÇA 2016-1
219122989 - BRUNO PAIVA DA SILVA 2019-1
219121010 - CAIO FERRAZ CABRAL DE ARAUJO 2019-1
221116809 - CARLOS ALBERTO MAFRA OLIVEIRA NETO 2021-1
219117516 - CARLOS DANIEL DE SOUSA ROCHA 2019-1
201518564 - Carolina Galdino Souza Andrade 2015-1
&lt;18-4&gt;
ANNE KAROLINE PEREIRA BRITO 218221980 2018.2
DÉBORAH CRUZ DOS SANTOS 218125417 2018.1
LAIS FERRAZ BRITO SOUSA 217126647 2017.1
MAIARA RAULINA DE JESUS DIAS 2020120485 2020.1
AMANDA ALVES DE ALMEIDA 2020126095 2020.1
ANNA CAROLINA SAUDE DANTAS 2020126077 2020.1
BRENDA OLIVEIRA LIMA 2021120149 2021.1
        </pre>
    </div>
</div>
<table border="solid 1px black">
    <thead>
        <tr><th colspan="2" style="text-align: center;">Colegiados</th></tr>
        <tr><th>id_setor</th><th>nome</th></tr>
    </thead>
    <tbody>
<?php
    $colegiados = SisrhSetor::find()->select('id_setor, nome')->where('eh_colegiado = 1')->all();
    foreach($colegiados as $colegiado)
        echo "<tr><td>$colegiado->id_setor</td><td>$colegiado->nome</td></tr>";
?>
    </tbody>
</table>
<br/>
<table border="solid 1px black">
    <thead>
        <tr><th colspan="2" style="text-align: center;">Escolaridade</th></tr>
        <tr><th>nivel_escolaridade</th><th>descrição</th></tr>
    </thead>
    <tbody>
<?php
    foreach(SisaiAluno::ESCOLARIDADE as $key => $value)
        echo "<tr><td>$key</td><td>$value</td></tr>";
?>
    </tbody>
</table>