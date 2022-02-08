
Modified version of dumprtp that is able to use bigger socket receive buffer.
This prevents to UDP loss when the system is busy.

The buffer limit should be increased in system:
$ sysctl -w net.core.rmem_max=8388608

See the background info:
http://www.29west.com/docs/THPM/udp-buffering-background.html

Dumprtp upstream:
http://sourceforge.net/projects/dvbtools/

