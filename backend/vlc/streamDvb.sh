#!/bin/sh

# HTTP streaming of all video channels (Czech Republic DVB)
LANG=C LC_ALL=C exec vlc -v --color \
dvb: --dvb-frequency=674000000 --dvb-bandwidth=8 \
--ts-es-id-pid --programs="1,3,4,5,7" \
--sout "#duplicate{"\
"dst=std{access=http,mux=ps,url=localhost:1234/prima},select=\"program=1\","\
"dst=std{access=http,mux=ps,url=localhost:1234/nova},select=\"program=3\","\
"dst=std{access=http,mux=ps,url=localhost:1234/ct1},select=\"program=4\","\
"dst=std{access=http,mux=ps,url=localhost:1234/ct2},select=\"program=5\","\
"dst=std{access=http,mux=ps,url=localhost:1234/ct24},select=\"program=7\""\
"}" "#@"

# Programs:
# 1...Prima
# 2...TOP TV
# 3...NOVA
# 4...CT1
# 5...CT2
# 6...CRo2
# 7...CT24
# 8...CRo1
# 9...Proglas
#10...Evropa2
#11...Expres Radio
#11...Classic FM

#-----------------------------------------------------------------
# Other examples
#-----------------------------------------------------------------
# -d ... run as daemon
# --logfile filename ... log the daemon output

# File streaming
#once1.avi \
#--sout "#std{access=http,mux=ps,url=localhost:1234}"

# Single DVB PS streaming
#dvb: --dvb-frequency=674000000 --dvb-bandwidth=8 \
#--program="7" \
#--sout "#std{access=http,mux=ps,url=localhost:1234}"

# Multiple DVB windows
#dvb: --dvb-frequency=674000000 --dvb-bandwidth=8 \
#--sout-all \
#--sout "#duplicate{"\
#"dst=display,select=\"program=7\","\
#"dst=display,select=\"program=1\""\
#"}"

