#include <ar.h>
#include <stdio.h>
#include <getopt.h> 
#include <stdlib.h>
#include <fcntl.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <math.h>
#include <time.h>
#include <sys/stat.h>
#include <errno.h>
#include <dirent.h>
#include "assert.h"

#define TYPE struct arFiles

typedef struct DynArr DynArr;

struct arFiles
{
	char *fileName;
	char *file;
	int  fileSize;
};


struct DynArr
{
	TYPE *data;		/* pointer to the data array */
	int size;		/* Number of elements in the array */
	int capacity;		/* capacity ofthe array */
};

// Initialize (including allocation of data array) dynamic array.
void _initDynArr(DynArr *v, int capacity)
{
	assert(capacity > 0);
	assert(v!= 0);
	v->data = malloc(sizeof(TYPE) * capacity);
	assert(v->data != 0);
	v->size = 0;
	v->capacity = capacity;

}

// Allocate and initialize dynamic array.
DynArr* createDynArr(int cap)
{
	DynArr *r; 
	assert(cap > 0);
	r = malloc(sizeof( DynArr));
	assert(r != 0);
	_initDynArr(r,cap);
	return r;
}

// Deallocate data array in dynamic array. 
void freeDynArr(DynArr *v)
{
	assert(v!=0);

	if(v->data != 0)
	{
		free(v->data); 	/* free the space on the heap */
		v->data = 0;   	/* make it point to null */
	}
	v->size = 0;
	v->capacity = 0;
}

// Deallocate data array and the dynamic array ure. 
void deleteDynArr(DynArr *v)
{
	assert (v!= 0);
	freeDynArr(v);
	free(v);
}

// Resizes the underlying array to be the size cap 
void _dynArrSetCapacity(DynArr *v, int newCap)
{
	int i;
	TYPE *oldData;
	int oldSize = v->size;
	oldData = v->data;

	//printf("========Resizing========\n");
	/* Create a new dyn array with larger underlying array */
	_initDynArr(v, newCap);

	for(i = 0; i < oldSize; i++){
		v->data[i] = oldData[i];
	}

	v->size = oldSize;
	/* Remember, init did not free the original data */
	free(oldData);
}

//Adds an element to the end of the dynamic array
void addDynArr(DynArr *v, TYPE val)
{

	assert(v!=0);

	/* Check to see if a resize is necessary */
	if(v->size >= v->capacity)
		_dynArrSetCapacity(v, 2 * v->capacity);
	
	v->data[v->size] = val;
	v->size++;

}

//Get an element from the dynamic array from a specified position
TYPE getDynArr(DynArr *v, int pos)
{
	assert(v!=0);
	assert(pos < v->size);
	assert(pos >= 0);
   
	return v->data[pos];
}


//	Remove the element at the specified location from the array,
void removeAtDynArr(DynArr *v, int idx){
	int i;
	assert(v!= 0);
	assert(idx < v->size);
	assert(idx >= 0);

   //Move all elements up

   for(i = idx; i < v->size-1; i++){
      v->data[i] = v->data[i+1];
   }

   v->size--;

}

// Get the size of the dynamic array
int sizeDynArr(DynArr *v)
{
	assert(v!=0);
	return v->size;
}

//#define	ARMAG		"!<arch>\n"	/* ar "magic number" */
//#define	SARMAG		8		/* strlen(ARMAG); */

//struct ar_hdr {
//	char ar_name[16];		/* name */
//	char ar_date[12];		/* modification time */
//	char ar_uid[6];			/* user id */
//	char ar_gid[6];			/* group id */
//	char ar_mode[8];		/* octal file permissions */
//	char ar_size[10];		/* size in bytes */
//#define	ARFMAG	"`\n"
//	char ar_fmag[2];		/* consistency check */
//};


