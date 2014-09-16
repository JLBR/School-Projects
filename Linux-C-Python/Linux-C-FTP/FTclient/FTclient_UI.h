

#ifndef FTCLIENT_UI_H
#define FTCLIENT_UI_H

#include "FTclient.h"
#include "FTclient_Files.h"
#include "FTclient_sockets.h"

int mainMenu();
int stopConnection(int port);
int startConnection(char * server);
int echoConnection(int clientDescriptor);

#endif
