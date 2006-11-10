#!/bin/bash
mencoder "$1" -quiet -ovc lavc -lavcopts vcodec=mpeg4 -vf scale -zoom -xy 0.125 -oac mp3lame -lameopts cbr:br=64 -o "$2"

