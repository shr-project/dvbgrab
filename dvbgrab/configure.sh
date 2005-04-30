#!/bin/sh

chmod 644 setup.php

if [ ! -f config.php ]; then
    touch config.php
fi

chmod 666 config.php

echo "Please setup dvbgrab with pointing browser to setup."
echo "Then make configfiles private by executing secure.sh"
echo ""
