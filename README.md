
### Instruções de instalação

Baixe o repositorio. 
`git clone https://github.com/eudesaraujo/nofaro_app.git`

Copie o .env.example para .env 

Preencha as seguintes varáveis de ambiente com as conexões do seu banco de dados.

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nofaro_app
    DB_USERNAME=root
    DB_PASSWORD=



Roder os seguintes comandos.



`composer install`



`php artisan key:generate`



Criar as tabelas:
`php artisan migrate`




Executar:
`php artisan serve`



### Instruções de uso



#### Rotas disponíveis:



**Limite de requisições por minuto:** 10;



Criar Hash com prefixo inicial de 4 zeros e uma key aleátoria.



POST http://127.0.0.1:8000/api/hash/generate
Parâmetros:

|  Parâmetro | Descrição  | Tipo | Obrigátorio|
| ------------ | ------------ | ------------ | ------------ |
|  input |   String de entrada para gerar a hash | string | Sim




Listagens das Hashs geradas.



GET http://127.0.0.1:8000/api/hash/listing


Parâmetros:

|  Parâmetro | Descrição  | Tipo |Obrigátorio|
| ------------ | ------------ | ------------ | ------------ |
|  limit |   Limite de registro retornados Maxímo de 100. | Inteiro |Não
| offset  |  Inicio da leitura dos registros | Inteiro | Não
| attempts_less_than  |  Os registros retornados serão oque tiverem menor tentativas do que o número informado nesse parâmetro | Inteiro | Não


#### Comandos disponíveis

- Teste da rota api/hash/generate passando a quantidade de requisições que serão feitas.

`php artisan nofaro:test {string} --requests={inteiro}`

- Exemplo:

`php artisan nofaro:test "nafora" --requests=10`


#### Explicação da solução implantada.

**1) Rota para geração do hash**
Utilizei as funcões do PHP random_bytes e bin2hex para gerar Key.
Ref: https://www.php.net/manual/pt_BR/function.random-bytes.php

Montei um laço de repetição no qual gerar uma nova key até encontrar uma na qual concatenando com a string resulte em na hash que inicie com 4 zeros.

Após encontrar a hash e key salvo no banco de dados utilizando uma model.

**2) Comando para consulta da rota**
Criei o comando utilizando o recurso do laravel `php artisan make:command {nome_do_comando}`
E com base no que foi solicitado criei um lanço de repetição limitando a quantidade de repetições informadas no parâmetro requests, e cada vez que o laço roda faz uma requesição para rota /api/hash/generate.
Adicionei um delay para cada requisição para evitar Too Many Attempts.
Mesmo assim caso a API retorne Attempts existe uma outra tratative na qual faz o string aguarda 1 minuto.

**3) Rota de retorno dos resultados**
Foi criado um rota para listar os hashs gerados.
Limitado por padrão a 100 registros por vez.
Adicionado a possibilidade de paginação com os parâmetros **offset** e **limit**.
Pesquisar por menor tentativa utilizando o parametro.
Query montanda com eloquent na Model Hash.






