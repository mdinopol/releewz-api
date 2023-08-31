#!/bin/sh
exec < /dev/tty

# Stash un-committed files including un-indexed
git stash -q -u

# PHP-CS-FIXER
echo 'Running php-cs-fixer...'
docker-compose run --rm --no-deps api vendor/bin/php-cs-fixer fix --dry-run --stop-on-violation --quiet
if [ $? -ne 0 ]; then
  docker-compose run --rm --no-deps api vendor/bin/php-cs-fixer fix && \
    git add . && \
    git commit -m "Apply code style changes"

    # Pop back
    git stash pop -q

    echo "\n\033[31m!!Push rejected because of an added commit to apply code style changes. Run \`git push\` again!!\n"
    exit 1
else
  echo 'passed'
fi

# PHP STAN
echo 'Running phpstan...'
docker-compose run --rm --no-deps api vendor/bin/phpstan
RESULT=$?

# Pop back
git stash pop -q

[ $RESULT -ne 0 ] && exit 1
echo 'passed'
