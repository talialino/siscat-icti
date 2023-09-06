$('.situacaoCores').each(function(){
    switch ($(this).text()) {
        case 'Programa não preenchido':
        case 'Programa necessita de correções de acordo com parecerista do colegiado':
        case 'Programa necessita de correções de acordo com parecerista da CAC':
            $(this).addClass('situacao3');
        break;
    }
})


