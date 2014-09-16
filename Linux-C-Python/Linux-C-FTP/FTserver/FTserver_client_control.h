#include "FTserver.h"
#include <pthread.h>

#ifndef FTSERVER_CLIENT_CONTROL_H
#define FTSERVER_CLIENT_CONTROL_H

#define ADDRSTRLEN (NI_MAXHOST + NI_MAXSERV + 10)

struct clientTport
{
	int portDescriptor;
	char * host;
};

struct command
{
	char command;
	char argument[255];
};



int getCommand(int clientDescriptor, struct command * clientCommand);
int doCommand(int clientDescriptor, struct command clientCommand, char * client);
int clientControl(void * args);
//static void clientThread (void * args);

#endif
