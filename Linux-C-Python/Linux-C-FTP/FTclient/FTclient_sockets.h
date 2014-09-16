#include <sys/socket.h>
#include <netinet/in.h>
#include <netdb.h>
#include <string.h>
#include <unistd.h>

#include "FTclient.h"

#ifndef FTCLIENT_SOCKETS_H
#define FTCLIENT_SOCKETS_H

int TCPListen(char* portNumber);
int TCPconnect(char *server, char* portNumber);
int closeSocket(int socketDes);

#endif
