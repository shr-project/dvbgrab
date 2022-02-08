#!/bin/bash
exec mencoder "$1" -quiet -ffourcc XVID -ovc lavc -lavcopts vcodec=mpeg4:vbitrate=800:autoaspect -vf scale -zoom -xy 0.5 -oac copy -o "$2"

