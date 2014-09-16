#include "FTserver_UI.h"
#include <pthread.h>

#define STARTED 1
#define STOPPED 0

/* Start Server starts listening on the control port
Input:None
Output: SUCCESS when successfull
*/
int startServer(pthread_t * thr)
{
	struct clientTport *cp = malloc(sizeof(struct clientTport));

	pthread_attr_t attr;
	int tError;

	int portDescriptor = TCPListen(CONTROL_PORT);
	if(portDescriptor == FAILURE)
	{
		printf("failed to start\n");
		return FAILURE;	
	}

	printf("Server started\n");

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

	cp->portDescriptor = portDescriptor;

	tError = pthread_create(thr, &attr, (void *)clientControl, cp );
	if(tError != 0)
	{
		perror("Error creating pthread");
		exit(EXIT_FAILURE);
	}
	
	return portDescriptor;
}

/* Stop Server stops listening on the control port
Input: port to close
Output: SUCCESS or FAILURE
*/
int stopServer(int port)
{
	if(closeSocket(port) == FAILURE)
	{
		printf("Failed to stop");
		return FAILURE;
	}

	printf("Server stopped\n");
	return SUCCESS;
}


/* Main Menu gives the user the functions of the server
Inputs:NONE
Output: returns 0 when successfull
*/
int mainMenu()
{

	char choice;
	char temp = ' ';
	int serverState = STOPPED;
	int controlPort;
	pthread_t controlThread;
	
	do
	{
		if(serverState == STOPPED)
			printf("\n[1]Start Server\n");
		else
			printf("\n[1]Stop Server\n");

		//printf("[2]Change Default Directory\n\n");
		printf("[9]Stop Server and quit\n");

		choice = getchar();

		while (temp != '\n')
			temp = getchar();
		temp = ' ';

		switch(choice)
		{
			case '1'://start/stop based on the current state
				if(serverState == STOPPED)
				{
					controlPort = startServer(&controlThread);
					if(controlPort != FAILURE)
						serverState = STARTED;
				}
				else
				{
					if(stopServer(controlPort)==SUCCESS)
						serverState = STOPPED;
					pthread_cancel(controlThread);
				}
				break;
			case '2'://removed
				break;
			case '9'://shutdown
				if(serverState == STARTED)
				{
					if(stopServer(controlPort)==SUCCESS)
						serverState = STOPPED;
				}
				break;

			default:
				;
		}

	}while(choice != '9');

	return SUCCESS;
}

