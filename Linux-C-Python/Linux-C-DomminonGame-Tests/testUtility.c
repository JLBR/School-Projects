#include "dominion.h"
#include "testUtility.h"
#include <stdio.h>
char * getCardName(int card)
{
	switch(card)
	{
		case curse:
			return ("curse");
			break;
		case estate:
			return("estate");
			break;
		case duchy:
			return("duchy");
			break;
		case province:
			return("province");
			break;
		case copper:
			return("copper");
			break;
		case silver:
			return("silver");
			break;
		case gold:
			return("gold");
			break;
		case adventurer:
			return("adventurer");
			break;
		case council_room:
			return("council room");
			break;
		case feast:
			return("feast");
			break;
		case gardens:
			return("gardens");
			break;
		case mine:
			return("mine");
			break;
		case remodel:
			return("remodel");
			break;
		case smithy:
			return("smithy");
			break;
		case village:
			return("village");
			break;
		case baron:
			return("baron");
			break;
		case great_hall:
			return("great hall");
			break;
		case minion:
			return("minion");
			break;
		case steward:
			return("steward");
			break;
		case tribute:
			return("tribute");
			break;
		case ambassador:
			return("ambassador");
			break;
		case cutpurse:
			return("cutpurse");
			break;
		case embargo:
			return("embargo");
			break;
		case outpost:
			return("outpost");
			break;
		case salvager:
			return("salvager");
			break;
		case sea_hag:
			return("sea hag");
			break;
		case treasure_map:
			return("treasure map");
			break;
	}

	return "ERROR";
}


int getCardCost(int cardNumber)
{
  switch( cardNumber )
    {
    case curse:
      return 0;
    case estate:
      return 2;
    case duchy:
      return 5;
    case province:
      return 8;
    case copper:
      return 0;
    case silver:
      return 3;
    case gold:
      return 6;
    case adventurer:
      return 6;
    case council_room:
      return 5;
    case feast:
      return 4;
    case gardens:
      return 4;
    case mine:
      return 5;
    case remodel:
      return 4;
    case smithy:
      return 4;
    case village:
      return 3;
    case baron:
      return 4;
    case great_hall:
      return 3;
    case minion:
      return 5;
    case steward:
      return 3;
    case tribute:
      return 5;
    case ambassador:
      return 3;
    case cutpurse:
      return 4;
    case embargo:
      return 2;
    case outpost:
      return 5;
    case salvager:
      return 4;
    case sea_hag:
      return 4;
    case treasure_map:
      return 4;
    }

  return -1;
}

void printResult(int result)
{
	if(result == SUCCESS)
	{
		printf("SUCCESS\n");
	}
	else
	{
		printf("FAILURE\n");
	}
}


//calculates the value of the treasuer in the hand
int valueOfTreasuerInHand(int player, struct gameState *state)
{
	int i;
	int totalInHand = 0;

	//add coins for each Treasure card in player's hand
	for (i = 0; i < state->handCount[player]; i++)
	{
		if (state->hand[player][i] == copper)
		{
			totalInHand += COPPER;
		}
		else if (state->hand[player][i] == silver)
		{
			totalInHand += SILVER;
		}
		else if (state->hand[player][i] == gold)
		{
			totalInHand += GOLD;
		}
	}

	return totalInHand;

}
