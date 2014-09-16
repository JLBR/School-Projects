#include <fcntl.h>
#include <sys/stat.h>
#include <stdlib.h>
#include <stdio.h>
#include <limits.h>
#include <math.h>
#include <semaphore.h>
#include <unistd.h>
#include <pthread.h>
#include <sys/wait.h>
#include <time.h>

#define BIT_FRAME int

const unsigned int numIntBits = (sizeof(BIT_FRAME)*8);

//int *done, unsigned int *nextPrimeForC, int *bitArray, unsigned int arrLen
struct uMaskArg{
	int *done;
	unsigned int *nextPrimeForC;
	int *bitArray;
	unsigned int arrLen;
};

sem_t needNextTestSemi;
sem_t readingSemi;
sem_t readyToReadSemi;

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

static void threadUnmask(void * args)
{
	int nextPrime;
	struct uMaskArg *uMask = (struct uMaskArg *)args;

	while(*uMask->done == 0)
	{
		//wait until the next prime has been identified
		if(sem_wait(&needNextTestSemi) == -1)
		{
			perror("Error while waiting on sem.");
			exit(EXIT_FAILURE);
		}

		//quit if no more work
		if(*uMask->done == 1)
			break;

		//lock the variable untill read
		if(sem_wait(&readingSemi) == -1)
		{
			perror("Error while waiting on sem.");
			exit(EXIT_FAILURE);
		}

		nextPrime = *uMask->nextPrimeForC;
		//unlock the variable after reading
		if(sem_post(&readingSemi) == -1)
		{
			perror("Error while posting on sem.");
			exit(EXIT_FAILURE);
		}

		//tell main to get new value
		if(sem_post(&readyToReadSemi) == -1)
		{
			perror("Error while posting semi.");
			exit(EXIT_FAILURE);
		}

		unmaskSieve(uMask->bitArray, uMask->arrLen, nextPrime);

	}

}

//error for incorrect command line
void usageError(const char *progName)
{
	fprintf(stderr, "Usage: %s -q -t -m (2-4294967294) -c (1-10) \n", progName);
	fprintf(stderr, "	-q no output (DEFAULT OFF)\n");
	fprintf(stderr, "	-t time output only; overides -q (DEFAULT OFF)\n");
	fprintf(stderr, "	-c number of threads (DEFAULT 1)\n");
	fprintf(stderr, "	-m maximum size of prime (DEFAULT UINT_MAX-1) \n");
	exit(EXIT_FAILURE);
}


