CFLAGS = -g -Wall $(LINKF)
GCOV = -fpic -coverage 
LINKF = -lm -lpthread


FTclient: FTclient.o FTclient_Files.o FTclient_sockets.o FTclient_UI.o FTclient_util.o
	gcc -o FTclient FTclient.o FTclient_Files.o FTclient_sockets.o FTclient_UI.o FTclient_util.o $(CFLAGS)
	make clean

FTclient_UI.o: FTclient_UI.c FTclient_UI.h
	gcc -c FTclient_UI.c  $(CFLAGS)

FTclient_Files.o: FTclient_Files.c FTclient_Files.h
	gcc -c FTclient_Files.c  $(CFLAGS)

FTclient_sockets.o: FTclient_sockets.c FTclient_sockets.h
	gcc -c FTclient_sockets.c  $(CFLAGS)

FTclient_util.o: FTclient_util.h FTclient_util.c
	gcc -c FTclient_util.c  $(CFLAGS)

FTclient.o: FTclient.c FTclient.h
	gcc -c FTclient.c $(CFLAGS)





FTclient_test.o: FTclient_test.c FTclient_test.h
	gcc -c FTclient_test.c $(CFLAGS)

FTclient_test: FTclient_test.o FTclient_Files.o FTclient_sockets.o
	gcc -o FTclient_test FTclient_test.o FTclient_Files.o FTclient_sockets.o $(CFLAGS) $(GCOV)

all: FTclient_test FTclient

clean:
	rm -f *.o *.gcov *.gcda *.gcno *.so