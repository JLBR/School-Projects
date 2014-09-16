

#ifndef FTSERVER_UI_H
#define FTSERVER_UI_H

#include "FTserver.h"
#include "FTserver_Files.h"
#include "FTserver_sockets.h"
#include "FTserver_client_control.h"

int mainMenu();
int startServer(pthread_t * thr);
int stopServer(int port);

#endif