//This does not deal with the flags wich are not stored so there are no special permissions
//Returns a text string in the format of rwxrwxrwx
void filePerms(int perm){
	char str[sizeof("rwxrwxrwx")];

	snprintf(str, sizeof("rwxrwxrwx"), "%c%c%c%c%c%c%c%c%c",
		(perm & S_IRUSR)? 'r' : '-', 
		(perm & S_IWUSR)? 'w' : '-',
		(perm & S_IXUSR)? 'x' : '-',
		(perm & S_IRGRP)? 'r' : '-', 
		(perm & S_IWGRP)? 'w' : '-',
		(perm & S_IXGRP)? 'x' : '-',
		(perm & S_IROTH)? 'r' : '-', 
		(perm & S_IWOTH)? 'w' : '-',
		(perm & S_IXOTH)? 'x' : '-');
		printf("%s ", str);
}

//Returns 1 if not at the end
int getHeadder(int fd, struct ar_hdr *header){
	int num_read = 0;

	num_read = read(fd, header,60);

	if (num_read < 0)
	{
		close(fd);
		perror("Error while reading the file.\n");
		exit(EXIT_FAILURE);
	}else if (num_read == 0){
		return 0;
	}
	return 1;
}

void validate(int fd){
	int num_read;
	char buffer[7];

	num_read = read(fd, &buffer, SARMAG);
	if (num_read < 0)
	{
		close(fd);
		perror("Error while reading the archive file.\n");
		exit(EXIT_FAILURE);
	}
	if((strncmp(buffer, ARMAG, sizeof(ARMAG)-1))!= 0){
		close(fd);
		printf("This is not an achive file.\n");
	        exit(EXIT_SUCCESS);
	}
	
}

//Opens and calls validate in read only
void openArchiveRead(int *fd, char *arName){

	*fd = open(arName, O_RDONLY);
	if (fd < 0){
		perror("Error while opening the file.");
		exit(EXIT_FAILURE);
	}
	validate(*fd);
}

//opens the archive for writting or creates a new one if the file does not exist
void openArchiveWrite(int *fd, char *arName){

	*fd = open(arName, O_RDWR);
	if (*fd < 0){
		int errsv = errno;
		if(errsv == ENOENT){
			*fd = open(arName, O_RDWR|O_CREAT, 438);
			if(*fd<0){
				perror("Error while creating the archive file.");
				exit(EXIT_FAILURE);
			}else{
				char buffer[8] = "!<arch>\n";
				int num_read = write(*fd, buffer, sizeof(buffer));
				if (num_read < 0)
				{
					close(*fd);
					perror("Error while writing the archive file.\n");
					exit(EXIT_FAILURE);
				}
				lseek(*fd, 0, SEEK_SET);
			}
		}else{
			perror("Error while opening the archive file.");
			exit(EXIT_FAILURE);
		}		
	}

	validate(*fd);
}

//opens the archive for deletion
void openArchiveDelete(int *fd, char *arName){

	*fd = open(arName, O_RDWR|O_CREAT|O_TRUNC, 438);
	if(*fd<0){
		perror("Error while creating the archive file.");
		exit(EXIT_FAILURE);
		}else{
		char buffer[8] = "!<arch>\n";
		int num_read = write(*fd, buffer, sizeof(buffer));
		if (num_read < 0)
		{
			close(*fd);
			perror("Error while writing the archive file.\n");
			exit(EXIT_FAILURE);
		}
		lseek(*fd, 0, SEEK_SET);
	}

	validate(*fd);
}

