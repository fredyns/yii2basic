#!/bin/bash

echo "==============================="
echo "change ownership as yours"
echo "add group www-data"
echo "change permission to 775"
sudo echo "==============================="

for foldername in ./*; do
    if [ $foldername = './vendor' ]
    then
        echo "skipping `$foldername`"
        continue
    fi

    echo "configuring `$foldername`"
    sudo chown -R $USER:www-data $foldername
    sudo chmod -R 775 $foldername  
done

echo "==============================="
echo "done."
echo "==============================="
