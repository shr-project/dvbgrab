#!/bin/sh
# Converts .mpg to .mpg.
# It does so by creating hardlink.

if test $# -ne 2 ; then
    echo "usage: $0 grab.mpg new_file.mpg" >&2
    exit 1
fi

if test "$1" != "$2" ; then
    ln "$1" "$2"
fi

