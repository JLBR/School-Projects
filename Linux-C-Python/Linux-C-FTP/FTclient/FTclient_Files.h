#include <sys/types.h> 
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>
#include <dirent.h>
#include <string.h>

#include "FTclient.h"
#include "FTclient_util.h"

#ifndef FTCLIENT_FILES_H
#define FTCLIENT_FILES_H

int writeFile(int fp, char * buffer, int sizeToWrite);
int openFile(char * name, char* currentDir);
int getDir(int portDescriptor);
int getFiles(int portDescriptor, char * fileName);

#endif
