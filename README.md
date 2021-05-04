
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

Roder composer para instalar as dependencias.
`composer install`

Criar as tabelas:
`php artisan migrate`

### Instruções de uso

#### Rotas disponíveis:

Criar Hash com prefixo inicial de 4 zeros e uma key aleátoria.

POST http://127.0.0.1:8000/api/hash/generate
Parâmetros:

|  Parâmetro | Descrição  | Tipo | Obrigátorio|
| ------------ | ------------ |
|  input |   String de entrada para gerar a hash | string | Sim

Listagens das Hashs geradas.

GET http://127.0.0.1:8000/api/hash/listing
Parâmetros:

|  Parâmetro | Descrição  | Tipo |Obrigátorio|
| ------------ | ------------ |
|  limit |   Limite de registro retornados Maxímo de 100. | Inteiro |Não
| offset  |  Inicio da leitura dos registros | Inteiro | Não
| attempts_less_than  |  Número de tentativas maximo que um registro vez | Inteiro | Não
