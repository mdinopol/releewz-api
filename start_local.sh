#!/bin/bash

function error { echo -e "\033[31mError: $1" >&2; }
function version { echo "$@" | awk -F. '{ printf("%d%03d%03d%03d\n", $1,$2,$3,$4); }'; }

# Check .env
if [ -f .env ]
then
    export $(cat .env | sed 's/#.*//g' | xargs)
else
    echo $(error '.env not found')
    exit 1
fi

# Check docker-compose must be version 2.1 or higher
DOCKER_COMPOSE_MIN_VER=2.1
DOCKER_COMPOSE_VER=$(docker-compose --version | cut -d'v' -f3)
if [ $(version $DOCKER_COMPOSE_VER) -lt $(version $DOCKER_COMPOSE_MIN_VER) ]; then
    echo $(error "docker-compose version must be $DOCKER_COMPOSE_MIN_VER or higher; you have $DOCKER_COMPOSE_VER")
    exit 1
fi

if [[ $APP_ENV == 'local' ]];
then
    if command -v cp &> /dev/null
    then
        cp -rp .github/scripts/pre-push.sh .git/hooks/pre-push
        chmod ug+x .git/hooks/pre-push
    fi

    docker-compose up -d --build --wait --remove-orphans && \
        docker-compose exec mysql mysql -uroot -e "CREATE DATABASE IF NOT EXISTS ${DB_DATABASE}" && \
        docker-compose exec mysql mysql -uroot -e "CREATE DATABASE IF NOT EXISTS ${DB_DATABASE}_test" && \
        docker-compose exec mysql-log mysql -uroot -e "CREATE DATABASE IF NOT EXISTS ${LOG_DB_DATABASE}" && \
        docker-compose exec api composer install --optimize-autoloader --no-interaction && \
        docker-compose exec api php artisan ide-helper:generate && \
        docker-compose exec api php artisan ide-helper:meta && \

        # Create s3 public bucket
        docker run --rm --link releewz-s3:minio --network releewz --entrypoint sh minio/mc -c "\
          mc config host add s3 http://s3:9000 ${AWS_ACCESS_KEY_ID} ${AWS_SECRET_ACCESS_KEY}; \
          mc mb -p s3/${AWS_BUCKET}; \
          mc policy set public s3/${AWS_BUCKET}; \
          exit 0; \
        "
else
    docker-compose up -d api --build --no-recreate --wait --remove-orphans --rm && \
        docker-compose exec api composer install --optimize-autoloader --no-interaction --no-progress --no-dev
fi

docker-compose exec api php artisan key:generate --ansi && \
    docker-compose exec api php artisan passport:keys -q -n 2>/dev/null || true && \
    docker-compose exec api php artisan migrate && \
    docker-compose exec api php artisan db:seed
