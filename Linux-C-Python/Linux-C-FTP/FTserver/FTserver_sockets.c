#include "FTserver_sockets.h"

/*TCPListen creates a TCP socket for listening
SOURCE:LPI ch59
INPUT: portNumber as a string
OUTPUT: socket descriptor on success 
	or FAILURE on failure
*/
int TCPListen(char* portNumber)
{
	int socketDes;
	int optval = 1;

	struct addrinfo hints;
	struct addrinfo *result;
	struct addrinfo *tempAdderInfo;

	memset(&hints, 0, sizeof(struct addrinfo));

	hints.ai_canonname = NULL;
	hints.ai_addr = NULL;
	hints.ai_next = NULL;
	hints.ai_socktype = SOCK_STREAM;
	hints.ai_family = AF_UNSPEC;
	hints.ai_flags = AI_PASSIVE;

	if(getaddrinfo(NULL, portNumber, &hints, &result) != 0)
	{
		perror("Socket setup failed at getaddrinfo");
		return FAILURE;
	}

	for(tempAdderInfo = result; tempAdderInfo != NULL; tempAdderInfo = tempAdderInfo->ai_next)
	{
		socketDes = socket(tempAdderInfo->ai_family, tempAdderInfo->ai_socktype, tempAdderInfo->ai_protocol);
		if(socketDes == -1)
			continue;//try another address
		
		if(setsockopt(socketDes, SOL_SOCKET, SO_REUSEADDR, &optval, sizeof(optval)) == -1)
		{
			perror("open control setsocketopt failure");
			freeaddrinfo(result);
			return FAILURE;
		}

		if(bind(socketDes, tempAdderInfo->ai_addr, tempAdderInfo->ai_addrlen) == 0)
			break;//successfull binding

		close(socketDes);//unsuccessfull binding
	}
	
	if(tempAdderInfo == NULL)
	{
		freeaddrinfo(result);
		return FAILURE;//unable to bind a socket
	}

	//magic number 50 is arbitray
	if(listen(socketDes, 50) == -1)
	{
		perror("control listen failed");
		close(socketDes);
		freeaddrinfo(result);
		return FAILURE;
	}

	freeaddrinfo(result);

	return socketDes;
}

/*
TCPconnect connects to a listening port on a server
SOURCE:LPI ch59
INPUT:  char * server address
	char * port number
OUTPUT: socket desgnator or FAILURE
*/
int TCPconnect(char *server, char* portNumber)
{
	int socketDes;

	struct addrinfo hints;
	struct addrinfo *result;
	struct addrinfo *tempAdderInfo;

	memset(&hints, 0, sizeof(struct addrinfo));

	hints.ai_canonname = NULL;
	hints.ai_addr = NULL;
	hints.ai_next = NULL;
	hints.ai_socktype = SOCK_STREAM;
	hints.ai_family = AF_UNSPEC;

	if(getaddrinfo(NULL, portNumber, &hints, &result) != 0)
	{
		perror("Socket setup failed at getaddrinfo");
		return FAILURE;
	}

	//try and find an open address to connect to
	for(tempAdderInfo = result; tempAdderInfo != NULL; tempAdderInfo = tempAdderInfo->ai_next)
	{
		socketDes = socket(tempAdderInfo->ai_family, tempAdderInfo->ai_socktype, tempAdderInfo->ai_protocol);
		if(socketDes == -1)
			continue;//try another address
		
		if(connect(socketDes, tempAdderInfo->ai_addr, tempAdderInfo->ai_addrlen) != -1)
			break;//successfull binding

		close(socketDes);//unsuccessfull binding
	}
	
	if(tempAdderInfo == NULL)
	{
		freeaddrinfo(result);
		return FAILURE;//unable to bind a socket
	}

	freeaddrinfo(result);

	return socketDes;

}

/*closeSocket closes the desgnated socket
INPUT:int socketDes socket designator
OUTPUT: SUCCESS if closed FAILURE if in error
*/
int closeSocket(int socketDes)
{

	if(close(socketDes)==-1)
	{
		perror("close socket failed");
		return FAILURE;
	}

	return SUCCESS;
}

