#!/bin/bash
exec mencoder "$1" -quiet -ffourcc XVID -ovc lavc -lavcopts vcodec=mpeg4:vbitrate=400:autoaspect -vf scale -zoom -xy 0.25 -oac copy -o "$2"
