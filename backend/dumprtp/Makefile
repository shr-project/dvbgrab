
CC=gcc
CFLAGS =  -g -Wall -O2 -D_FILE_OFFSET_BITS=64 -D_LARGEFILE_SOURCE -D_LARGEFILE64_SOURCE
OBJS=dumprtp rtp.o


all: $(OBJS)

dumprtp: dumprtp.c rtp.o
	$(CC) $(CFLAGS) -o dumprtp dumprtp.c rtp.o

rtp.o: rtp.c rtp.h
	$(CC) $(CFLAGS) -c -o rtp.o rtp.c

clean:
	rm -f  $(OBJS)
