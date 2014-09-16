#include <fcntl.h>
#include <sys/stat.h>
#include <stdlib.h>
#include <stdio.h>
#include <limits.h>
#include <math.h>
#include <semaphore.h>
#include <sys/mman.h>
#include <unistd.h>
//#include <pthread.h>
#include <sys/wait.h>
#include <time.h>

#define BIT_FRAME int

const unsigned int numIntBits = (sizeof(BIT_FRAME)*8);

void setBit(BIT_FRAME *arr, unsigned int bIndex)
{
	unsigned int arrIndex = bIndex/numIntBits;
	unsigned int bPoss = bIndex % numIntBits;

	unsigned BIT_FRAME val = 1;

	val = val << bPoss;

	arr[arrIndex] = arr[arrIndex] | val;
	
}

void clearBit(BIT_FRAME *arr, unsigned int bIndex)
{
	unsigned int arrIndex = bIndex/numIntBits;
	unsigned int bPoss = bIndex % numIntBits;

	unsigned BIT_FRAME val = 1;

	val = val << bPoss;
	val = ~val;

	arr[arrIndex] = arr[arrIndex] & val;
	
}

int getBit(BIT_FRAME *arr, unsigned int bIndex)
{
	unsigned int arrIndex = bIndex/numIntBits;
	unsigned int bPoss = bIndex % numIntBits;

	unsigned BIT_FRAME val = 1;

	val = val << bPoss;

	if(arr[arrIndex]&val)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

void unmaskSieve(BIT_FRAME *arr, unsigned int arrLen, unsigned int prime)
{
	unsigned int i;
	unsigned start = 3*prime;//3xPrime skips the first even composit
	unsigned interval = 2*prime;//skips even numbers
	

	if(interval>prime)
	{
		unsigned int k = arrLen*numIntBits;
		for(i = start; (i<k) & (i+interval>prime) ; i += interval)
		{
			clearBit(arr, i);
		}
	}
}


//print 2 then all primes 
//(does not check to see if the bit array has even miss indicated as true)
void printPrimes(BIT_FRAME *arr, unsigned int arrLen, unsigned int maxPrime)
{
	unsigned int i;

	printf("2\n");

	for(i=3; i<= maxPrime; i += 2)
	{
		unsigned int iNext = i +2;
		if(iNext < i)
			break;

		if(getBit(arr, i))
			printf("%u\n", i);
	}
}

//set all bits to 1
void initArray(BIT_FRAME *arr, unsigned int arrLen)
{
	unsigned int i;

	//Set all bits to 1
	for( i = 0; i<arrLen ; i++)
	{
		arr[i] = 0;
		arr[i] = ~arr[i];
	}
}

//sets up memory shares
void * openSharedM(char *name, unsigned int size, int type)
{
	int fd;

	sem_t *addrS;
	unsigned char *addr;


	fd = shm_open(name, O_CREAT | O_RDWR, size);
	if(fd == -1)
	{
		perror("Error opening shared memory");
		exit(EXIT_FAILURE);
	}

	if(ftruncate(fd, size) == -1)
	{
		perror("Error truncating shared memory ");
		exit(EXIT_FAILURE);
	}
	
	if(type == 1)
	{
		addrS = (sem_t *)mmap(NULL, size, PROT_READ | PROT_WRITE, MAP_SHARED | MAP_ANONYMOUS, fd, 0);
	}
	else
	{
		addr = mmap(NULL, size, PROT_READ | PROT_WRITE, MAP_SHARED, fd, 0);
	}
	if(addr == MAP_FAILED)
	{
		shm_unlink(name);
		perror("Error mapping memory");
		exit(EXIT_FAILURE);
	}
	
	//close unused fd
	if(close(fd)==-1)
	{
		munmap(addr, size);
		shm_unlink(name);
		perror("Error closing file discriptor");
		exit(EXIT_FAILURE);
	}

	if(type == 1)
		return addrS;

	return addr;
}

//error for incorrect command line
void usageError(const char *progName)
{
	fprintf(stderr, "Usage: %s -q -t -m (2-4294967294) -c (1-10) \n", progName);
	fprintf(stderr, "	-q no output (DEFAULT OFF)\n");
	fprintf(stderr, "	-t time output only; overides -q (DEFAULT OFF)\n");
	fprintf(stderr, "	-c number of children (DEFAULT 1)\n");
	fprintf(stderr, "	-m maximum size of prime (DEFAULT UINT_MAX-1) \n");
	exit(EXIT_FAILURE);
}


int main(int argc, char *argv[])
{
	int i;
	char options;
	int timer = 0;
	unsigned int testMax = UINT_MAX-1;
	int numChildren = 1;
	int verbose = 1;

	while( (options = getopt(argc, argv, "tqc:m:" )) != -1)
	{
		switch(options)
		{
			case 'q':
				verbose = 0;
				break;
			case 'm':
				testMax = atoi(optarg);
				if( (testMax < 2 ) | (testMax >= UINT_MAX) )
					usageError(argv[0]);
				break;
			case 'c':
				numChildren = atoi(optarg);
				if( (numChildren > 10 ) | (numChildren < 1))
					usageError(argv[0]);
				break;
			case 't':
				timer = 1;
				verbose = 0;
				break;
			default:
				usageError(argv[0]);
		}
	}

	//unsigned int maxPrime = UINT_MAX;
	unsigned int maxPrime = testMax;
	unsigned int displayMax = testMax;
	if(maxPrime<32)
		maxPrime = 32;

	unsigned int arrLen = (unsigned int) ceil(maxPrime/numIntBits)+1;

	unsigned int nextPrime;
	unsigned int lastPrime;

	unsigned char *addr;
	unsigned char *primeAddr;
	unsigned char *doneAddr;
	sem_t *semiAddr;

	int children;

	int *bitArray;
	unsigned int *nextPrimeForC;
	int *done;

	time_t totalTime;
	struct timespec startTime;
	struct timespec endTime;

	clock_gettime(CLOCK_REALTIME, &startTime);

	//closes open shares that were improperly closed if they exist
	shm_unlink("/primeBitArray");
	shm_unlink("/primeAddr");
	shm_unlink("/done");
	shm_unlink("/sem");

	//create new shares
	addr = (unsigned char *)openSharedM("/primeBitArray", arrLen*sizeof(BIT_FRAME)+1, 0);
	primeAddr = (unsigned char *)openSharedM("/primeAddr", sizeof(unsigned int), 0);
	doneAddr = (unsigned char *)openSharedM("/done", sizeof(int), 0);
	semiAddr = (sem_t *)openSharedM("/sem", sizeof(sem_t)*3, 0);

	//closes shares on crash
	shm_unlink("/primeBitArray");
	shm_unlink("/primeAddr");
	shm_unlink("/done");
	shm_unlink("/sem");

	bitArray = (int *)(addr);
	done = (int *)(doneAddr);
	nextPrimeForC = (unsigned int *)primeAddr;
	
	//needNextTestSemi = semiAddr[0];
	//readingSemi = semiAddr[1];
	//readyToReadSemi = semiAddr[2];

	//tells children when to die
	*done = 0;

	if(sem_init(&semiAddr[0], 1, 0) == -1)
	{
		perror("Error while init of semi.");
		exit(EXIT_FAILURE);
	}
	if(sem_init(&semiAddr[1], 1, 1) == -1)
	{
		perror("Error while init of semi.");
		exit(EXIT_FAILURE);
	}
	if(sem_init(&semiAddr[2], 1, 0) == -1)
	{
		perror("Error while init of semi.");
		exit(EXIT_FAILURE);
	}

	//set next prime read lock to prevent child deadlock
	if(sem_wait(&semiAddr[1]) == -1)
	{
		perror("Error while waiting on sem.");
		exit(EXIT_FAILURE);
	}

	//set array to 1
	initArray(bitArray, arrLen);

	//create processes for setting sieves
	for(i = 0; i < numChildren ; i++)
	{
		children = fork();
		switch(children)
		{
			case -1:
					perror("Error while forking.");
					exit(EXIT_FAILURE);
			case 0:
		
					while(*done == 0)
					{
						//wait until the next prime has been identified
						if(sem_wait(&semiAddr[0]) == -1)
						{
							perror("Error while waiting on sem.");
							exit(EXIT_FAILURE);
						}

						//quit if no more work
						if(*done == 1)
							break;

						//lock the variable untill read
						if(sem_wait(&semiAddr[1]) == -1)
						{
							perror("Error while waiting on sem.");
							exit(EXIT_FAILURE);
						}

						nextPrime = *nextPrimeForC;
						//unlock the variable after reading
						if(sem_post(&semiAddr[1]) == -1)
						{
							perror("Error while posting on sem.");
							exit(EXIT_FAILURE);
						}

						//tell main to get new value
						if(sem_post(&semiAddr[2]) == -1)
						{
							perror("Error while posting semi.");
							exit(EXIT_FAILURE);
						}

						unmaskSieve(bitArray, arrLen, nextPrime);

					}
					exit(EXIT_SUCCESS);
			default:
					break;
		}
	}

	nextPrime = lastPrime = 3;

	//get first prime
	if(nextPrime < maxPrime)
	{
		*nextPrimeForC = nextPrime;

		//unset next prime read lock to prevent child deadlock
		if(sem_post(&semiAddr[1]) == -1)
		{
			perror("Error while posting sem.");
			exit(EXIT_FAILURE);
		}

		//tell children one value is avalible
		if(sem_post(&semiAddr[0]) == -1)
		{
			perror("Error while posting semi.");
			exit(EXIT_FAILURE);
		}

	}

//	nextPrime = lastPrime = 1;

	//get next prime and post it to nextPrimeForC
	while(nextPrime < maxPrime){

		nextPrime += 2;

		//break if overflow
		if(nextPrime < lastPrime)
			break;

		//check for prime and overflow
		while(!(getBit(bitArray, nextPrime)) & (nextPrime > lastPrime))
		{
			lastPrime = nextPrime;
			nextPrime += 2;
		}

		//break if overflow
		if(nextPrime < lastPrime)
			break;
		
		//wait for a child to read the current value
		if(sem_wait(&semiAddr[2]) == -1)
		{
			perror("Error while waiting on sem.");
			exit(EXIT_FAILURE);
		}

		//set next prime read lock to prevent child reading old info
		//also prevents writing while another is reading
		if(sem_wait(&semiAddr[1]) == -1)
		{
			perror("Error while waiting on sem.");
			exit(EXIT_FAILURE);
		}

		*nextPrimeForC = nextPrime;

		//unset next prime read lock when new info is posted
		if(sem_post(&semiAddr[1]) == -1)
		{
			perror("Error while waiting on sem.");
			exit(EXIT_FAILURE);
		}

		//let children know new info is avalible
		if(sem_post(&semiAddr[0]) == -1)
		{
			perror("Error while posting on sem.");
			exit(EXIT_FAILURE);
		}
	}


	//tell children to die
	*done = 1;
	
	//tell children to stop waiting and die
	for(i = 0; i<numChildren; i++)
	{
		if(sem_post(&semiAddr[0]) == -1)
		{
			perror("Error while posting on sem.");
			exit(EXIT_FAILURE);
		}
	}

	//clean up zombie children 
	//needed before printing primes in case they are still working
	for(i = 0; i<numChildren;i++){	
		wait(NULL);
	}

	clock_gettime(CLOCK_REALTIME, &endTime);
	totalTime = (endTime.tv_sec - startTime.tv_sec);

	if(timer)
		printf("%u \n", (unsigned int)totalTime);

	//print a single colunm list of primes
	if(verbose)
		printPrimes(bitArray, arrLen, displayMax);

	//clean up
	munmap(addr, arrLen*sizeof(BIT_FRAME));
	//free(bitArray); 

	if(sem_destroy(&semiAddr[0]) == -1)
	{
		perror("Error while destroying semi.");
		exit(EXIT_FAILURE);
	}
	if(sem_destroy(&semiAddr[1]) == -1)
	{
		perror("Error while destroying semi.");
		exit(EXIT_FAILURE);
	}
	if(sem_destroy(&semiAddr[2]) == -1)
	{
		perror("Error while destroying semi.");
		exit(EXIT_FAILURE);
	}

	exit(EXIT_SUCCESS);
}