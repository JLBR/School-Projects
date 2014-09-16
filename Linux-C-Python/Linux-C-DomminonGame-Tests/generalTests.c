#include "dominion.h"
#include <stdio.h>
#include <stdlib.h>

int testNumPlayers(struct gameState* oState, struct gameState *newState, int change)
{
	int results = SUCCESS;
	if(oState->numPlayers != (newState->numPlayers-change))
	{
		printf("NumPlayers incorect.  Original value: %d New value: %d expected value %d",
			oState->numPlayers, newState->numPlayers, newState->numPlayers-change);
		results = FAILURE;
	}

	return results;
}

int testSupplyCount(struct gameState* oState, struct gameState *newState, int[] change)
{
	int results = SUCCESS;
	int i;
	
	for(i =0; i<= treasure_map;i++)
	{
		if(oState->supplyCount[i] != (newState->supplyCount[i]-change[i]))
		{
			printf("Supply count %s incorect.  Original value: %d New value: %d expected value %d",
				getCardName(i), oState->supplyCount[i], 
				newState->supplyCount[i], supplyCount[i]-change[i]);
			results = FAILURE;
		}
	}
	return results;
}

int testEmbargoTokens(struct gameState* oState, struct gameState *newState, int[] change)
{
	int results = SUCCESS;
	int i;
	
	for(i =0; i<= treasure_map;i++)
	{
		if(oState->embargoTokens[i] != (newState->embargoTokens[i]-change[i]))
		{
			printf("Embargo tokens %s incorect.  Original value: %d New value: %d expected value %d",
				getCardName(i), oState->embargoTokens[i], 
				newState->embargoTokens[i], newState->embargoTokens[i]-change[i]);
			results = FAILURE;
		}
	}
	return results;
}

int testOutpostPlayed(struct gameState* oState, struct gameState *newState, int change)
{
	int results = SUCCESS;
	if(oState->outpostPlayed != (newState->outpostPlayed-change))
	{
		printf("Outpost played incorect.  Original value: %d New value: %d expected value %d",
			oState->outpostPlayed, newState->outpostPlayed, newState->outpostPlayed-change);
		results = FAILURE;
	}

	return results;
}

int testOutpostTurn(struct gameState* oState, struct gameState *newState, int change)
{
	int results = SUCCESS;
	if(oState->outpostTurn != (newState->outpostTurn-change))
	{
		printf("Outpost turn incorect.  Original value: %d New value: %d expected value %d",
			oState->outpostTurn, newState->outpostTurn, newState->outpostTurn-change);
		results = FAILURE;
	}

	return results;
}

int testWhoseTurn(struct gameState* oState, struct gameState *newState, int change)
{
	int results = SUCCESS;
	if(oState->whoseTurn != (newState->whoseTurn-change))
	{
		printf("Whose turn incorect.  Original value: %d New value: %d expected value %d",
			oState->whoseTurn, newState->whoseTurn, newState->whoseTurn-change);
		results = FAILURE;
	}

	return results;
}

int testPhase(struct gameState* oState, struct gameState *newState, int change)
{
	int results = SUCCESS;
	if(oState->phase != (newState->phase-change))
	{
		printf("Phase turn incorect.  Original value: %d New value: %d expected value %d",
			oState->phase, newState->phase, newState->phase-change);
		results = FAILURE;
	}

	return results;
}

int testNumActions(struct gameState* oState, struct gameState *newState, int change)
{
	int results = SUCCESS;
	if(oState->numActions != (newState->numActions-change))
	{
		printf("Num actions incorect.  Original value: %d New value: %d expected value %d",
			oState->numActions, newState->numActions, newState->numActions-change);
		results = FAILURE;
	}

	return results;
}

int testCoins(struct gameState* oState, struct gameState *newState, int change)
{
	int results = SUCCESS;
	if(oState->coins != (newState->coins-change))
	{
		printf("Coins incorect.  Original value: %d New value: %d expected value %d",
			oState->coins, newState->coins, newState->coins-change);
		results = FAILURE;
	}

	return results;
}

int testNumBuys(struct gameState* oState, struct gameState *newState, int change)
{
	int results = SUCCESS;
	if(oState->numBuys != (newState->numBuys-change))
	{
		printf("Num buys incorect.  Original value: %d New value: %d expected value %d",
			oState->numBuys, newState->numBuys, newState->numBuys-change);
		results = FAILURE;
	}

	return results;
}

