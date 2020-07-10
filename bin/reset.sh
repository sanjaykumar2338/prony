#!/bin/bash

set -x
set -e

echo "Remove all doctrine migrations, database and images"
bin/console doctrine:database:drop --if-exists --force -q
# Ignore errors for ES index drop, it might be not there yet
set +e
bin/console ongr:es:index:drop -n --force -q
set -e

rm -fr ./src/Migrations/*
rm -fr ./public/upload/media/*

bin/console ongr:es:index:create -n
echo "Create database, generate first migration and run it"
bin/console doctrine:database:create --if-not-exists -q
bin/console doctrine:schema:create -q
#bin/console doctrine:migrations:diff -vvv
#bin/console doctrine:migrations:migrate -n -vvv
bin/console doctrine:fixtures:load -n