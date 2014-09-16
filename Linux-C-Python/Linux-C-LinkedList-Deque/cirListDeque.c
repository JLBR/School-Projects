/* Assignment 3
 * Name:Jimmy 
 * Date:3 Feb 2013
 * Solution description: This file creates a circularly double linked list with a Deque interface.
 * With minor addition of methods this can handle the bag and stack interfaces too.
 */

#include <stdio.h>
#include <stdlib.h>
#include <assert.h>
#include <float.h>
#include "cirListDeque.h"

# define TYPE_SENTINEL_VALUE DBL_MAX 
#define TRUE 1
#define FALSE 0

/* ************************************************************************
 Deque ADT based on Circularly-Doubly-Linked List WITH Sentinel
 ************************************************************************ */
/* Double Link Struture */
struct DLink {
	TYPE value;/* value of the link */
	struct DLink * next;/* pointer to the next link */
	struct DLink * prev;/* pointer to the previous link */
};

struct cirListDeque {
	int size;/* number of links in the deque */
	struct DLink *Sentinel;	/* pointer to the sentinel */
};

/* internal functions prototypes */
struct DLink* _createLink (TYPE val);
void _addLinkAfter(struct cirListDeque *q, struct DLink *lnk, struct DLink *newLnk);
void _removeLink(struct cirListDeque *q, struct DLink *lnk);



/* ************************************************************************
	Deque Functions
************************************************************************ */

/* Initialize deque.

	param: 	q		pointer to the deque
	pre:	q is not null
	post:	q->backSentinel is allocated and q->size equals zero
*/
void _initCirListDeque (struct cirListDeque *q) 
{
  	assert(q != NULL);

	q->Sentinel = malloc(sizeof(struct DLink));
	q->size = 0;
	q->Sentinel->next = q->Sentinel;
	q->Sentinel->prev = q->Sentinel;
	q->Sentinel->value = 0;

	assert(q->Sentinel != NULL);
	assert(q->size == 0);
}

/*
 create a new circular list deque
 
 */

struct cirListDeque *createCirListDeque()
{
	struct cirListDeque *newCL = malloc(sizeof(struct cirListDeque));
	_initCirListDeque(newCL);
	return(newCL);
}


/* Create a link for a value.

	param: 	val		the value to create a link for
	pre:	none
	post:	a link to store the value
*/
struct DLink * _createLink (TYPE val)
{
    struct DLink *newLink = malloc(sizeof(struct DLink));
    newLink->value = val;
    assert(newLink != NULL);
    return(newLink);	 
}

/* Adds a link after another link

	param: 	q		pointer to the deque
	param: 	lnk		pointer to the existing link in the deque
	param: 	newLnk	pointer to the new link to add after the existing link
	pre:	q is not null
	pre: 	lnk and newLnk are not null
	post:	the new link is added into the deque after the existing link
*/
void _addLinkAfter(struct cirListDeque *q, struct DLink *lnk, struct DLink *newLnk)
{
    assert(q != NULL);
    assert(lnk != NULL);

    newLnk->next = lnk->next;
    newLnk->prev = lnk;
 
    lnk->next = newLnk;
    newLnk->next->prev = newLnk;
    q->size++;

    assert(q->size != 0);	 
}

/* Adds a link to the back of the deque

	param: 	q		pointer to the deque
	param: 	val		value for the link to be added
	pre:	q is not null
	post:	a link storing val is added to the back of the deque
*/
void addBackCirListDeque (struct cirListDeque *q, TYPE val) 
{
	assert(q != NULL);
	struct DLink* newLink = _createLink(val);
	_addLinkAfter(q, q->Sentinel->prev , newLink);
	assert(q->Sentinel->prev->value == val);
}

/* Adds a link to the front of the deque

	param: 	q		pointer to the deque
	param: 	val		value for the link to be added
	pre:	q is not null
	post:	a link storing val is added to the front of the deque
*/
void addFrontCirListDeque(struct cirListDeque *q, TYPE val)
{
	assert(q != NULL);
	struct DLink* newLink = _createLink(val);
	_addLinkAfter(q, q->Sentinel , newLink);
	assert(q->Sentinel->next->value == val);	
}

