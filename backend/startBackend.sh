#!/bin/sh
killall /usr/bin/php
killall java

./grab_loop.php &
#./encode_loop.php &

encoding/activemq/bin/activemq &

cd encoding/jmServer/
./runReportProcess.sh &
./runWorkerProcess.sh &

