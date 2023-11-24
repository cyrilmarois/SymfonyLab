<h1 align="center"><img src="https://upload.wikimedia.org/wikipedia/commons/9/98/International_Pok%C3%A9mon_logo.svg" alt="API Platform"></h1>

This project is using API Platform

It is fully dockerized, therefore there is no installation required (except for docker ;)).
I changed it to use MySQL but feel free to use any other database services

### Installation

-   clone the repo, and into the dir
-   docker compose build --no-cache
-   docker compose up -d --wait

To import pokemon csv file (assuming it's in public/imports)

-   docker compose exec php bin/console app:import:csv pokemon.csv

To change the default importer directory, take a look in services.yaml and change app.import_dir config var

## API

### Pokemons :

GET ALL http://localhost/api/v1/pokema.jsonld

### Pokemons filters :

BY NAME http://localhost/api/v1/pokema.jsonld?name=<$name>

$name : string, the search is partial meaning you can only enter a portion of the name

BY LEGENDARY http://localhost/api/v1/pokema.jsonld?legendary=<$legendary>

$legendary : bool

BY GENERATION http://localhost/api/v1/pokema.jsonld?generation[gt]=<$generation>

$generation: integer, the search is made by range, the keywords available are : lt, gt, lte, gte, between

BY TYPE http://localhost/api/v1/pokema.jsonld?types=<$type>

$type : integer

### PAGINATION

You can change the number of elements per page http://localhost/api/v1/pokema.jsonld?itemsPerPage=<$itemsPerPage>

$itemsPerPage: integer, default 50