//Makes a 60 char string retruning in the suppied ar_hder struct
void makeHeader(struct ar_hdr *header, int fdSource, char *sourceName){
	int len;
	int i;
	int k;
	long j;
	char temps[17];
	struct stat fileInfo;		
	
	fstat(fdSource, &fileInfo);

//set ar_name
	len = strlen(sourceName);
	if(len>15)
		len=15;
//printf("source name %s \n",sourceName);
	for(i = 0;i<16;i++){
		header->ar_name[i] = (len>i)?sourceName[i]:' ';
		//printf("%c \n",header->ar_name[i]);
	}
	header->ar_name[len]='/';

					
//set ar_Mode				
	k = fileInfo.st_mode;
	j =5;
	while(j>=0){
		if(k>0){
			i = k%8;//convert base8
			snprintf(temps, 2, "%d", i);
			header->ar_mode[j]=temps[0];
			k = k/8;
		}else{
			header->ar_mode[j] = '0';
		}
		j--;
	}
	header->ar_mode[6]=' ';
	header->ar_mode[7]=' ';

//Set ar_date
	j = fileInfo.st_mtime;
	snprintf(temps, 12, "%lu", j);
	for(i = 0; i<12; i++){
		header->ar_date[i] = (strlen(temps)>i)? temps[i]:' ';
	}

//Set ar_gid
	snprintf(temps, 6, "%d", fileInfo.st_gid);
	for(i = 0; i<6; i++){
		header->ar_gid[i] =(strlen(temps)>i)? temps[i]:' ';
	}
	
//Set ar_uid				
	snprintf(temps, 6, "%d", fileInfo.st_uid);
	for(i = 0; i<6; i++){
		header->ar_uid[i] = (strlen(temps)>i)? temps[i]:' ';
	}

//Set ar_size					
	snprintf(temps, 10, "%lu", fileInfo.st_size);
	for(i = 0; i<10; i++){
		header->ar_size[i] = (strlen(temps)>i)? temps[i]:' ';
	}

//set ar_fmag
	header->ar_fmag[0] = '`';
	header->ar_fmag[1] = '\n';

}


void appendFile(int fd, char *fName){
	int fdSource;
	char *buffer;
	int fileLen;
	char *fileBuffer = NULL;
	int num_read;
	int i;
	struct ar_hdr header;
	
	fdSource = open(fName, O_RDONLY);
	if (fdSource < 0)
	{
		perror("Error while opening file to add to the archive.");
		exit(EXIT_FAILURE);
	}


	makeHeader(&header, fdSource, fName);


	//read file into memory
	fileLen = (int)atoi(header.ar_size);
	fileBuffer = (char*)malloc(fileLen);
	num_read = read(fdSource, fileBuffer, fileLen);
	if (num_read < 0)
	{
		close(fdSource);
		perror("Error while reading the file to add to the archive.\n");
		exit(EXIT_FAILURE);
	}

	buffer = (char*)malloc(60);

	for(i = 0;i<60;i++)//clear the buffer
		buffer[i] = ' ';
					
	for(i = 0;i<60;i++)
		buffer[i] = header.ar_name[i];

	//write header to archive
	num_read = write(fd, buffer, 60);
	if (num_read < 0)
	{
		close(fd);
		perror("Error while writing the archive file.\n");
		exit(EXIT_FAILURE);
	}

	//fileBuffer = strcat(fileBuffer, (char *)buffer);
	//write file to archive
	num_read = write(fd, fileBuffer, fileLen);
	if (num_read < 0)
	{
		close(fd);
		perror("Error while writing the archive file.\n");
		exit(EXIT_FAILURE);
	}

	//if odd file lenght write newline to end of archive			
	if(fileLen%2!=0){
		buffer[0] = '\n';
		num_read = write(fd, buffer , 1);
		if (num_read < 0)
		{
			close(fd);
			perror("Error while writing the archive file.\n");
			exit(EXIT_FAILURE);
		}
	}
	free(buffer);						
	free(fileBuffer);
	close(fdSource);

}

