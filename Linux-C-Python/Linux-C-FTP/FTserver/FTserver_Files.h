#include <sys/types.h> 
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>
#include <dirent.h>
#include <string.h>

#include "FTserver.h"

#ifndef FTSERVER_FILES_H
#define FTSERVER_FILES_H

int bufferFile(struct fileInfo *serverFile, char* currentDir);
int openFile(struct fileInfo *serverFile, char* currentDir);
char * getDirectory(char* currentDir);

#endif
