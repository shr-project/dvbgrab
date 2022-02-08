#!/bin/bash

# configuration:
frequency=506000000
programs=(1 2 5)
prgNames=(ct1 ct2 nova)

# implementation:

commaList=""
for prg in ${programs[@]}; do
    commaList="$commaList,$prg"
done
commaList="${commaList:1}"

output=""
for (( i = 0 ; i < ${#programs[@]} ; i++ )) ; do
    output="$output,dst=std{access=http{mime=video/mpeg},mux=ps,url=localhost:1234/${prgNames[$i]}},select=\"program=${programs[$i]}\""
done
output="${output:1}"

# HTTP streaming of all video channels (Czech Republic DVB)
LANG=C LC_ALL=C exec vlc -v --color \
dvb: --dvb-frequency="$frequency" --dvb-bandwidth=8 \
--ts-es-id-pid --programs="$commaList" \
--sout "#duplicate{$output}" "$@"

#-----------------------------------------------------------------
# Other examples
#-----------------------------------------------------------------
# -d ... run as daemon
# --extraintf logger --logfile filename ... log the daemon output

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

