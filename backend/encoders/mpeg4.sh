#!/bin/bash
mencoder "$1" -quiet -ovc lavc -lavcopts vcodec=mpeg4 -vf scale -zoom -xy 0.5 -oac mp3lame -lameopts preset=standard -o "$2"

