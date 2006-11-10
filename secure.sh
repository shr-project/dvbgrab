#!/bin/sh

if [ -f config.php ]; then
    chmod 644 config.php 
fi

chmod 600 setup.php

echo "Dvbgrab configuration files are private now."
echo "If you wish to change configureation execute configure.sh."
echo ""
