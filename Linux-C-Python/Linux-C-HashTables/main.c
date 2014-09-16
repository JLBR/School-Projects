/* 
 * Date:10 Mar 2013
 * Solution description: This is the main program implementing a hash table 
 * that counts the number of times a word was used in a file.
 */

#include <assert.h>
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include "hashMap.h"
#define MAIN 1  //0 for spellcheck, 1 for word count

/*
 the getWord function takes a FILE pointer and returns you a string which was
 the next word in the in the file. words are defined (by this function) to be
 characters or numbers seperated by periods, spaces, or newlines.
 
 when there are no more words in the input file this function will return NULL.
 
 this function will malloc some memory for the char* it returns. it is your job
 to free this memory when you no longer need it.
 */
char* getWord(FILE *file);

#if MAIN == 1
int main (int argc, const char * argv[]) {
    const char* filename;
    struct hashMap hashTable;
    int tableSize = 997;
    clock_t timer;
    FILE * readFile;
    char* nextWord;
    int i = 0;
    struct hashMap* tablePTR = &hashTable;

    /*
	   this part is using command line arguments, you can use them if you wish
	   but it is not required. DO NOT remove this code though, we will use it for
	   testing your program.
     
	   if you wish not to use command line arguments manually type in your
	   filename and path in the else case.
	   */
    if(argc == 2)
	   filename = argv[1];
    else
	   filename = "input.txt"; /*specify your input text file here*/
    
    printf("opening file: %s\n", filename);
    readFile = fopen(filename, "r");

    printf("File text is: ");

    timer = clock();
	
    initMap(&hashTable, tableSize);
    
    nextWord = getWord(readFile);

    while(nextWord != NULL){

	   ValueType* value = atMap(&hashTable, nextWord);

	   if(value == NULL){
		  insertMap(&hashTable, nextWord, 1);
		  printf("%s ",nextWord);
	   }else{
		  printf("%s ",nextWord);
		  *value += 1;
		  free(nextWord);
	   }
	   nextWord = getWord(readFile);
    }
    printf("\n\nThe following words were used:\n\n"); 

    //removeKey(&hashTable, "we");

    //insertMap(&hashTable, "dongle", 999);

    for(i = 0; i<capacity(&hashTable);i++){
	   struct hashLink* tempLink = tablePTR->table[i];

	   while(tempLink != NULL){
		  printf("%s: %d \n\n", tempLink->key, tempLink->value);
		  tempLink = tempLink->next;
	   }
    }

    timer = clock() - timer;
    printf("concordance ran in %f seconds\n", (float)timer / (float)CLOCKS_PER_SEC);
    
    fclose(readFile);

    //printf("Number of empty buckets: %d\n", emptyBuckets(&hashTable));
    //printf("Table load: %f\n", tableLoad(&hashTable));
    //printf("Contains the word 'Z': %d\n", atMap(&hashTable, "Z"));

    freeMap(&hashTable);

    return 0;
}


char* getWord(FILE *file)
{
	int length = 0;
	int maxLength = 16;
	char character;
    
	char* word = (char*)malloc(sizeof(char) * maxLength);
	assert(word != NULL);
    
	while( (character = fgetc(file)) != EOF)
	{
		if((length+1) > maxLength)
		{
			maxLength *= 2;
			word = (char*)realloc(word, maxLength);
		}
		if(character >= '0' && character <= '9' || /*is a number*/
		   character >= 'A' && character <= 'Z' || /*or an uppercase letter*/
		   character >= 'a' && character <= 'z' || /*or a lowercase letter*/
		   character == 39) /*or is an apostrophy*/
		{
			word[length] = character;
			length++;
		}
		else if(length > 0)
			break;
	}
    
	if(length == 0)
	{
		free(word);
		return NULL;
	}
	word[length] = '\0';
	return word;
}
#endif