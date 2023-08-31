## Local docker-compose deployment

1. Copy `.env.example` to `.env`
    - update necessary values
    - make sure that `DC_` port envs don't conflict with your existing docker ports.
```sh
cp .env.example .env
```

2. Start
```sh
./start_local.sh
```

3. Seed dummy data
```
php artisan db:seed --class=TestDatabaseSeeder
```

4. Health Check
```sh
curl localhost/api/health_check

# Output: {"status":"ok"}
```

## Postman
- [Collection](.docs/postman/postman_collection.json)
- [Environment](.docs/postman/postman_environment.json)
