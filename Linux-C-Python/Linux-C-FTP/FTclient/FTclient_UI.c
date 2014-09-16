#include "FTclient_UI.h"
#include <pthread.h>

#define STARTED 1
#define STOPPED 0

#define CONTROL_PORT "30021"
#define DATA_PORT "30020"

/* Start connection connects to the server's control port
Input: server address
Output: portDescriptor when successfull, FAILURE when in error
*/
int startConnection(char * server)
{
	printf("%s\n",server);
	int portDescriptor = TCPconnect(server, CONTROL_PORT);
	if(portDescriptor == FAILURE)
	{
		perror("failed to connect");
		return FAILURE;	
	}

	printf("Connected to server");

	return portDescriptor;
}


/* Stop connection stops the control port 
Input: port descriptor to stop
Output: SUCCESS when successfull or FAILURE when not
*/
int stopConnection(int port)
{
	if(closeSocket(port) == FAILURE)
	{
		printf("Failed to stop");
		return FAILURE;
	}

	printf("Dissconected from  server\n");
	return SUCCESS;
}


/* Main Menu gives the user the functions of the client
Inputs: server address
Output: returns SUCCESSFULL when successfull
*/
int mainMenu(char *server)
{
	char * fileName = malloc(sizeof(char)*255);

	char choice;
	char temp = ' ';
	int connectionState = STOPPED;
	int controlPort;
	
	do
	{
		if(connectionState == STOPPED)
			printf("\n[1]Connect to Server\n");
		else
		{
			printf("\n[1]Dissconect from Server\n");
			printf("[2]Get a list of files\n");
			printf("[3]Get a file\n");
		}

		printf("[9]Dissconect and quit\n");

		choice = getchar();
		//clear stdin
		while (temp != '\n')
			temp = getchar();
		temp = ' ';

		switch(choice)
		{
			case '1'://stops or starts based on the current state
				if(connectionState == STOPPED)
				{
					controlPort = startConnection(server);
					if(controlPort != FAILURE)
						connectionState = STARTED;
				}
				else
				{
					if(stopConnection(controlPort)==SUCCESS)
						connectionState = STOPPED;
				}
				break;
			case '2'://get the directory
				if(getDir(controlPort)==FAILURE)
					perror("getDir Failure");
				break;
			case '3':// get a file
				printf("Enter the file name to get: ");

				scanf("%s", fileName);
				//clear stdin
				while (temp != '\n')
					temp = getchar();
				temp = ' ';

				getFiles(controlPort, fileName);
				break;
			case '9'://shutdown
				if(connectionState == STARTED)
				{
					if(stopConnection(controlPort)==SUCCESS)
						connectionState = STOPPED;
				}
				break;

			default:
				;
		}

	}while(choice != '9');

	free(fileName);

	return SUCCESS;
}

