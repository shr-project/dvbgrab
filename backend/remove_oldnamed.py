#!/usr/bin/env python
USAGE="Usage: %s required_space_in_MB pattern..."
"""
Removes the oldest-named files.
The files to remove are specified by one or more pattern masks.

Example:
$ ./remove_oldest.py 2048 'users/*/DVB-*.avi' 'users/*/DVB-*.mpg'
"""

import sys
import os
import os.path
import commands
import glob

def getFreeSpace(path):
    """ Returns free disk space at given filesystem.
    Returns number of free KB.
    """
    cmd = "df -k -P '%s'" % path
    status, df_output = commands.getstatusoutput(cmd)
    if status != 0:
        raise Exception("command exited with status %s: %r" % (status, cmd))
    lastLine = df_output.split("\n")[-1]
    return int(lastLine.split()[3])

def cmpBasename(a, b):
    return cmp(os.path.basename(a),
        os.path.basename(b))

def getSortedFiles(patterns):
    """ Returns all files matching given pattern.
    The files will be sorted by name.
    """
    filenames = []
    for pattern in patterns:
        filenames += glob.glob(pattern)
    filenames.sort(cmpBasename)
    return filenames

def removeFile(filename):
    print "remove:", filename
    os.remove(filename)

def main():
    if len(sys.argv) <= 2:
        print USAGE % sys.argv[0]
        return

    requiredSpaceInKB = int(sys.argv[1]) * 1024
    patterns = sys.argv[2:]
    filenames = getSortedFiles(patterns)

    for victim in filenames:
        freeSpace = getFreeSpace(victim)
        if freeSpace >= requiredSpaceInKB:
            return
        print "free space:", freeSpace // 1024, "MB"
        removeFile(victim)

#-----------------------------------------------------------------
main()
