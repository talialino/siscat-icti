Sistema SISCAT 

Para baixar as dependecias inicias do projeto quando aberto pela primeira vez, é preciso rodar:

composer update --ignore-platform-reqs --no-plugins --with-all-dependencies

Depois: 
composer install --ignore-platform-reqs --no-plugins 

Se aparecer o erro "Root composer.json requires kartik-v/yii2-datecontrol dev-master..." altere no composer.json:

"kartik-v/yii2-krajee-base": "^1.9",
Para
"kartik-v/yii2-krajee-base": "^3.0.4",

Ou se aparecer qualquer outro erro de biblioteca desatualizada, altere a versão que ele sugerir no termnal para o arquivo composer.json.

Altere os dados do banco para os seu local em config/db.php. Faça essa alteração em todos os arquivos da pasta config que precisam das configurações do banco. (menos a pasta sisaidb.php)

O arquivo do banco para gerar a schema está na pasrta raiz desse projeto como siscat_db.sql.

Preencha todos os dados da tabela user do banco, como exemplo:

INSERT INTO `siscat`.`user` (`id`, `username`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `created_at`, `updated_at`, `flags`) VALUES ('2', 'talialino', 'talia@lino', '123', '123', '123', 'talia@lino', '8092023', '8092023', '1');

Acessando página:

Aqui estamos considerando que sua máquina está devidamente configurada com um LEMP ou um LAMP, ambiente e programas necessários instalados e configurados.

Acesso pelo servidor PHP:

php yii serve localhost:8000 -t web/
ou
php -S localhost:8000 -t web/

Aqui precisa aparecer a tela inicial do projeto.

Acesso local: 

Em /var/www/siscat-icti

Eu consigo acessar a tela principal, que está em siscat-icti/web/index.php
http://localhost/siscat-icti/web/

Possíveis erros não corrigidos:

Erro: 
A página não é encontrada localemnte e o HTML não esta sendo gerado.

Erro: 
session_start(): O arquivo de dados da sessão não foi criado pelo seu uid

Erro:
Não é possível usar yii\base\Object como Object porque 'Object' é um nome de classe especial


