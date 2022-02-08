#!/bin/sh
# vytvori ftp ucty

FTP_ACCOUNT_DIR=/etc/vsftpd/
FTP_ACCOUNT_DB=${FTP_ACCOUNT_DIR}accounts.db
FTP_ROOT=/grab/users/

TVGRAB_USER=tvgrab
TVGRAB_PASS=tvgrab


FTP_ACCOUNT_TMP=${FTP_ACCOUNT_DIR}accounts.tmp

touch $FTP_ACCOUNT_TMP
chmod 600 $FTP_ACCOUNT_TMP
FTP_ACCOUNT_TMP=$FTP_ACCOUNT_TMP TVGRAB_USER=$TVGRAB_USER TVGRAB_PASS=$TVGRAB_PASS ./get_user_account.php
db3_load -T -t hash -f $FTP_ACCOUNT_TMP $FTP_ACCOUNT_DB
chmod 600 $FTP_ACCOUNT_DB

FTP_ACCOUNTS=`awk 'BEGIN {odd=1};/.*/{if(odd==1) {print $0;odd=0;} else odd=1}' $FTP_ACCOUNT_TMP`
for user in $FTP_ACCOUNTS; do

	# nastaveni ftp rootu a nazvu ftp adresare pro uzivatele
	if [ "x$user" = "x$TVGRAB_USER" ]; then
		LOCAL_ROOT=$FTP_ROOT
		FTP_USER_DIR=${FTP_ROOT}all
	else
		LOCAL_ROOT=${FTP_ROOT}$user
		FTP_USER_DIR=$LOCAL_ROOT
	fi

	# vytvoreni ftp uctu
	if [ ! -f ${FTP_ACCOUNT_DIR}users/$user ]; then
		cat > ${FTP_ACCOUNT_DIR}users/$user <<EOF
dirlist_enable=YES
download_enable=YES
local_root=$LOCAL_ROOT
EOF
	fi

	# vytvoreni ftp adresare
	if [ ! -d $FTP_USER_DIR ]; then
		mkdir $FTP_USER_DIR
		chown fidlej:nogroup $FTP_USER_DIR
	fi
done

#smaz vsechny neplatne ftp ucty
for account in `ls ${FTP_ACCOUNT_DIR}users/`; do
	if ! echo $FTP_ACCOUNTS | grep -q $account; then
		rm -f ${FTP_ACCOUNT_DIR}users/$account
		rm -rf ${FTP_ROOT}$user
	fi
done
