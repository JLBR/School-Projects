#include "FTserver_client_control.h"

/*
doCommand executes valid requests from the client
INPUT: 	int client descriptor for the control port
	struct command * client command that will be filled in
OUTPUT:SUCCESS or FAILURE
*/
int getCommand(int clientDescriptor, struct command * clientCommand)
{
	char buffer[255];
	int numread;
	char * token;
	char * savepoint;

	memset(&buffer, 0, 255);
	clientCommand->command = 0;

	numread = read(clientDescriptor, &buffer, 254);
	if(numread == -1)
	{
		perror("Error getting client commands");
		return FAILURE;
	}

	//dissconect a disconnected client
	if(buffer[0] == 0)
	{
		clientCommand->command = 'q';
		return SUCCESS;
	}

	token = strtok_r(buffer, " ", &savepoint);
	
	printf("%s\n",token);

	//update command struct based on delivered commands
	if(strcmp(token, "list")==0)
	{
		clientCommand->command = 'l';
	}
	else if (strcmp(token, "get")==0)
	{
		clientCommand->command = 'g';
		strcpy(clientCommand->argument, savepoint);
	}else if (strcmp(token, "quit")==0)
		clientCommand->command = 'q';
	else
		return FAILURE;

	return SUCCESS;
}

/*
doCommand executes valid requests from the client
INPUT: int client descriptor for the control port
		srtuct command client command that contains the command an any arguments
		char * client used to identify the client in logging
OUTPUT:SUCCESS or FAILURE
*/
int doCommand(int clientDescriptor, struct command clientCommand, char * client)
{
	char * dirList;
	struct fileInfo file;

	int numWritten = 0;

	int portDescriptor = TCPconnect(client, DATA_PORT);
	if(portDescriptor ==FAILURE)
	{
		perror("Failed to connect to client data");
		return FAILURE;
	}

	switch(clientCommand.command)
	{
		case 'l'://list directory
			dirList = getDirectory(".");
			if(dirList != NULL)
			{
				printf("sending List to %s\n",client);
				numWritten = write(portDescriptor, dirList, strlen(dirList));
				if(numWritten<=0)
				{
					perror("Error when sending list of files");
				}
			}
			else
				write(portDescriptor, "No files avalible", strlen("No files avalible\n"));
			break;
		case 'g'://get files
			printf("getting %s for %s\n", clientCommand.argument, client);

			file.name = clientCommand.argument;

			//if the file exists tell the client Good and send the file
			//otherwise send file not found error
			if(bufferFile(&file, ".")==SUCCESS)
			{
				write(clientDescriptor, "Good", sizeof("Good"));
				numWritten = write(portDescriptor, file.fileBuffer, file.size);
			}
			else
				write(clientDescriptor, "File not found", sizeof("File not found"));		
	}

	closeSocket(portDescriptor);

	return SUCCESS;
}

/*ClientThread runs the thread to alow for multiple clients from difernt IPs to connect
(same IP does not work 100% due to the non-negotiated ports
INPUT: args containing struct clientTport with the host name and port descriptor
OUTPUT: None*/
static void clientThread (void * args)
{
	struct clientTport *cp = (struct clientTport *) args;
	struct command clientCommand;

	//cycle through client requests untill the client disconects
	for(;;){

		if(getCommand(cp->portDescriptor, &clientCommand)==SUCCESS)
		{

			if(clientCommand.command == 'q')
			{
				printf("Closeing connection %s\n",cp->host);
				closeSocket(cp->portDescriptor);
				break;
			}
			doCommand(cp->portDescriptor, clientCommand, cp->host);
		}
		else
		{
			printf("Closeing connection %s\n",cp->host);
			closeSocket(cp->portDescriptor);
			break;
		}
		
	}
}

/* client control runs an endless loop in a thread accepting clients and
responding to commands
INPUT: void * args (casted from struct clientTport) contains the client port descriptor
OUTPUT: NONE as the only way to terminat the function is by killing the thread
*/
int clientControl(void * args)
{
	struct clientTport *cp = (struct clientTport *) args;
	struct clientTport cpt;

	int clientDescriptor;

	socklen_t addrlen;

	char addrStr[ADDRSTRLEN];
	char host[NI_MAXHOST];
	char service[NI_MAXSERV];
	
	pthread_t thr;
	pthread_attr_t attr;
	int tError;

	struct sockaddr_storage clientAddr;

	//cycle through each connection
	for(;;)//from LPI page 1222
	{
		addrlen = sizeof(struct sockaddr_storage);
		clientDescriptor = accept(cp->portDescriptor, (struct sockaddr *) &clientAddr, &addrlen);
		if(clientDescriptor == -1)
		{
			perror("accept has failed");
			continue;
		}

		//get connection info for logging
		if(getnameinfo((struct sockaddr *) &clientAddr, 
			addrlen, host, NI_MAXHOST, service, NI_MAXSERV, 0) == 0)
			snprintf(addrStr, ADDRSTRLEN, "(%s, %s)", host, service);
		else
			snprintf(addrStr, ADDRSTRLEN, "(UNKNOWN)");

		printf("Connection from %s\n", addrStr);

		cpt.host = malloc(sizeof(char)*strlen(host));

		strcpy(cpt.host, host);
		cpt.portDescriptor = clientDescriptor;

		tError = pthread_attr_init(&attr);
		if(tError != 0)
		{
			perror("Error inti pthread");
			return FAILURE;
		}

		tError = pthread_attr_setdetachstate(&attr, PTHREAD_CREATE_DETACHED);
		if(tError != 0)
		{
			perror("Error setting detatched pthread");
			return FAILURE;
		}

		tError = pthread_create(&thr, &attr, (void *)clientThread, &cpt );
		if(tError != 0)
		{
			perror("Error creating pthread");
			exit(EXIT_FAILURE);
		}

	}

	//this should never be reached
	return SUCCESS;
}