int testHand(struct gameState* oState, struct gameState *newState, int expectedHand[][])
{
	int results = SUCCESS;
	int i;
	int j;

	for(i=0;i<MAX_PLAYERS;i++)
	{
		for(j=0;j<newState->handCount[i];j++)
		{
			if(newState->hand[i][j] != (expectedHand[i][j]))
			{
				printf("Hand incorect.  Original value: %s New value: %s expected value %s",
					getCardName(oState->hand[i][j]), 
					getCardName(newState->hand[i][j]), 
					getCardName(expectedHand[i][j]));
				results = FAILURE;
			}

	return results;
}

int testHandCount(struct gameState* oState, struct gameState *newState, int[] change)
{
	int results = SUCCESS;
	int i;
	
	for(i =0; i<= MAX_PLAYERS;i++)
	{
		if(oState->handCount[i] != (newState->handCount[i]-change[i]))
		{
			printf("Hand count for player %d incorect.  Original value: %d New value: %d expected value %d",
				i, oState->handCount[i], 
				newState->handCount[i], handCount[i]-change[i]);
			results = FAILURE;
		}
	}
	return results;
}

int testDeck(struct gameState* oState, struct gameState *newState, int expectedDeck[][])
{
	int results = SUCCESS;
	int i;
	int j;

	for(i=0;i<MAX_PLAYERS;i++)
	{
		for(j=0;j<newState->deckCount[i];j++)
		{
			if(newState->deck[i][j] != (expectedDeck[i][j]))
			{
				printf("Hand incorect.  Original value: %s New value: %s expected value %s",
					getCardName(oState->deck[i][j]), 
					getCardName(newState->deck[i][j]), 
					getCardName(expectedDeck[i][j]));
				results = FAILURE;
			}

	return results;
}


int testDeckCount(struct gameState* oState, struct gameState *newState, int[] change)
{
	int results = SUCCESS;
	int i;
	
	for(i =0; i<= MAX_PLAYERS;i++)
	{
		if(oState->deckCount[i] != (newState->deckCount[i]-change[i]))
		{
			printf("Hand count for player %d incorect.  Original value: %d New value: %d expected value %d",
				i, oState->deckCount[i], 
				newState->deckCount[i], deckCount[i]-change[i]);
			results = FAILURE;
		}
	}
	return results;
}

int testDiscard(struct gameState* oState, struct gameState *newState, int expectedDiscard[][])
{
	int results = SUCCESS;
	int i;
	int j;

	for(i=0;i<MAX_PLAYERS;i++)
	{
		for(j=0;j<newState->discardCount[i];j++)
		{
			if(newState->discard[i][j] != (expectedDiscard[i][j]))
			{
				printf("Hand incorect.  Original value: %s New value: %s expected value %s",
					getCardName(oState->discard[i][j]), 
					getCardName(newState->discard[i][j]), 
					getCardName(expectedDiscard[i][j]));
				results = FAILURE;
			}

	return results;
}


int testDiscardCount(struct gameState* oState, struct gameState *newState, int[] change)
{
	int results = SUCCESS;
	int i;
	
	for(i =0; i<= MAX_PLAYERS;i++)
	{
		if(oState->discardCount[i] != (newState->discardCount[i]-change[i]))
		{
			printf("Hand count for player %d incorect.  Original value: %d New value: %d expected value %d",
				i, oState->discardCount[i], 
				newState->discardCount[i], discardCount[i]-change[i]);
			results = FAILURE;
		}
	}
	return results;
}


int testPlayedCards(struct gameState* oState, struct gameState *newState, int expectedPlayedCards[][])
{
	int results = SUCCESS;
	int i;
	int j;

	for(i=0;i<MAX_PLAYERS;i++)
	{
		for(j=0;j<newState->playedCardsCount[i];j++)
		{
			if(newState->playedCards[i][j] != (expectedPlayedCards[i][j]))
			{
				printf("Hand incorect.  Original value: %s New value: %s expected value %s",
					getCardName(oState->playedCards[i][j]), 
					getCardName(newState->playedCards[i][j]), 
					getCardName(expectedPlayedCards[i][j]));
				results = FAILURE;
			}

	return results;
}


int testPlayedCardsCount(struct gameState* oState, struct gameState *newState, int[] change)
{
	int results = SUCCESS;
	int i;
	
	for(i =0; i<= MAX_PLAYERS;i++)
	{
		if(oState->playedCardsCount[i] != (newState->playedCardsCount[i]-change[i]))
		{
			printf("Hand count for player %d incorect.  Original value: %d New value: %d expected value %d",
				i, oState->playedCardsCount[i], 
				newState->playedCardsCount[i], playedCardsCount[i]-change[i]);
			results = FAILURE;
		}
	}
	return results;
}
