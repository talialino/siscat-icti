Sistema SISCAT 

Para baixar as dependecias inicias do projeto quando aberto pela primeira vez, é preciso rodar:

composer install --ignore-platform-req=ext-ldap


Em /var/www/siscat

Eu consigo acessar a tela principal, que está em siscat/web/index.php
http://localhost/siscat/web/

Erro: 
A página não é encontrada localemnte e o HTML não esta sendo gerado.

Outra forma de acessar a página: 
php yii serve localhost:8000 -t web/

Erro: 
session_start(): O arquivo de dados da sessão não foi criado pelo seu uid
 
Em /var/www/siscat

Esse é o projeto iniciado do zero com as pastas necessárias para inicialmente ter pelo menos o módulo do Sisape em execução. Porém, a estrutura de pastas criadas estão relacionadas com os módulos Sisai e Sisrh.

Erro:
Não é possível usar yii\base\Object como Object porque 'Object' é um nome de classe especial


