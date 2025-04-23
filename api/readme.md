# Commandes

## Run App
```
flask --app api.app run
```

## Migration init
```
flask --app api.app db init -d ./api/migrations
```
## Make migration
```
flask --app api.app db migrate -m "MESSAGE" -d ./api/migrations
```
## Run migration
```
flask --app api.app db upgrade -d ./api/migrations
```