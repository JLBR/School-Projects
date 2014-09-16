#include <stdio.h>
#include "dominion.h"
#include "testUtility.h"

//tests the costs of cards, getCost comes from dominon.c getCardCost comes from testUtility so if dominon changes it will be caught
int testCost()
{
	int i;
	int tempCost;
	int results = SUCCESS;

	for(i = 0;i<=treasure_map;i++)
	{
		tempCost = getCost(i);
		if(tempCost != getCardCost(i))
		{
			printf("%s cost is incorect.  Corect value: %d Incorect value: %d\n",
				 getCardName(i), getCardCost(i), tempCost);
			results = FAILURE;
		}
	}

	return results;
}

int main()
{
	int results;
	printf("Starting cost test\n");
	results = testCost();
	printf("Final result is ");
	printResult(results);

	return 0;
}
