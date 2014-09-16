#include "FTclient.h"

/*Main calls mainMenu to run the program
mainMenu was used for the main loop to make unit testing easer
INPUT: server address from the command line
*/
int main(int argc, char* argv[])
{
	if(argc < 2)
	{
		printf("Usage is %s <server address>\n", argv[0]);
		return 0;
	}

	mainMenu(argv[1]);

	return 0;
}
