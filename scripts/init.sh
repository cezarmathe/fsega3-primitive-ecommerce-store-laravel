#!/usr/bin/env bash

set -euo pipefail

if [[ ! -f "/etc/php/conf.d/postgresql.ini" ]]; then
    @ echo "Installing PostgreSQL extension. You may be asked for your password."
    sudo cp config/etc/php/conf.d/postgresql.ini /etc/php/conf.d/postgresql.ini
else
    echo "PostgreSQL extension already installed"
fi

if [[ ! -f '.env' ]]; then
    cp .env.example .env
else
    echo ".env file exists already, not overwriting"
fi

if [[ ! -f '.env' ]]; then
    php artisan key:generate
elif [[ "$(cat .env | grep APP_KEY | cut -d '=' -f 2)" == "" ]]; then
    php artisan key:generate
else
    echo "APP_KEY already set, not overwriting"
fi
