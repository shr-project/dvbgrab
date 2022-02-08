#!/bin/bash
exec mencoder "$1" -quiet -ffourcc XVID -ovc lavc -lavcopts vcodec=mpeg4:vbitrate=1200:autoaspect -oac copy -o "$2"
