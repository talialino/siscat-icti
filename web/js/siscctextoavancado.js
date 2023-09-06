function onkeyevent(editor){
    editor.on('keyup', function(e) {
        var elemento = $(this.selection.getNode());
        var pai = elemento.parent();

        if(elemento.is('li') && !pai.is('ul'))
        {
            if(elemento.prev().is('ul') && elemento.next().is('ul'))
            {
                prev = elemento.prev();
                next = elemento.next();
                prev.append(elemento);
                next.children('li').appendTo(prev);
                next.remove();
            }
            else if(elemento.prev().is('ul'))
            {
                elemento.prev().append(elemento);
            }
            else if(elemento.next().is('ul'))
            {
                elemento.next().prepend(elemento);
            }
            else{
                ul = $('<ul></ul>');
                pai.append(ul);
                ul.append(elemento);
            }
        }
        else{
            if(elemento.is('body'))
            {
                text = elemento.html();

                elemento.html('<ul><li>' + text + '</li></ul>');
            }
        }

        if(e.keyCode == 86 && elemento.has('br').length)
        {
            html = elemento.html().split('<br>');
            if(html.length > 0){
                elemento.html(html[0]);
                temp = new Array();
                for(i = 1; i < html.length; i++)
                    temp[i - 1] = '<li>' + html[i] + '</li>';
                elemento.after(temp);
            }
        }
    });
/*    editor.on('focusout', function(e){
        body = $(e.currentTarget);
        
        if(!body.is('body')){
            return;
        }

        ul = body.children('ul');

        if(ul.find("li").has('ul').length > 0)
            ul.addClass('multinivel');
        else
            ul.removeClass("multinivel");

        console.log(body.html());

    });*/
}