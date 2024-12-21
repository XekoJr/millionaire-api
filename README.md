## Como correr?

1. Devem ter instalado o PHP e Composer
2. Façam clone/descarreguem o repositório
3. Abram o Visual Studio Code na pasta do projeto (Garantam que é mesmo a pasta do projeto, e não a pasta pai)
4. Abrir o terminal dentro do Visual Studio Code
5. Duplicar o ficheiro `.env.example` e mudar o nome para `.env`
6. Alterar os dados de ligação à base de dados no ficheiro `.env` (começam com `DB_`)
7. Correr a migração da base de dados para inicializar a mesma `php artisan migrate`
8. Executar o comando `composer run dev` para correr o programa

## Como testar?

Recomenda-se utilizar o [Postman](https://www.postman.com/downloads/), mas qualquer ferramenta que faça pedidos HTTP funciona igualmente 😁

Neste repositório está disponível um ficheiro `exemplo-jwt.postman_collection.json` que pode ser utilizado para importar os pedidos já preparados para o Postman.
