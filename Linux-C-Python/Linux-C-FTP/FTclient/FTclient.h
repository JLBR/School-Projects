#include <stdio.h>
#include <stdlib.h>
#include <errno.h>

#include "FTclient_UI.h"

#ifndef	FTCLIENT
#define FTCLIENT

#define TRUE 1
#define FALSE 0
#define SUCCESS 0
#define FAILURE -1

struct fileInfo
{
	char* name;
	int size;
	int dateModified;//not used yet
	char *fileBuffer;
};

#endif