/* Get the value of the front of the deque

	param: 	q		pointer to the deque
	pre:	q is not null and q is not empty
	post:	none
	ret: 	value of the front of the deque
*/
TYPE frontCirListDeque(struct cirListDeque *q) 
{
    assert(q !=NULL);
    assert(q->size != 0);
    return(q->Sentinel->next->value);
}

/* Get the value of the back of the deque

	param: 	q		pointer to the deque
	pre:	q is not null and q is not empty
	post:	none
	ret: 	value of the back of the deque
*/
TYPE backCirListDeque(struct cirListDeque *q)
{
    assert(q !=NULL);
    assert(q->size != 0);
    return(q->Sentinel->prev->value);
}

/* Remove a link from the deque

	param: 	q		pointer to the deque
	param: 	lnk		pointer to the link to be removed
	pre:	q is not null and q is not empty
	post:	the link is removed from the deque
*/
void _removeLink(struct cirListDeque *q, struct DLink *lnk)
{
    assert(q !=NULL);
    assert(lnk != NULL);
    assert(q->size != 0);

    lnk->prev->next = lnk->next;
    lnk->next->prev = lnk->prev;

    q->size--;

    free(lnk);
}

/* Remove the front of the deque

	param: 	q		pointer to the deque
	pre:	q is not null and q is not empty
	post:	the front is removed from the deque
*/
void removeFrontCirListDeque (struct cirListDeque *q) {
    assert(q !=NULL);
    assert(q->size != 0);

    struct DLink *frontLink = q->Sentinel->next;
    _removeLink(q, frontLink);
    //assert(frontLink == NULL);
}


/* Remove the back of the deque

	param: 	q		pointer to the deque
	pre:	q is not null and q is not empty
	post:	the back is removed from the deque
*/
void removeBackCirListDeque(struct cirListDeque *q)
{
    assert(q !=NULL);
    assert(q->size != 0);
    struct DLink *backLink = q->Sentinel->prev;
    _removeLink(q, backLink);
    //assert(backLink == NULL);
}

/* De-allocate all links of the deque

	param: 	q		pointer to the deque
	pre:	none
	post:	All links (including backSentinel) are de-allocated
*/
void freeCirListDeque(struct cirListDeque *q)
{
    int size = q->size;
    for(int i = 0; i< size; i++){
	   _removeLink(q, q->Sentinel->next);
    }
    free(q);
    //assert(q->Sentinel == NULL);
}

/* Check whether the deque is empty

	param: 	q		pointer to the deque
	pre:	q is not null
	ret: 	1 if the deque is empty. Otherwise, 0.
*/
int isEmptyCirListDeque(struct cirListDeque *q) {
    
    int isEmpty = FALSE;
    if(q->size <= 0){
	   isEmpty = TRUE;
    }
  
    return(isEmpty);
}

/* Print the links in the deque from front to back

	param: 	q		pointer to the deque
	pre:	q is not null and q is not empty
	post: 	the links in the deque are printed from front to back
*/
void printCirListDeque(struct cirListDeque *q)
{
	assert(q != NULL);
	assert(q->size != 0);

	struct DLink* tempLink = q->Sentinel;
	for(int i =0; i<q->size;i++){
	    printf("%g\n", tempLink->next->value);
	    tempLink = tempLink->next;
	}

	//assert(q->Sentinel == tempLink->next);
}

/* Reverse the deque

	param: 	q		pointer to the deque
	pre:	q is not null and q is not empty
	post: 	the deque is reversed
*/
void reverseCirListDeque(struct cirListDeque *q)
{
    assert(q != NULL);

    struct DLink* swapLink = q->Sentinel;
    struct DLink* tempLink = swapLink->next;

    for(int i = 0; i <q->size+1; i++){
	   swapLink->next = swapLink->prev;
	   swapLink->prev = tempLink;
	   swapLink = tempLink;
	   tempLink = tempLink->next;
    }
}
