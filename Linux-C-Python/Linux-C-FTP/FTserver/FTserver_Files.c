
#include "FTserver_Files.h"


/* openFile opens a file and prints error information 
Inputs: fileInfo pointer with the file name
	char* zero terminated
Output: fileInfo 
Return: the file pointer, or -1 for a failure
	
*/
int openFile(struct fileInfo *serverFile, char* currentDir)
{

	int fp;

	fp = open(serverFile->name, O_RDONLY|O_NOFOLLOW);
	if(fp < 0){
		perror("Error while opening file");
		return FAILURE;
	}
	serverFile->size = lseek(fp, 0, SEEK_END);
	lseek(fp, 0, SEEK_SET);

	return fp;

}

/* bufferFile fills in the buffer of fileInfo
Inputs: fileInfo pointer with the file name
	currentDir char* zero terminated -- NOT USED ATM
Output: Returns the file pointer, or -1 for a failure
*/
//TODO add file locking to prevent a change until the file is read
//possably use a file copy to a temp folder to save the current state of the file
int bufferFile(struct fileInfo *serverFile, char* currentDir)
{
	int fp;
	int numRead;

	fp = openFile(serverFile, currentDir);
	if(fp == FAILURE)
		return FAILURE;


	serverFile->fileBuffer = (char *)malloc(sizeof(char)*serverFile->size+1);
	
	numRead = read(fp, serverFile->fileBuffer, serverFile->size);

	if(numRead < 0){
		close(fp);
		perror("Error while reading file");
		return FAILURE;
	}

	close(fp);

	serverFile->fileBuffer[serverFile->size] = 0;
	return SUCCESS;
}

/* getDirectory gets a list (now lists all files)(REMARKED OUT -- of files that end with .txt)
Inputs: currentDir char* zero terminated -- NOT USED ATM
Output: Returns char * with the list or NULL for a failure or no files in the current directory
*/
char * getDirectory(char* currentDir)
{
	
	struct dirent *directoryListing;
	DIR *dirPointer;
	int len;
	char tempExtension[2];
	//int i;
	char* tempList = NULL;
	char* directoryList = NULL;
	
	dirPointer = opendir(currentDir);
	
	if(dirPointer == NULL)
	{
		perror("opendir failed");
		return NULL;
	}

	//cycle through the directory listing
	for(;;)
	{
		errno = 0;
		directoryListing = readdir(dirPointer);
		
		if(directoryListing == NULL)
			break;
		
		//list only regular files
		if(directoryListing->d_type == DT_REG)
		{
			//skip hidden files
			len = strlen(directoryListing->d_name);
			tempExtension[0] = directoryListing->d_name[len-1];
			tempExtension[1] = 0;
			
			if(strncmp(tempExtension, "~", 1)!=0)
			{
				//add on the file name to the end of the list
				if(tempList != NULL)
				{
					free(directoryList);
					directoryList = malloc(snprintf(NULL, 0, "%s\n%s", tempList, directoryListing->d_name) + 1);//from http://stackoverflow.com/questions/1383649/concatenating-strings-in-c-which-method-is-more-efficient
					sprintf(directoryList, "%s\n%s",tempList ,directoryListing->d_name);
		
					
				}
				else
				{
					//this is the first file found
					directoryList = malloc(snprintf(NULL, 0, "%s", directoryListing->d_name) + 1);
					sprintf(directoryList, "%s", directoryListing->d_name);

				}
				//coppy the directory to the temporary list
				free(tempList);
				tempList = malloc(snprintf(NULL, 0, "%s", directoryList) + 1);					
				sprintf(tempList, "%s", directoryList);

			}
		}
		
	}

	if(errno != 0)
	{
		perror("Unspecified error in getDirectory");
		return NULL;	
	}

	return directoryList;
	
}

