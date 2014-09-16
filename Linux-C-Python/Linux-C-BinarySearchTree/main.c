/* Assignment 4 EC1
 * Name:Jimmy 
 * Date:17 Feb 2013
 * Solution description: This program asks questions about animals then if it does not find the right one
 * it asks questions to increase the knowledge base.
 */

#include<stdio.h>
#include<stdlib.h>
#include "bst.h"
#include "structs.h"
#include <assert.h>
#include <iostream>

#define NO 0
#define YES 1

/*
 loads the intial tree
 param:   questions the empty list of questions
 pre:	questions is not null
 pose:    None
 */
void initialData(struct BSTree* questions){
    assert(questions != NULL);

    addBSTree(questions, "");

    struct Node* currentQ = getRoot(questions);

    addNextQ(questions, currentQ, "Does it live in the water?", "", "");
    addNextQ(questions, getNextQ(currentQ, 1), "Is it a mamal?", "Sea Turtle", "Whale");
    addNextQ(questions, getNextQ(currentQ, 0), "Does it climb trees?", "Dog", "Cat");
}

/*
 Runs the main game asking questions and adding new questions
 param:   tree the question list
 pre:	None
 pose:    None
 */
void askQuestions(struct BSTree *tree){

    struct Node *nextQ = getRoot(tree);
    struct Node *currentQ;

    printf(" \n\n\n");

    char choice[50] = "";
    while(nextQ != NULL){
	   
	   currentQ = nextQ;
	   choice[0] = 2;
	   while(choice[0] != '1' && choice[0] != '0'){

		  //choice[0] = 'w';

		  printf("%s\n[1]Yes, [0]No : ", getVal(nextQ));
		  scanf("%s[49]", choice);
		  //printf("%s", choice);

		  //char* temp = (char *)malloc(sizeof(char));
		 // while(*temp != '\n'){
		//	 scanf("%c", temp);
		 // }
	   }

	   if(choice[0] == '1'){
		  nextQ = getNextQ(nextQ, YES);
	   }else{
		  nextQ = getNextQ(nextQ, NO);
	   }
    }

    if(choice[0] == '0'){
	   char* newQ  = (char *) malloc(sizeof(char)*100);
	   char* newA = (char *) malloc(sizeof(char)*50);
	   
	   printf("So what were you thinking of?\n");
	   gets(newA);//clear buffer
	   gets(newA);
	   printf("Tell me a question that identifies %s that I did not ask.\n",newA);
	   gets(newQ);

	   addNextQ(tree, currentQ, newQ, getVal(currentQ), newA);
    }else{
	   printf("\nI got it !!");
    }
}

/*
 Prints the instructions
 param:   None
 pre:	None
 pose:    None
 */
void intro(){
    printf("Welcome to the animal guessing game.\nYou will be given a series of yes or no questions.\n");
    printf("If at the end your animal did not show up,\ngive me information to teach me about it.\n");
    printf("Lets begin!!");
}

/*
 Main loop keeps going untill the user quits*** I did not havetime to add file IO to save the users new data
 param:   none
 pre:	None
 pose:    None
 */
int main()
{
	struct BSTree *tree	= newBSTree();
	initialData(tree);

	intro();
	char choice[50] = "1";

	while( choice[0] == '1'){
	
	   askQuestions(tree);

	   printf("\n\nWould you like to do that again? Yes[1]");
	   gets(choice);//clear buffer
	   gets(choice);
	}
	
	printf("\n\nThank you, goodbye\n\n");

	/**
	struct Node *nextQ = getRoot(tree);
	printf("%s\n", getVal(nextQ));
	printf("Yes\n");
	nextQ = getNextQ(nextQ, YES);
	printf("%s\n", getVal(nextQ));
	printf("Yes\n");
	nextQ = getNextQ(nextQ, YES);
     printf("%s\n", getVal(nextQ));

	nextQ = getRoot(tree);
     printf("%s\n", getVal(nextQ));
	printf("no\n");
	nextQ = getNextQ(nextQ, NO);
	printf("%s\n", getVal(nextQ));
	printf("No\n");
	nextQ = getNextQ(nextQ, NO);
     printf("%s\n", getVal(nextQ));

		nextQ = getRoot(tree);
     printf("%s\n", getVal(nextQ));
	printf("Yes\n");
	nextQ = getNextQ(nextQ, YES);
	printf("%s\n", getVal(nextQ));
	printf("No\n");
	nextQ = getNextQ(nextQ, NO);
     printf("%s\n", getVal(nextQ));

		nextQ = getNextQ(nextQ, NO);
		*/
	return 0;
}

