<?php

use kartik\detail\DetailView;

echo DetailView::widget([
        'model' => $model,
        'panel' => ['type' => 'primary', 'heading' => $this->title],
        'enableEditMode' => false,
        'attributes' => [
            ['label'=>'Nome', 'attribute'=>'ligaNome'],
            ['label'=>'Vigência', 'attribute'=>'situacaoLigaString'],
            'atividades:ntext',
            'prestacao_contas:ntext',
            'consideracoes_finais:ntext',
            [
                'columns' => [
                    'data_inicio:date',
                    'data_fim:date',
                ]
            ],
            [
                'columns' => [
                    'data_aprovacao_comissao:date',
                    'data_homologacao_congregacao:date',
                ]
            ], 
            'sessaoCongregacao',                           
            'situacaoString',
        ],
    ]);?>