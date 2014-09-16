/* 
 * Date:10 Mar 2013
 * Solution description: This file contains the ADT for linked list hash tables
 * with two hashing algorithms.
 */

#include <stdlib.h>
#include "hashMap.h"
#include "assert.h"
#include "string.h"

#if HASHING_FUNCTION == 1
#define HASH stringHash1
#else
#define HASH stringHash2
#endif

/*the first hashing function you can use*/
int stringHash1(char * str)
{
    int i;
    int r = 0;
    for (i = 0; str[i] != '\0'; i++)
        r += str[i];
    return r;
}

/*the second hashing function you can use*/
int stringHash2(char * str)
{
    int i;
    int r = 0;
    for (i = 0; str[i] != '\0'; i++)
        r += (i+1) * str[i]; /*the difference between 1 and 2 is on this line*/
    return r;
}

/*initialize the supplied hashMap struct*/
void initMap (struct hashMap * ht, int tableSize)
{
    int index;
    
    if(ht == NULL)
        return;
    ht->table = (hashLink**)malloc(sizeof(hashLink*) * tableSize);
    ht->tableSize = tableSize;
    ht->count = 0;
    for(index = 0; index < tableSize; index++)
        ht->table[index] = NULL;
}

/*
 free all memory used for your hashMap, but do not free the pointer to ht. you
 can see why this would not work by examining main(). the hashMap passed into
 your functions was never malloc'ed, so it can't be free()'ed either.
 */
void freeMap (struct hashMap * ht)
{
    int i;

    assert(ht != NULL);

    for(i = 0;i<ht->tableSize;i++){
	   
	   struct hashLink* oldLink = ht->table[i];

	   while(oldLink != NULL){
		  struct hashLink* currentLink = oldLink;
		  oldLink = currentLink->next;
		  free(currentLink->key);
		  //free(currentLink->value);
		  free(currentLink);
	   }
    }
    ht->count = 0;
}

/*
 insert the following values into a hashLink, you must create this hashLink but
 only after you confirm that this key does not already exist in the table. you
 cannot have two hashLinks for the word "taco".
 
 if a hashLink already exists in the table for the key provided you should
 replace that hashLink (really this only requires replacing the value v).
 */
void insertMap (struct hashMap * ht, KeyType k, ValueType v)
{
    //assert(ht != NULL);

    ValueType* exists = atMap(ht, k);

    if(exists != NULL){
	   *exists = v;
    }else{

	   int bucket = HASH(k) % ht->tableSize;
	   hashLink* insertLink = ht->table[bucket];
	   
	   hashLink* newLink = (hashLink *)malloc(sizeof(hashLink));
	   assert(newLink != NULL);

	   newLink->value = v;
	   newLink->next = NULL;
	   newLink->key = k;

	   if(insertLink != NULL){
		  
		  while(insertLink->next != NULL){
			 insertLink = insertLink->next;
		  }
		  insertLink->next = newLink;

	   }else{
		  ht->table[bucket] = newLink;
	   }
	   ht->count++;
    }
}

/*
 this returns a POINTER to the value stored in a hashLink specified by the key k.
 
 if the user supplies the key "taco" you should find taco in the hashTable, then
 return a pointer to the value member of the hashLink that represents taco. keep
 in mind we are storing an int for value, so you don't use malloc or anything.
 
 if the supplied key is not in the hashtable return NULL.
 */
ValueType* atMap (struct hashMap * ht, KeyType k)
{
    //assert(ht != NULL);

    int bucket = HASH(k) % ht->tableSize;
    ValueType* returnValue = NULL;

    hashLink* returnLink = ht->table[bucket];

    while(returnLink != NULL){
	   if(strncmp(returnLink->key, k, 100) == 0)
		  break;
	   returnLink = returnLink->next;
    }

    if(returnLink != NULL)
	   returnValue = &returnLink->value;

    return returnValue;
}

/*
 a simple yes/no if the key is in the hashtable. 0 is no, all other values are
 yes.
 */
int containsKey (struct hashMap * ht, KeyType k)
{
    int contains = 0;

    assert(ht != NULL);

    if( atMap(ht, k) != NULL)
	   contains = 1;

    return contains;
}

/*
 find the hashlink for the supplied key and remove it, also freeing the memory
 for that hashlink. it is not an error to be unable to find the hashlink, if it
 cannot be found do nothing (or print a message) but do not use an assert which
 will end your program.
 */
void removeKey (struct hashMap *ht, KeyType k)
{
    //assert(ht != NULL);

    int bucket = HASH(k) % ht->tableSize;

    hashLink* removeLink = ht->table[bucket];
    hashLink* previousLink = removeLink;

    if(strncmp(removeLink->key, k, 100) != 0){//if not first link
	   while(removeLink !=  NULL){
		  if(strncmp(removeLink->key, k, 100) == 0){
			 previousLink->next = removeLink->next;
			 ht->count--;
			 free(removeLink);
			 removeLink = previousLink;
		  }else{
			 previousLink = removeLink;
			 removeLink = removeLink->next;
		  }
	   }
    }else{//if first link
	   ht->table[bucket] = removeLink->next;
	   ht->count--;
	   free(removeLink);
    }

}

/*
 returns the number of hashLinks in the table
 */
int size (struct hashMap *ht)
{
    assert(ht != NULL);
    return ht->count;
}

/*
 returns the number of buckets in the table
 */
int capacity(struct hashMap *ht)
{
    assert(ht != NULL);
    return ht->tableSize;
}

/*
 returns the number of empty buckets in the table, these are buckets which have
 no hashlinks hanging off of them.
 */
int emptyBuckets(struct hashMap *ht)
{
    int emptyBucket = 0;
    int i;

    assert(ht != NULL);

    for(i = 0;i<ht->tableSize;i++){
	   if(ht->table[i] == NULL)
		  emptyBucket++;
    }
    return emptyBucket;
}

/*
 returns the ratio of: (number of hashlinks) / (number of buckets)
 
 this value can range anywhere from zero (an empty table) to more then 1, which
 would mean that there are more hashlinks then buckets (but remember hashlinks
 are like linked list nodes so they can hang from each other)
 */
float tableLoad(struct hashMap *ht)
{
    assert(ht != NULL);
    return ((float)size(ht)/capacity(ht));
}
