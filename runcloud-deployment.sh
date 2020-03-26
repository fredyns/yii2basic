#!/bin/bash

git pull origin master
composer install

./yii migrate --interactive=0 --migration-path=@vendor/dektrium/yii2-user/migrations
./yii migrate --interactive=0 --migration-path=@mdm/upload/migrations
./yii migrate --interactive=0