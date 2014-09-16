/*
NAME: Jimmy Brewer
Program name: FTclient
*/
#include "FTclient_Files.h"

#define DATA_PORT "30020"
#define BUFFER_SIZE 15962 //largest MTU x2

/* openFile opens a file to write 
Inputs: char* file name
	char* current directory path
Output: fileInfo 
Return: the file pointer, or FAILURE
*/
int openFile(char * name, char* currentDir)
{

	int fp;
	char choice;
	char temp = ' ';

	fp = open(name, O_CREAT|O_WRONLY|O_EXCL, S_IRUSR|S_IWUSR|S_IRGRP|S_IWGRP|S_IROTH);
	if(fp < 0)
	{
		if(errno == EEXIST)
		{
			printf("Do you want to replace the existing file? (Y/N) ");
			choice = getchar();

			while (temp != '\n')
				temp = getchar();
			temp = ' ';

			if(choice =='Y' || choice == 'y')
			{
				fp = open(name, O_CREAT|O_WRONLY, S_IRUSR|S_IWUSR|S_IRGRP|S_IWGRP|S_IROTH);
				if(fp < 0)
				{
						perror("Error while opening file");
						return FAILURE;
				}
			}
			else
				return FAILURE;
		}
		else
		{
			perror("Error while opening file");
			return FAILURE;
		}
	}

	return fp;
}

/* writeFile writes up to size to write of the buffer to the file
INPUT: int fp file pointer
	char * buffer
	int sizeToWrite (must be smaller or equal to buffer)
OUTPUT: FAILURE OR SUCCESS
*/	
int writeFile(int fp, char * buffer, int sizeToWrite)
{
	//int fp = openFile(serverFile, currentDir);
	int bytesWritten;


	bytesWritten = write(fp, buffer, sizeToWrite);
	if(bytesWritten != sizeToWrite)
	{
		perror("Failure to write file");
		return FAILURE;
	}

	return SUCCESS;

}

/*getDir gets a list of all regular files and displays them
to the client
INPUT:int portDescriptor to send the results
OUTPUT: SUCCESS OR FAILURE
*/
int getDir(int portDescriptor)
{
	int bytesWritten;
	int bytesRead;
	char buffer[BUFFER_SIZE];
	
	int clientDescriptor;

	//setup the listener
	int recivePort = TCPListen(DATA_PORT);
	if(recivePort == FAILURE)
	{
		perror("Error creating data port");
		return FAILURE;
	}

	//send the command
	bytesWritten = write(portDescriptor, "list", sizeof("list"));
	if(bytesWritten != (sizeof("list")))
		return FAILURE;

	//start accpting data
	clientDescriptor = newDataPortListener(recivePort);
	if(clientDescriptor == FAILURE)
	{
		closeSocket(recivePort);	
		return FAILURE;
	}

	do
	{
		memset(&buffer, 0, BUFFER_SIZE);

		bytesRead = read(clientDescriptor, buffer, BUFFER_SIZE-1);
		if(bytesRead == -1)
		{
			perror("getDir reading error");
			closeSocket(recivePort);
			return FAILURE;
		}

		//display the directory contents
		if(bytesRead > 0)
			printf("%s", buffer);
	} while(bytesRead != 0);

	closeSocket(recivePort);

	printf("\n");

	return SUCCESS;
}

/* getFiles gets a file and sends it to the current directory
INPUT: int portDescriptor to send the file
	fileName of the file in the current directory
OUTPUT: SUCCESS OR FAILURE
NOTES: there is nothing to stop directory traversal on the host or guest*/
int getFiles(int portDescriptor, char * fileName)
{

	int bytesWritten;
	int bytesRead;
	//int totalRead = 0;
	char buffer[BUFFER_SIZE];

	char command[1000];

	int fp;

	int clientDescriptor;

	memset(&buffer, 0, BUFFER_SIZE);

	//setup the listener
	int recivePort = TCPListen(DATA_PORT);
	if(recivePort == FAILURE)
	{
		perror("Error creating data port");
		return FAILURE;
	}
	
	snprintf(command, 999, "get %s", fileName);

	//send the command
	bytesWritten = write(portDescriptor, command, strlen(command));
	if(bytesWritten != (strlen(command)))
		return FAILURE;

	//check for a good response from the server
	read(portDescriptor, buffer, BUFFER_SIZE);
	if(strcmp(buffer, "Good")!=0)
	{
		printf("%s \n", buffer);
		closeSocket(recivePort);
		return FAILURE;
	}

	//try and open the file for writing
	fp = openFile(fileName ,".");
	if(fp == FAILURE)
	{
		closeSocket(recivePort);
		return FAILURE;
	}

	//start accpting data
	clientDescriptor = newDataPortListener(recivePort);
	if(clientDescriptor == FAILURE)
	{
		closeSocket(recivePort);	
		return FAILURE;
	}

	do
	{
		memset(&buffer, 0, BUFFER_SIZE);
		//get data
		bytesRead = read(clientDescriptor, buffer, BUFFER_SIZE-1);
		if(bytesRead == -1)
		{
			perror("getFile reading error");
			closeSocket(recivePort);
			return FAILURE;
		}
		
		if(bytesRead > 0)
		{
			if(writeFile(fp, buffer, bytesRead)==FAILURE)
				break;
		}
	
	}while(bytesRead !=0);//close when the socket closes
	
	printf("%s downloaded \n", fileName);

	close(fp);
	closeSocket(recivePort);

	return SUCCESS;

}