#!/bin/sh

cd /home/node/app/

yarn add @symfony/webpack-encore --dev
yarn install

exec "$@"