int main(int argc, char *argv[])
{
	int i;
	char options;
	int timer = 0;
	unsigned int testMax = UINT_MAX-1;
	int numThreads = 1;
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
				numThreads = atoi(optarg);
				if( (numThreads > 10 ) | (numThreads < 1))
					usageError(argv[0]);
				break;
			case 't':
				timer = 1;
				verbose = 0;
				break;
			default:
				usageError(argv[0]);
				exit(EXIT_FAILURE);
		}
	}

	//unsigned int maxPrime = UINT_MAX;
	unsigned int maxPrime = testMax;
	unsigned int displayMax = testMax;
	if(maxPrime<32)
		maxPrime = 32;

	unsigned int arrLen = (unsigned int) ceil(maxPrime/numIntBits)+1;
	BIT_FRAME *bitArray = malloc(arrLen*sizeof(BIT_FRAME));

	unsigned int nextPrime;
	unsigned int lastPrime;

	pthread_t thr;
	pthread_attr_t attr;
	int tError;

	unsigned int nextPrimeForC;
	int done;

	struct uMaskArg uMask;

	time_t totalTime;
	struct timespec startTime;
	struct timespec endTime;

	clock_gettime(CLOCK_REALTIME, &startTime);

	//tells threads when to die
	done = 0;

	uMask.done = &done;
	uMask.nextPrimeForC = &nextPrimeForC;
	uMask.arrLen = arrLen;
	uMask.bitArray = bitArray;
	
	if(sem_init(&needNextTestSemi, 1, 0) == -1)
	{
		perror("Error while init of semi.");
		exit(EXIT_FAILURE);
	}
	if(sem_init(&readingSemi, 1, 1) == -1)
	{
		perror("Error while init of semi.");
		exit(EXIT_FAILURE);
	}
	if(sem_init(&readyToReadSemi, 1, 0) == -1)
	{
		perror("Error while init of semi.");
		exit(EXIT_FAILURE);
	}

	//set next test read lock to prevent thread deadlock
	if(sem_wait(&readingSemi) == -1)
	{
		perror("Error while waiting on sem.");
		exit(EXIT_FAILURE);
	}

	//set array to 1
	initArray(bitArray, arrLen);

	//create threads for setting sieves

	tError = pthread_attr_init(&attr);
	if(tError != 0)
	{
		perror("Error inti pthread");
		exit(EXIT_FAILURE);
	}

	tError = pthread_attr_setdetachstate(&attr, PTHREAD_CREATE_DETACHED);
	if(tError != 0)
	{
		perror("Error setting detatched pthread");
		exit(EXIT_FAILURE);
	}

	for(i = 0; i < numThreads; i++)
	{ 
		tError = pthread_create(&thr, &attr, (void *)threadUnmask, &uMask );
		if(tError != 0)
		{
			perror("Error creating pthread");
			exit(EXIT_FAILURE);
		}
	}

	tError = pthread_attr_destroy(&attr);
	if(tError != 0)
	{
		perror("Error destroying attr pthread");
		exit(EXIT_FAILURE);
	}

	nextPrime = lastPrime = 3;

	//get first prime
	if(nextPrime < maxPrime)
	{
		nextPrimeForC = nextPrime;

		//unset next prime read lock to prevent child deadlock
		if(sem_post(&readingSemi) == -1)
		{
			perror("Error while posting sem.");
			exit(EXIT_FAILURE);
		}

		//tell threads one value is avalible
		if(sem_post(&needNextTestSemi) == -1)
		{
			perror("Error while posting semi.");
			exit(EXIT_FAILURE);
		}

	}

	//nextPrime = lastPrime = 1;

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
		if(sem_wait(&readyToReadSemi) == -1)
		{
			perror("Error while waiting on sem.");
			exit(EXIT_FAILURE);
		}

		//set next prime read lock to prevent thread reading old info
		//also prevents writing while another is reading
		if(sem_wait(&readingSemi) == -1)
		{
			perror("Error while waiting on sem.");
			exit(EXIT_FAILURE);
		}

		nextPrimeForC = nextPrime;

		//unset next prime read lock when new info is posted
		if(sem_post(&readingSemi) == -1)
		{
			perror("Error while waiting on sem.");
			exit(EXIT_FAILURE);
		}

		//let threads know new info is avalible
		if(sem_post(&needNextTestSemi) == -1)
		{
			perror("Error while posting on sem.");
			exit(EXIT_FAILURE);
		}
	}

	//tell threads to die
	done = 1;
	
	//tell threads to stop waiting and die
	for(i = 0; i<numThreads; i++)
	{
		if(sem_post(&needNextTestSemi) == -1)
		{
			perror("Error while posting on sem.");
			exit(EXIT_FAILURE);
		}
	}

	clock_gettime(CLOCK_REALTIME, &endTime);
	totalTime = (endTime.tv_sec - startTime.tv_sec);

	if(timer)
		printf("%u \n", (unsigned int)totalTime);

	//print a single colunm list of primes
	if(verbose)
		printPrimes(bitArray, arrLen, displayMax);

	//clean up
	free(bitArray); 

	if(sem_destroy(&needNextTestSemi) == -1)
	{
		perror("Error while destroying semi.");
		exit(EXIT_FAILURE);
	}
	if(sem_destroy(&readingSemi) == -1)
	{
		perror("Error while destroying semi.");
		exit(EXIT_FAILURE);
	}
	if(sem_destroy(&readyToReadSemi) == -1)
	{
		perror("Error while destroying semi.");
		exit(EXIT_FAILURE);
	}

	exit(EXIT_SUCCESS);
}