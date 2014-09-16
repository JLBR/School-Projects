#include "FTserver_util.h"

/*newDataPortListner accepts incoming requests
INPUT: int recivePort port to listen to
OUTPUT: connection descriptor on success, or FAILURE
*/
int newDataPortListener(int recivePort)
{

	int clientDescriptor;
	socklen_t addrlen;
	struct sockaddr_storage clientAddr;

	addrlen = sizeof(struct sockaddr_storage);

	clientDescriptor = accept(recivePort, (struct sockaddr *) &clientAddr, &addrlen);
	if(clientDescriptor == -1)
	{
		perror("accept has failed");
		return FAILURE;
	}

	return clientDescriptor;
}

/* this is used with telnet for debugging*/
int echoConnection(int clientDescriptor)
{
	char buffer[100];
	int numread;

	memset(&buffer, 0, 100);
	do
	{
		numread = read(clientDescriptor, &buffer, 99);
		if(numread < 99)
			buffer[numread-2]=0;

		printf("numread %d\n", numread);
		printf("%s\n", buffer);
	}while(numread>0);
	
	printf("\n");

	return SUCCESS;
}
