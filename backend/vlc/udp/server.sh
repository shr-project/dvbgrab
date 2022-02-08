#!/bin/sh
# VLC Server to stream given MPEG file.

if test $# -eq 0 ; then
    echo "Usage $0 file_to_stream.mpg"
    exit 1
fi

LANG=C LC_ALL=C exec vlc -v --color "$@" \
--sout '#std{access=udp{ttl=1},mux=ts,url=239.194.10.11:1234,sap,name=TestStream}'

# UDP with SAP announces
#--sout '#std{access=udp{ttl=1},mux=ts,url=239.194.10.11:1234,sap,name=TestStream}'
# Client:
#$ vlc udp:@239.194.10.11:1234
# Save to file
#--sout "#std{access=file,mux=ps,url=/tmp/test.mpg}"

# HTTP
#--sout "#std{access=http{mime=video/mpeg},mux=ps,url=localhost:1234}"


