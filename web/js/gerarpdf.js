
const { jsPDF } = window.jspdf;


const doc = new jsPDF('landscape');

function gerarPDF(link)
{
    link.remove();
    var img = new Image()
    img.src = '/siscat/images/brasao_ufba.jpg';
    
    doc.addImage(img,'JPEG',10,10);
    doc.setFontSize(15);
    doc.text(['Universidade Federal da Bahia - Campus Anísio Teixeira',
        'Instituto Multidisciplinar em Saúde','SISAI - Sistema de Avaliação Institucional'],25,15);
    doc.line(10,31,287,31);
    
    document.getElementsByClassName("kv-grid-table")[0].style.backgroundColor = 'white';

    const tamanho = document.getElementById("containerTabela").style.width;

    document.getElementById("containerTabela").style.width = '1365px';

    const online = document.getElementById('online');
    const estagio = document.getElementById('estagio');
    const antigo = document.getElementById('antigo');
    const containerTabela = document.getElementById("containerTabela").innerHTML;

    if(online)
    {
        var trs = document.getElementsByTagName('tr');
        for (var i = 0; i < trs.length; i++) {
            if(trs[i].getAttribute('data-key') >= 10)
            {
                trs[i].remove();
                i--;
            }
        }
        document.getElementsByClassName("kv-page-summary-container")[0].remove();
        document.getElementById('copiaTabelaOnline').innerHTML = containerTabela;
        var trs2 = document.getElementById('copiaTabelaOnline').getElementsByTagName('tr');
        for (var i = 0; i < trs2.length; i++) {
            if(trs2[i].getAttribute('data-key') && trs2[i].getAttribute('data-key') < 10)
            {
                trs2[i].remove();
                i--;
            }
        }
        html2canvas(document.getElementById("containerTabela")).then(function(canvas) {
        
        
            const image = canvas.toDataURL('image/jpeg',1.0);
            
            doc.addImage(image,'JPEG',10,40,277,144);
            doc.addPage();
            
        });
        document.getElementById("copiaTabelaOnline").style.width = '1365px';
        html2canvas(document.getElementById("copiaTabelaOnline")).then(function(canvas2) {
            const image2 = canvas2.toDataURL('image/jpeg',1.0);
        
            doc.addImage(image2,'JPEG',10,30,277,144);
            
            doc.setFontSize(8);
            doc.setFont('helvetica','bold');
            doc.line(10,190,287,190);
            doc.text(online.innerText,10,193);
    
            const ctx = document.getElementById("grafico").getContext('2d');
            ctx.save();
            ctx.globalCompositeOperation = 'destination-over';
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, document.getElementById("grafico").width, document.getElementById("grafico").height);
            
            ctx.restore();
            const imagem = document.getElementById("grafico").toDataURL('image/jpeg',1.0);
            doc.addPage();
            doc.addImage(imagem,'JPEG', 10,40,277,144);
            doc.line(10,190,287,190);
            
            doc.text(document.getElementById('dataRelatorio').innerText,243,193);
            doc.save('avaliacao.pdf');
            
        });
        document.getElementById('copiaTabelaOnline').remove();
        document.getElementById('containerTabela').innerHTML = containerTabela;
    }
    else
        html2canvas(document.getElementById("containerTabela")).then(function(canvas) {
            
            
            const image = canvas.toDataURL('image/jpeg',1.0);
            
            doc.addImage(image,'JPEG',10,40,277,144);
            
            doc.setFontSize(8);
            doc.setFont('helvetica','bold');

            if(estagio)
            {
                doc.line(10,190,287,190);
                doc.text(estagio.innerText,10,193);
            }

            if(antigo)
            {
                doc.line(10,190,287,190);
                doc.text(antigo.innerText,10,193);
            }

            const ctx = document.getElementById("grafico").getContext('2d');
            ctx.save();
            ctx.globalCompositeOperation = 'destination-over';
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, document.getElementById("grafico").width, document.getElementById("grafico").height);
            
            ctx.restore();
            const imagem = document.getElementById("grafico").toDataURL('image/jpeg',1.0);
            doc.addPage();
            doc.addImage(imagem,'JPEG', 10,40,277,144);
            doc.line(10,190,287,190);
            
            doc.text(document.getElementById('dataRelatorio').innerText,243,193);
            doc.save('avaliacao.pdf');
        });

    document.getElementById("containerTabela").style.width = tamanho;
}