int main(int argc, char **argv){
	char *temps;
	char c;
	char *arName = "";
        int  verbose = 0;
	int  nextArg = 0;
        int  fd;
	int fileLen =0;
	char buffer[60];
	int  i =0;
	long  j =0;
	int k =0;
	double  tempSum;
	int fdDestination;	
	char *fileBuffer = NULL;
	int num_read;
	int found[argc];
	struct tm *time;
	struct ar_hdr header;
	struct dirent **namelist;
	struct arFiles arF;

	while ((c = getopt(argc, argv, "q:x:v:d:A:wt:")) != -1){
		switch (c)
		{
			case 'q'://appends new file to end of archive
				arName = optarg;
				nextArg = optind;
				
				if(nextArg>=argc){
					fprintf(stderr, "Too few arguments\nUsage: %s ar {qxvdAwt} archive-file file...\n", argv[0]);
					exit(EXIT_FAILURE);
				}

				openArchiveWrite(&fd, arName);
				lseek(fd,0, SEEK_END);

				while (nextArg<argc){
					temps = argv[nextArg];
					appendFile(fd, temps);
					nextArg++;
				}
				close(fd);

				break;
	 		case 'x'://extracts a file
				arName = optarg;
				nextArg = optind;
				openArchiveRead(&fd, arName);
				
				for(i = 0; i<argc;i++)
					found[i]=0;

				while (nextArg<argc){

					while (getHeadder(fd, &header)){
						temps = NULL;
						temps = strtok(header.ar_name, "/");
						fileLen = atoi(header.ar_size);
						if(fileLen%2 != 0)
							fileLen++;

						if(strcmp(temps, argv[nextArg])==0){

							tempSum=0;

							for(i=5;i>2;i--){//base conversion for modes
								buffer[0] = header.ar_mode[i];
								buffer[1]= 0;
								tempSum += atoi(buffer)*pow(8.0,5-i);

							}
							
							//read archived file into memory
							fileLen = atoi(header.ar_size);
							fileBuffer = (char *)malloc(fileLen);
							num_read = read(fd, fileBuffer, fileLen);
							if (num_read < 0)
							{
								close(fd);
								perror("Error while reading the file to add to extract.\n");
								exit(EXIT_FAILURE);
							}

							//create the archived file 
							i = tempSum;
							fdDestination = open(temps, O_RDWR|O_CREAT, i);
							if(fdDestination<0){
								free(fileBuffer);
								close(fd);
								perror("Error while creating the extracted file.");
								exit(EXIT_FAILURE);
							}

							//write the archived file to disk
							num_read = write(fdDestination, fileBuffer, fileLen);
							if (num_read < 0)
							{
								close(fd);
								perror("Error while writing the extracted file.\n");
								exit(EXIT_FAILURE);
							}

							free(fileBuffer);
							close(fdDestination);
							break;
						}
						lseek(fd, fileLen, SEEK_CUR);					
					}	
					lseek(fd, 8, SEEK_SET);				
					nextArg++;
				}

				close(fd);
				break;
			case 'v'://set verbose to 1 and cascade to -t
				verbose=1;
			case 't':
				arName = optarg;
				openArchiveRead(&fd, arName);

				while (getHeadder(fd, &header)){
					
					if(verbose){//accounts for -v
						tempSum=0;
						for(i=5;i>2;i--){//convert mode base
							buffer[0] = header.ar_mode[i];
							buffer[1]= 0;
							tempSum += atoi(buffer)*pow(8.0,5-i);
						}
						
						//write permissions
						filePerms(tempSum);

						//write GID/UID
						printf("%d/", atoi(header.ar_gid));
						printf("%d ", atoi(header.ar_uid));
						
						//store the size string
						k = atoi(header.ar_size);
						snprintf(buffer, 10, "%d", k);
						k= strlen(buffer);
						
						//ar displays 6 places for size unless larger than 6 places
						if(k<6){
							j = 6;
						} else{
							j = k;//if larger than 6 display up to 10 places (limit of .ar_size)
						}

						//print size right justified
						for(i=0;i<j;i++){
							buffer[i] = (i+k<j)?' ': header.ar_size[(i+k-j)];
							
						}
						buffer[j]= 0;
						printf("%s ", buffer); 
					
						//convert time to tm struct and write custom formated time
						j = atoi(header.ar_date);
						time = localtime(&j);
						strftime(buffer, 60, "%b %e %H:%M %Y", time);
						printf("%s ",buffer);
		
					}
			
					//the first token indicated by / is the file name
					temps = strtok(header.ar_name, "/");
		     			printf("%s\n",temps);
					
					//move the file pointer to where the next header should exist
					fileLen = atoi(header.ar_size);	
					if(fileLen%2 != 0)
						fileLen++;
					lseek(fd, fileLen, SEEK_CUR);
				}
				close(fd);
				break;
	           	case 'd'://removes a file from the archive
				arName = optarg;
				nextArg = optind;
				openArchiveRead(&fd, arName);
				
				//find all locations to delete
				while (nextArg<argc){

					while (getHeadder(fd, &header)){
						temps = NULL;
						temps = strtok(header.ar_name, "/");
						fileLen = atoi(header.ar_size);
						if(fileLen%2 != 0)
							fileLen++;

						if(strcmp(temps, argv[nextArg])==0){
							found[nextArg] = 1;
							break;
						}
						lseek(fd, fileLen, SEEK_CUR);					
					}	
					lseek(fd, 8, SEEK_SET);				
					nextArg++;
				}
	
				//check to see if at least one file was found
				k=0;
				for(i=0;i<argc;i++)
					k = (k|found[i]);

				if(k){//get each file info
					char *fileData;
					struct DynArr *fileArray;
					fileArray = createDynArr(10);
					
					while (getHeadder(fd, &header)){
						
						fileLen = atoi(header.ar_size);
						if(fileLen%2 != 0)
							fileLen++;
						fileData = (char *)malloc(fileLen+60);

						lseek(fd, -60, SEEK_CUR);
						num_read = read(fd, fileData, fileLen+60);
						if (num_read < 0)
						{
							close(fd);
							perror("Error while reading the archive prior to delete.\n");
							exit(EXIT_FAILURE);
						}
						
						//add filedata to array
						arF.fileName = (char *)malloc(15);
						char *temp = strtok(header.ar_name, "/");
						strncpy(arF.fileName, temp, 15);
						arF.fileSize = fileLen+60;
						arF.file = fileData;
						addDynArr(fileArray, arF);
						

					}
					
					//close the current file and re-open it deleting it's contents
					close(fd);
					openArchiveDelete(&fd, arName);

					//iterate through each file saved in fileArray
					int numFiles = sizeDynArr(fileArray);
					for(i=0;i<numFiles;i++){
						arF = getDynArr(fileArray, i);
						for(k=0;k<argc;k++){
							if(found[k] && (strcmp(arF.fileName, argv[k])==0)){

								found[k] = 0;//turn off the found flag to stop searching after the first file is found
								k=argc+1;// break out of the loop
							}	
						}
						//if k==argc then the file was not on the list to be deleted so write it
						if(k==argc){
							num_read = write(fd, arF.file, arF.fileSize);
							if (num_read < 0)
							{
								close(fd);
								perror("Error while writing the updated archive file.\n");
								exit(EXIT_FAILURE);
							}
						}
					}

					deleteDynArr(fileArray);
					close(fd);
				} else{
					close(fd);
				}
	             		break;
	           	case 'A'://most of the code for getting the file listing comes from the scandir man page
				arName = optarg;
				k = scandir(".", &namelist, NULL, alphasort);
				if(k<0){
					perror("Failed to get directory listing.");
					exit(EXIT_FAILURE);
				}
				
				openArchiveWrite(&fd, arName);
				lseek(fd,0, SEEK_END);

				for(i =0;i<k;i++){
		
					if((strcmp(namelist[i]->d_name, arName)!=0)&&namelist[i]->d_type== DT_REG){
						appendFile(fd, namelist[i]->d_name);
					}
				}

				close(fd);
				free(namelist);
	             		break;
	           	case 'w':
	             		break;
	           	default:
		     		fprintf(stderr, "Usage: %s ar {qxvdAwt} archive-file file...\n", argv[0]);
	            		exit(EXIT_FAILURE);
		}
	}

   return 0;
}


