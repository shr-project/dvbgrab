#!/bin/sh

if [ -f config.php ]; then
    chmod 444 config.php 
fi

chmod 400 setup.php

echo "Dvbgrab configuration files are private now."
echo "If you wish to change configureation execute configure.sh."
echo ""
