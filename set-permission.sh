#!/bin/bash

echo "==============================="
echo "change ownership as yours"
echo "add group www-data"
echo "change permission to 775"
sudo echo "==============================="

    echo "configuring `yii`"
    sudo chown $USER:www-data yii
    sudo chmod 775 yii  
    echo "==============================="

    echo "configuring `runtime`"
    sudo chown -R $USER:www-data runtime
    sudo chmod -R 775 runtime  
    echo "==============================="

    echo "configuring `web/assets`"
    sudo chown -R $USER:www-data web/assets
    sudo chmod -R 775 web/assets  
    echo "==============================="

echo "done."
echo "==============================="
