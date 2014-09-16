/* Assignment 2
 * Name:Jimmy 
 * Date:20 Jan 2013
 * Solution description: This program takes a string as an input and identifies if there is a close
 * bace for an open brace.  The first brace encountered must be an open, or the result will be false.
 */

/*	stack.c: Stack application. */
#include <stdio.h>
#include <stdlib.h>
#include "dynArray.h"
#include "assert.h"


/* ***************************************************************
Using stack to check for unbalanced parentheses.
***************************************************************** */

/* Returns the next character of the string, once reaches end return '0' (zero)
	param: 	s pointer to a string 	
	pre: s is not null		
*/
char nextChar(char* s)
{

     assert(s != NULL);

	static int i = -1;	
	char c;
	++i;	
	c = *(s+i);			
	if ( c == '\0' )
		return '\0';	
	else 
		return c;
}

/* Checks whether the (), {}, and [] are balanced or not
	param: s pointer to a string 	
	pre: s is not null	
	post:	
*/
int isBalanced(char* s)
{
	
    assert(s != NULL);
    
    int returnValue = 0;
    char testChar;

    struct DynArr *stringBalance = newDynArr((sizeof(s)/sizeof('a'))+1);
    do{

	   testChar = nextChar(s);
	   if(testChar == '(' || testChar == '{' ||testChar == '[' ){
		  pushDynArr(stringBalance, testChar);
		  returnValue = 0;
	   }else if(testChar == ')' || testChar == '}' ||testChar == ']' ){
		  
		  if(isEmptyDynArr(stringBalance)){ //if the first match is a close then the string is imbalanced
			 returnValue = 0;
			 break;
		  }
		  

		  char top = topDynArr(stringBalance);
		  switch(testChar){
			 
		  case ')':
			 if( top == '('){
				popDynArr(stringBalance);
				returnValue = 1;
			 }else {
				returnValue = 0;
				testChar = '\0';
			 }
			 break;
		  case '}':
			 if( top == '{'){
				popDynArr(stringBalance);
				returnValue = 1;
			 }else {
				returnValue = 0;
				testChar = '\0';
			 }
			 break;
		case ']':
			 if( top == '['){
				popDynArr(stringBalance);
				returnValue = 1;
			 }else {
				returnValue = 0;
				testChar = '\0';
			 }
			 break;
		  }

	   }else if(testChar == '\0'){
		  if(!isEmptyDynArr(stringBalance)){//if at the end of the string it is not empty then it is false
			 returnValue = 0;
		  }
	   }

    }while(testChar != '\0');

    return returnValue;
}

int main(int argc, char* argv[]){
	
	char* s=argv[1];
	//s = "{[]}";
	int res;
	
	printf("Assignment 2\n");

	res = isBalanced(s);

	if (res)
		printf("The string %s is balanced\n",s);
	else 
		printf("The string %s is not balanced\n",s);
	
	return 0;	
}

