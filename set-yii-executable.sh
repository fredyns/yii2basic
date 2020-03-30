#!/bin/bash

# list all file's permission
git ls-files --stage

# edit permission
git update-index --chmod=+x '../yii'


git commit -m "made yii executable" 
git push