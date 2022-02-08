#!/usr/bin/env python
"""\
Usage: httpGrab -b BEGIN_TIME -e END_TIME -i CHANNEL_NAME -o OUTPUT_FILE
Grabs HTTP stream.
Time format: 'Y-M-D h:m:s'
Channels: nova, prima, ct1, ct2, ct24
Example:
./httpGrab -e '2005-10-26 14:40:00' -i nova -o test.mpg
"""
URL_PREFIX = "http://localhost:1234/"
PROXIES = {}
arg_BEGIN_TIME = "-b"
arg_END_TIME = "-e"
arg_CHANNEL_NAME = "-i"
arg_OUTPUT_FILE = "-o"

import sys
import time
import urllib2

def openUrl(url):
    """ Opens url or dies.
    Returns stream handler.
    """
    try:
        proxyHandler = urllib2.ProxyHandler(PROXIES)
        opener = urllib2.build_opener(proxyHandler)
        return opener.open(url)
    except:
        sys.exit("Error: cannot open url: %s, error: %s: %s"
                % (url, sys.exc_info()[0], sys.exc_info()[1]))

def dumpStream(url, outputFilename, endTime):
    stream = openUrl(url)
    output = file(outputFilename, 'w')

    start = time.time()
    length = endTime - start
    print "Dumping %s > %s for %.2fm" % (url, outputFilename, length/60.0)
    progress = 0
    bufferSize = 8*1024
    while time.time() <= endTime:
        buffer = stream.read(bufferSize)
        if len(buffer) == 0:
            print "Error: end of data, partial output: %s" % outputFilename
            break
        output.write(buffer)
        percent = 100 * (time.time() - start) / length
        while percent >= progress:
            progress += 1
            sys.stdout.write('.')
            sys.stdout.flush()
    output.close()
    stream.close()
    print "File %s is ready" % outputFilename

def waitForBegining(beginTime):
    delay = int(beginTime - time.time())
    if delay > 0:
        print "Waiting %ds" % delay
        time.sleep(delay)

def parseArgs(args):
    """ Returns 'key->value' pairs.
    """
    key = None
    options = {}
    for index, value in enumerate(args):
        if index % 2 == 0:
            options[key] = value
        else:
            key = value
    return options

def parseTime(string):
    """ Returns nuber for seconds since Epoch.
    """
    return time.mktime(time.strptime(string, '%Y-%m-%d %H:%M:%S'))

def strFromTime(seconds):
    return time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(seconds))

def usage():
    print __doc__

def main():
    channelName = None
    outputFilename = "/dev/null"
    beginTime = time.time()
    endTime = beginTime + 24*3600

    options = parseArgs(sys.argv)
    if not arg_CHANNEL_NAME in options:
        usage()
        return
    else:
        channelName = options[arg_CHANNEL_NAME] 
    if arg_OUTPUT_FILE in options:
        outputFilename = options[arg_OUTPUT_FILE] 
    if arg_BEGIN_TIME in options:
        beginTime = parseTime(options[arg_BEGIN_TIME])
    if arg_END_TIME in options:
        endTime = parseTime(options[arg_END_TIME])

    beginTime = max(time.time(), beginTime)
    #print "beginTime:", strFromTime(beginTime)
    #print "endTime:", strFromTime(endTime)
    if beginTime >= endTime:
        sys.exit("Error: begin time must be before end")
    waitForBegining(beginTime)
    url = URL_PREFIX + channelName
    dumpStream(url, outputFilename, endTime)

#-----------------------------------------------------------------
main()
