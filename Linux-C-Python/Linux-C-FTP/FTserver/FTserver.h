#include <stdio.h>
#include <stdlib.h>
#include <errno.h>

#include "FTserver_UI.h"

#ifndef	FTSERVER
#define FTSERVER

#define TRUE 1
#define FALSE 0
#define SUCCESS 0
#define FAILURE -1

#define CONTROL_PORT "30021"
#define DATA_PORT "30020"

struct fileInfo
{
	char* name;
	int size;
	int dateModified;//not used yet
	char *fileBuffer;
};

#endif
