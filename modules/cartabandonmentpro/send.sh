#!/bin/sh

DIR_MODULE=$(dirname "${0}");
ID_SHOP=$1;
TOKEN_SEND=$2;
WICH_REMIND=$3;

cd $DIR_MODULE;
php -f send.php $ID_SHOP $TOKEN_SEND $WICH_REMIND;

exit 0;