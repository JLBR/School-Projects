/* Assignment 2
 * Name:Jimmy 
 * Date:20 Jan 2013
 * Solution description: This file creates a doubly linked list with a bag and Deque interface.
 */

#include "linkedList.h"
#include <assert.h>
#include <stdlib.h>
#include <stdio.h>

#define FALSE 0
#define TRUE 1


/* Double Link*/
struct DLink {
	TYPE value;
	struct DLink * next;
	struct DLink * prev;
};

/* Double Linked List with Head and Tail Sentinels  */

struct linkedList{
	int size;
	struct DLink *firstLink;
	struct DLink *lastLink;
};


/*
	initList
	param lst the linkedList
	pre: lst is not null
	post: lst size is 0
*/

void _initList (struct linkedList *lst) {
    
    assert(lst != NULL);

    lst->firstLink = (struct DLink *) malloc(sizeof(struct DLink));
    lst->lastLink = (struct DLink *) malloc(sizeof(struct DLink));

    lst->firstLink->next = lst->lastLink;
    lst->lastLink->prev = lst->firstLink;

    lst->firstLink->prev = NULL;
    lst->firstLink->value = 0;
    lst->lastLink->next = NULL;
    lst->lastLink->value = 0;

    lst->size = 0;

    assert(lst->size ==0);
}

/*
 createList
 param: none
 pre: none
 post: firstLink and lastLink reference sentinels
 */

struct linkedList *createLinkedList()
{
	struct linkedList *newList = malloc(sizeof(struct linkedList));
	_initList(newList);
	return(newList);
}

/*
	_addLinkBeforeBefore
	param: lst the linkedList
	param: l the  link to add before
	param: v the value to add
	pre: lst is not null
	pre: l is not null
	post: lst is not empty
*/

/* Adds Before the provided link, l */

void _addLinkBefore(struct linkedList *lst, struct DLink *l, TYPE v)
{
    assert(lst != NULL);
    assert(l != NULL);

    struct DLink *newLink = (struct DLink *) malloc(sizeof(struct DLink));

    newLink->next = l;
    newLink->prev = l->prev;
    newLink->value = v;

    l->prev = newLink;
    newLink->prev->next = newLink;
    lst->size++;

    assert(lst->size != 0);
}


/*
	addFrontList
	param: lst the linkedList
	param: e the element to be added
	pre: lst is not null
	post: lst is not empty, increased size by 1
*/

void addFrontList(struct linkedList *lst, TYPE e)
{
	assert(lst != NULL);
	//printf("added %d\n", e);
	_addLinkBefore(lst, lst->firstLink->next , e);
	assert(lst->size != 0);	
}

/*
	addBackList
	param: lst the linkedList
	pre: lst is not null
	post: lst is not empty
*/

void addBackList(struct linkedList *lst, TYPE e) 
{
	assert(lst != NULL);
	_addLinkBefore(lst, lst->lastLink , e);
	assert(lst->size != 0);	
}

/*
	frontList
	param: lst the linkedList
	pre: lst is not null
	pre: lst is not empty
	post: none
*/

TYPE frontList (struct linkedList *lst) {
    assert(lst != NULL);
    assert(lst->size != 0);

    return(lst->firstLink->next->value);
}

/*
	backList
	param: lst the linkedList
	pre: lst is not null
	pre: lst is not empty
	post: lst is not empty
*/

TYPE backList(struct linkedList *lst)
{
    assert(lst != NULL);
    assert(lst->size != 0);

    TYPE returnValue = lst->lastLink->prev->value;

    assert(lst->size !=0);
    return(returnValue);
}

/*
	_removeLink
	param: lst the linkedList
	param: l the linke to be removed
	pre: lst is not null
	pre: l is not null
	post: lst size is reduced by 1
*/

void _removeLink(struct linkedList *lst, struct DLink *l)
{
    assert(lst != NULL);
    assert(l != NULL);

    l->prev->next = l->next;
    l->next->prev = l->prev;

    free(l);
    lst->size--;
}

/*
	removeFrontList
	param: lst the linkedList
	pre:lst is not null
	pre: lst is not empty
	post: size is reduced by 1
*/

void removeFrontList(struct linkedList *lst) {
    assert(lst != NULL);
    assert(lst->size != 0);
	
    _removeLink(lst, lst->firstLink->next);
}

/*
	removeBackList
	param: lst the linkedList
	pre: lst is not null
	pre:lst is not empty
	post: size reduced by 1
*/

void removeBackList(struct linkedList *lst)
{	
    assert(lst != NULL);
    assert(lst->size != 0);

    _removeLink(lst, lst->lastLink->prev);
}

/*
	isEmptyList
	param: lst the linkedList
	pre: lst is not null
	post: none
*/

int isEmptyList(struct linkedList *lst) {
    assert(lst != NULL);

    int isEmpty = FALSE;
    if(lst->size<=0){
	   isEmpty = TRUE;
    }
    return(isEmpty);
}


/* Function to print list
 Pre: lst is not null
 */
void _printList(struct linkedList* lst)
{
	assert(lst != NULL);
	struct DLink *tempLink = lst->firstLink;

	for(int i = 0; i<lst->size;i++){    
	    printf("%d\n", tempLink->next->value);
	    tempLink = tempLink->next;
	}
}

/* Iterative implementation of contains() 
 Pre: lst is not null
 */

void addList(struct linkedList *lst, TYPE v)
{
	assert(lst != NULL);
	addFrontList(lst, v);
}

/* Iterative implementation of contains() 
 Pre: lst is not null
 pre: list is not empty
 */
int containsList (struct linkedList *lst, TYPE e) {
    assert(lst != NULL);
    assert(lst->size != 0);

    int returnValue = FALSE;
    struct DLink *tempLink = lst->firstLink;
    int index = 0;

    while((returnValue == FALSE) &&(index < lst->size))
    {
	   if(tempLink->next->value == e){
		  returnValue = TRUE;
	   }else{
		  index++;
		  tempLink = tempLink->next;
	   }
    }

    return(returnValue);
}

/* Iterative implementation of remove() 
 Pre: lst is not null
 pre: lst is not empty
 */
void removeList (struct linkedList *lst, TYPE e) {
    assert(lst != NULL);
    assert(lst->size != 0);

    int removed = FALSE;
    struct DLink *tempLink = lst->firstLink;
    int index = 0;

    while((removed == FALSE) &&(index < lst->size))
    {
	   if(tempLink->next->value == e){
		  _removeLink(lst, tempLink->next);
		  removed = TRUE;
	   }else{
		  index++;
		  tempLink = tempLink->next;
	   }
    }
}

void removeAllList (struct linkedList *lst) {
    assert(lst != NULL);
    assert(lst->size != 0);

    while(lst->size > 0){
	   removeFrontList(lst);
	   }

    free(lst->firstLink);
    free(lst->lastLink);
    free(lst);
}

