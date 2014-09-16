CFLAGS = -g -Wall $(LINKF)
GCOV = -fpic -coverage 
LINKF = -lm -lpthread



FTserver: FTserver.o FTserver_Files.o FTserver_sockets.o FTserver_UI.o FTserver_client_control.o
	gcc -o FTserver FTserver.o FTserver_Files.o FTserver_sockets.o FTserver_UI.o FTserver_client_control.o $(CFLAGS)
	make clean

FTserver.o: FTserver.c FTserver.h
	gcc -c FTserver.c $(CFLAGS)

FTserver_UI.o: FTserver_UI.c FTserver_UI.h
	gcc -c FTserver_UI.c  $(CFLAGS)

FTserver_Files.o: FTserver_Files.c FTserver_Files.h
	gcc -c FTserver_Files.c  $(CFLAGS)

FTserver_sockets.o: FTserver_sockets.c FTserver_sockets.h
	gcc -c FTserver_sockets.c  $(CFLAGS)

FTserver_client_control.o: FTserver_client_control.c FTserver_client_control.h
	gcc -c FTserver_client_control.c  $(CFLAGS)



FTserver_test.o: FTserver_test.c FTserver_test.h
	gcc -c FTserver_test.c $(CFLAGS)

FTserver_test: FTserver_test.o FTserver_Files.o FTserver_sockets.o
	gcc -o FTserver_test FTserver_test.o FTserver_Files.o FTserver_sockets.o $(CFLAGS) $(GCOV)

all: FTserver_test FTserver

clean:
	rm -f *.o *.gcov *.gcda *.gcno *.so
