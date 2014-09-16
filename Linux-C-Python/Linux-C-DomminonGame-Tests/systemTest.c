#include "dominion.h"
#include "dominion_helpers.h"
#include "rngs.h"
#include <math.h>
#include <string.h>
#include <stdio.h>
#include <stdlib.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <sys/types.h> 
#include <errno.h>
#include <unistd.h>

#define NO_CHOICE 0
#define FAILURE -1
#define BUFFER_SIZE 30000

int openLogFile(char * name)
{

	int fp;
	char choice;
	//char temp = ' ';

	fp = open(name, O_CREAT|O_WRONLY|O_EXCL, S_IRUSR|S_IWUSR|S_IRGRP|S_IWGRP|S_IROTH);
	if(fp < 0)
	{
		if(errno == EEXIST)
		{
			//printf("Do you want to replace the existing log file? (Y/N) ");
			//choice = getchar();

			//while (temp != '\n')
				//temp = getchar();
			//temp = ' ';

			choice ='Y';

			if(choice =='Y' || choice == 'y')
			{
				fp = open(name, O_CREAT|O_WRONLY, S_IRUSR|S_IWUSR|S_IRGRP|S_IWGRP|S_IROTH);
				if(fp < 0)
				{
						perror("Error while opening file");
						return FAILURE;
				}
			}
			else
				return FAILURE;
		}
		else
		{
			perror("Error while opening file");
			return FAILURE;
		}
	}

	return fp;
}

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

int printGameSettings(struct gameState *state, int logSwitch, int logFile)
{
	if(logSwitch == 0)
	{
		printf("----------GAME SETTINGS--------------\n");
		printf("   numPlayers: %d\n\n", state->numPlayers);
	}
	else
	{
		dprintf(logFile, "----------GAME SETTINGS--------------\n");
		dprintf(logFile, "   numPlayers: %d\n\n", state->numPlayers);
	}
	
	return 0;
}

int printKingdomCards(int kingdomCards[10], int logSwitch, int logFile)
{
	int i;
 
	if(logSwitch == 0)
	{
		printf("----------KINGDOM CARDS--------------\n");
		for(i = 0; i < 10; i++)
			printf("    Kingdom card %d: %s\n", i, getCardName(kingdomCards[i]));

		printf("\n");
	}
	else
	{
		dprintf(logFile, "----------KINGDOM CARDS--------------\n");
		for(i = 0; i < 10; i++)
			dprintf(logFile, "    Kingdom card %d: %s\n", i, getCardName(kingdomCards[i]));

		dprintf(logFile, "\n");
	}
	return 0;
}

int printTurnSettings(struct gameState *state, int logSwitch, int logFile)
{
	if(logSwitch == 0)
	{
		printf("----------TURN SETTINGS--------------\n");
	
		printf("   phase:         %i\n", state->phase);
		printf("   whoseTurn:     %i\n", state->whoseTurn);
		printf("   outpostPlayed: %i\n", state->outpostPlayed);
		printf("   outpostTurn:   %i *unused\n", state->outpostTurn);
		printf("   numActions:    %i \n", state->numActions);
		printf("   numBuys:       %i \n", state->numBuys);
		printf("   coins:         %i \n\n", state->coins);
	}
	else
	{
		dprintf(logFile, "----------TURN SETTINGS--------------\n");
	
		dprintf(logFile, "   phase:         %i\n", state->phase);
		dprintf(logFile, "   whoseTurn:     %i\n", state->whoseTurn);
		dprintf(logFile, "   outpostPlayed: %i\n", state->outpostPlayed);
		dprintf(logFile, "   outpostTurn:   %i *unused\n", state->outpostTurn);
		dprintf(logFile, "   numActions:    %i \n", state->numActions);
		dprintf(logFile, "   numBuys:       %i \n", state->numBuys);
		dprintf(logFile, "   coins:         %i \n\n", state->coins);
	}
	return 0;
}

int printSupplyInformation(struct gameState *state, int logSwitch, int logFile)
{
	int i;

	if(logSwitch == 0)
	{
		printf("--------------SUPPLY-----------------\n");

		for(i = 0;i < treasure_map+1; i++)
		{
			printf("   embargo: %i  supplyCount: %i name:%s\n", 
				state->embargoTokens[i], 
				state->supplyCount[i],
				getCardName(i));
		}

		printf("\n");
	}
	else
	{
		dprintf(logFile, "--------------SUPPLY-----------------\n");

		for(i = 0;i < treasure_map+1; i++)
		{
			dprintf(logFile, "   embargo: %i  supplyCount: %i name:%s\n", 
				state->embargoTokens[i], 
				state->supplyCount[i],
				getCardName(i));
		}

		dprintf(logFile, "\n");
	}
	return 0;
}

int printPlayed(struct gameState * state, int logSwitch, int logFile)
{
	int i;

	if(logSwitch == 0)
	{
		printf("-----------PLAYED CARDS--------------\n");
	
		printf("   playedCardCount: %i\n\n", state->playedCardCount);
	
		for(i = 0; i < state->playedCardCount; i++)
			printf("   Card: %s\n", getCardName(state->playedCards[i]));

		printf("\n");
	}
	else
	{
		dprintf(logFile, "-----------PLAYED CARDS--------------\n");
	
		dprintf(logFile, "   playedCardCount: %i\n\n", state->playedCardCount);
	
		for(i = 0; i < state->playedCardCount; i++)
			dprintf(logFile, "   Card: %s\n", getCardName(state->playedCards[i]));

		dprintf(logFile, "\n");
	}
	return 0;
}


int printPlayerDiscard(int player, struct gameState * state, int logSwitch, int logFile)
{
	int i;

	if(logSwitch == 0)
	{
		printf("------PLAYER %d DISCARD PILE---------\n", player);
	
		printf("   discardCount: %i\n\n", state->discardCount[player]);
	
		for(i = 0; i < state->discardCount[player]; i++)
			printf("   Discard Position: %d Card: %s\n", i, getCardName(state->discard[player][i]));

		printf("\n");
	}
	else
	{
		dprintf(logFile, "------PLAYER %d DISCARD PILE---------\n", player);
	
		dprintf(logFile, "   discardCount: %i\n\n", state->discardCount[player]);
	
		for(i = 0; i < state->discardCount[player]; i++)
			dprintf(logFile, "   Discard Position: %d Card: %s\n", 
				i, 
				getCardName(state->discard[player][i]));

		dprintf(logFile, "\n");
	}

	return 0;
}

int printPlayerHand(int player, struct gameState * state, int logSwitch, int logFile)
{
	int i;

	if(logSwitch == 0)
	{
		printf("----------PLAYER %d HAND-------------\n", player);
		printf("   handCount: %i\n\n", state->handCount[player]);

		for(i = 0; i < state->handCount[player]; i++)
			printf("   Hand position %i card: %s\n", i, getCardName(state->hand[player][i]));

		printf("\n");
	}
	else
	{
		dprintf(logFile, "----------PLAYER %d HAND-------------\n", player);
		dprintf(logFile, "   handCount: %i\n\n", state->handCount[player]);

		for(i = 0; i < state->handCount[player]; i++)
			dprintf(logFile, "   Hand position %i card: %s\n", i, getCardName(state->hand[player][i]));

		dprintf(logFile, "\n");
	}

	return 0;
}

int printPlayerDeck(int player, struct gameState * state, int logSwitch, int logFile)
{
	int i;

	if(logSwitch == 0)
	{
		printf("----------PLAYER %d DECK-------------\n", player);
		printf("   deckCount: %i\n\n", state->deckCount[player]);

		for(i = 0; i < state->deckCount[player]; i++)
			printf("   Deck position %i card: %s\n", i, getCardName(state->deck[player][i]));

		printf("\n");
	}
	else
	{
		dprintf(logFile, "----------PLAYER %d DECK-------------\n", player);
		dprintf(logFile, "   deckCount: %i\n\n", state->deckCount[player]);

		for(i = 0; i < state->deckCount[player]; i++)
			dprintf(logFile, "   Deck position %i card: %s\n", i, getCardName(state->deck[player][i]));

		dprintf(logFile, "\n");
	}

	return 0;
}

int printAll(int kingdomCards[10], struct gameState * state, int logSwitch, int logFile)
{
	int i;
	
	printGameSettings(state, logSwitch, logFile);
	printKingdomCards(kingdomCards, logSwitch, logFile);
	printTurnSettings(state, logSwitch, logFile);
	printSupplyInformation(state, logSwitch, logFile);
	printPlayed(state, logSwitch, logFile);

	for(i = 0; i < state->numPlayers; i++)
	{
		printPlayerHand(i, state, logSwitch, logFile);
		printPlayerDeck(i, state, logSwitch, logFile);
		printPlayerDiscard(i, state, logSwitch, logFile);
	}

	return 0;
}

//returns a kingdom card that is in use in the current game
int getRndKingdomCard(int kingdomCards[10])
{
	int cardIndex = Random()*9;
	return kingdomCards[cardIndex];
}

int getRndCardInPlay(int kingdomCards[10])
{
	int randomCard = Random()*gold;
	int randomEvent = Random()*100;

	if(randomEvent > 70)
	{
		randomCard = getRndKingdomCard(kingdomCards);
	}

	return randomCard;
}

//returns the first position of the searched for card, or -1 if not found
int getHandPos(int card, struct gameState *state)
{
	int handPos = -1;
	int currentPlayer = whoseTurn(state);
	int handCount = state->handCount[currentPlayer];

	int i;

	for(i = 0; i < handCount; i++)
	{
		if(state->hand[currentPlayer][i] == card)
		{
			handPos = i;
			break;
		}
	}
	return handPos;
}


//returns a random action card from the hand, or -1 if no action cards are in the hand
int getActionCard(struct gameState* state, int unssucessfullyPlayed[])
{
	int player = whoseTurn(state);
	int i;
	int j = 0;
	int actionCardsInHand[MAX_HAND];
	
	actionCardsInHand[0] = -1;

	for(i = 0; i < state->handCount[player]; i++)
	{
		if((state->hand[player][i] > gold && unssucessfullyPlayed[i]==0) )
		{
			actionCardsInHand[j] = i;
			j++;
		}
	}
	
	i = Random()*j;
	return actionCardsInHand[i];	
}


//randomly selects 10 kingdom cards for the initilization
int * setKingdomCards()
{
	int kingdomCard[10];
	int tempCard;
	int inDeck;

	int i;
	int j;

	for(i = 0;i<10;i++)
	{
		inDeck = 0;
		kingdomCard[i] = 0;
		tempCard = Random()*20+adventurer;

		for(j = 0; j < i; j++)
		{
			if(kingdomCard[j] == tempCard)
			{
				inDeck = 1;
				break;
			}
		}

		if(inDeck == 0)
		{
			kingdomCard[i] = tempCard;
		}
		else
		{
			i--;
		}
	}

	return kingdomCards(kingdomCard[0], kingdomCard[1], kingdomCard[2], 
			kingdomCard[3], kingdomCard[4], kingdomCard[5], kingdomCard[6], 
			kingdomCard[7], kingdomCard[8], kingdomCard[9]);
}

//initialzer helper
int initializeRandomGame(int kingdomCards[10], struct gameState * state)
{
	int initSuccess;
	int numPlayers = (Random()*2)+2;
	
	initSuccess = initializeGame(numPlayers, kingdomCards, 600, state);
	if(initSuccess == -1)
		printf("initializeGame FAILED \n");
	return initSuccess;
}

int logBuySettings(struct gameState * state, int logFile)
{
	int logSwitch = 1;

	printTurnSettings(state, logSwitch, logFile);
	printSupplyInformation(state, logSwitch, logFile);
	printPlayerDiscard(whoseTurn(state), state, logSwitch, logFile);

	return 0;
}

int buyPhase( int kingdomCards[10], struct gameState * state, int logFile )
{
	int buyAction;
	int buyCardResult = 0;
	int badBuys = 0;
	int card;

	printf("Player %d BUY PHASE START\n", whoseTurn(state));
	dprintf(logFile, "Player %d BUY PHASE START\n", whoseTurn(state));

	while(state->numBuys > 0 && badBuys < 10)
	{
		logBuySettings(state, logFile);
		buyAction = Random()*100;

		if(buyAction > 80)//buy victory card
		{
			card = Random()*2 + estate;
		}
		else if(buyAction > 30)//buy treasure
		{
			card = Random()*2 + copper;
		}
		else//buy action
		{
			card = getRndKingdomCard(kingdomCards);
		}
	
		dprintf(logFile, "Player %d attempting to buy %s cost: %d coins: %d supply: %d ", 
			whoseTurn(state), 
			getCardName(card),
			getCost(card),
			state->coins,
			state->supplyCount[card]);

		buyCardResult = buyCard(card, state);
		if(buyCardResult < 0)
		{
			badBuys++;
			dprintf(logFile, "FAILED\n");
		}
		else
		{
			dprintf(logFile, "SUCCESS\n");
			printf("   Player %d bought %s \n", whoseTurn(state), getCardName(card));
			dprintf(logFile, "   Player %d bought %s \n", whoseTurn(state), getCardName(card));
		}
		logBuySettings(state, logFile);
	}
	
	return 0;
}

int logActionSettings(struct gameState * state, int logFile)
{
	int logSwitch = 1;
	int i;
	
	printTurnSettings(state, logSwitch, logFile);
	printSupplyInformation(state, logSwitch, logFile);
	printPlayed(state, logSwitch, logFile);

	for(i = 0; i < state->numPlayers; i++)
	{
		printPlayerHand(i, state, logSwitch, logFile);
		printPlayerDeck(i, state, logSwitch, logFile);
		printPlayerDiscard(i, state, logSwitch, logFile);
	}

	return 0;
}

int actionPhase(int kingdomCards[10], struct gameState* state, int logFile)
{
	int cardSuccess;
	int choice1, choice2, choice3 = NO_CHOICE;
	int player = whoseTurn(state);
	int unssucessfullyPlayed[MAX_HAND];
	int card;
	int handPos;
	int numActionsThisTurn = 0;
	
	memset(unssucessfullyPlayed, 0, MAX_HAND*sizeof(int));

	printf("Player %d ACTION PHASE START \n", player);
	dprintf(logFile, "Player %d ACTION PHASE START \n", player);

	while(state->numActions > 0)
	{

		dprintf(logFile, "   ACTION NUMBER: %d\n\n", numActionsThisTurn);
		logActionSettings(state, logFile);

		handPos = getActionCard(state, unssucessfullyPlayed);
		if(handPos == -1)
			return 0;

		card = state->hand[player][handPos];
		printf("   Player %d PLAYING %s ", player, getCardName(card));
		dprintf(logFile, "  Player %d PLAYING %s from hand position %d ", 
			player, 
			getCardName(card), 
			handPos);

		switch(card)
		{
			case adventurer:
				break;
			case council_room:
				break;
			case feast:
				choice1 = getRndCardInPlay(kingdomCards);

				//***Prevents INFINITE LOOP IF COST > 5 and if no supply left
				while(getCost(choice1) > 5 || state->supplyCount[choice1]<=0)
					choice1 = getRndCardInPlay(kingdomCards);

				dprintf(logFile, " choice 1: %s ", getCardName(choice1));
				break;
			case gardens:
				break;
			case mine:
				choice1 = getHandPos(copper, state);
				if(choice1 == -1)
				{
					choice1 = getHandPos(silver, state);
					if(choice1 == -1)
						choice1 = getHandPos(gold, state);
					if(choice1 == -1)//no treasure in hand
					{
						choice1 = 0;
					}
					choice2 = gold;
				}
				else 
				{
					choice2 = silver;
				}
				dprintf(logFile, "choice 1: %s choice 2: %s ", getCardName(state->hand[player][choice1]),
					 getCardName(choice2));
				break;
			case remodel:
				choice1 = floor(Random()*state->handCount[whoseTurn(state)]);
				choice2 = getRndCardInPlay(kingdomCards);
				dprintf(logFile, "choice 1: cardPosition %d card: %s choice 2: %s ", 
					choice1, getCardName(state->hand[player][choice1]), getCardName(choice2));
				break;
			case smithy:
				break;
			case village:
				break;
			case baron:
				choice1 = Random()*100;
				if(choice1>50)
				{
					choice1 = 1;
				}
				else
				{
					choice1 = 0;
				}
				dprintf(logFile, "choice 1: %d ", choice1);
				break;
			case great_hall:
				break;
			case minion:
				switch((int)Random())
				{
					case 0:
						choice1 = 1;
						choice2 = 0;
						break;
					case 1:
						choice1 = 0;
						choice2 = 1;
						break;
				}
				dprintf(logFile, "choice 1: %d choice 2: %d ", choice1, choice2);
				break;
			case steward:
				switch((int)Random()*2)
				{
					case 0:
						choice1 = 0;
						dprintf(logFile, "choice 1: %d ", choice1);
						break;
					case 1:
						choice1 = 1;
						dprintf(logFile, "choice 1: %d ", choice1);
						break;
					case 2:
						choice1 = 2;
						choice2 = (Random()*state->handCount[player])-1;
						choice3 = (Random()*state->handCount[player])-1;
						dprintf(logFile, "choice 1: %d choice 2: %s choice 3: %s", 
							choice1, 
							getCardName(state->hand[player][choice2]), 
							getCardName(state->hand[player][choice3]));
						break;
				}
				dprintf(logFile, "choice 1: %d choice 2: %s choice 3: %s", choice1, 
					getCardName(state->hand[player][choice2]), 
					getCardName(state->hand[player][choice3]));
				break;
			case tribute:
				dprintf(logFile, "KNOWN BUG INFINITE LOOP");
				state->numActions -= 3;//to prevent infinite loop
				break;
			case ambassador:
				choice2 = Random()*2;
				choice1 = floor(Random()*state->handCount[whoseTurn(state)]);
				dprintf(logFile, "choice 1: %s choice 2: %d ", 
					getCardName(state->hand[player][choice1]), 
					choice2);
				break;
			case cutpurse:
				break;
			case embargo:
				choice1 = floor(Random()*treasure_map);
				dprintf(logFile, "choice 1: %s ", getCardName(choice1));
				break;
			case outpost:
				break;
			case salvager:
				choice1 = floor(Random()*state->handCount[whoseTurn(state)]);
				dprintf(logFile, "choice 1: %s ", getCardName(state->hand[player][choice1]));
				break;
			case sea_hag:
				break;
			case treasure_map:
				break;
		}

		cardSuccess = playCard(handPos, choice1, choice2, choice3, state);
		if(cardSuccess < 0)
		{
			//printf(" FAILED");
			dprintf(logFile, " FAILED");
			unssucessfullyPlayed[handPos] = 1;
		}
		else
		{
			//printf(" SUCCESS");
			dprintf(logFile, " SUCCESS");
			memset(unssucessfullyPlayed, 0, MAX_HAND*sizeof(int));
		}
		printf("\n");
		dprintf(logFile, "\n");

		logActionSettings(state, logFile);
		numActionsThisTurn++;
	}
	return 0;
}

int main()
{
	int *kingdomCards;
	int turn = 1;
	int winners[MAX_PLAYERS];
	int i;

	int logFile;

	struct gameState * state;

	logFile = openLogFile("gameResults.out");

  	PutSeed(8);

	state = newGame();
	
	printf("Setting up kingdom cards\n");
	dprintf(logFile, "Setting up kingdom cards\n");

	kingdomCards = setKingdomCards();
	printKingdomCards(kingdomCards, 0, 0);

	printf("Initializing the game\n");
	dprintf(logFile, "Initializing the game\n");

	initializeRandomGame(kingdomCards, state);

	printAll(kingdomCards, state, 1, logFile);

	while(!isGameOver(state))
	{
		printf("\nTURN %d \n", turn);
		dprintf(logFile, "\nTURN %d \n", turn);
		actionPhase(kingdomCards, state, logFile);
		buyPhase(kingdomCards, state, logFile);

		dprintf(logFile, "END TURN %d \n", turn);
		printTurnSettings(state, 1, logFile);
		endTurn(state);
		printTurnSettings(state, 1, logFile);
		//printAll(kingdomCards, state, 1, logFile);
		turn++;
	
		//printAll(kingdomCards, state);

		printf("Score");
		dprintf(logFile, "Score");
		for(i = 0; i < state->numPlayers; i++)
		{
			printf(" Player %d: %d", i, scoreFor(i, state));
			dprintf(logFile, " Player %d: %d", i, scoreFor(i, state));
		}
		printf("\n");
		dprintf(logFile, "\n");
	}

	getWinners(winners, state);
	for(i = 0; i < MAX_PLAYERS;i++)
		if(winners[i] == 1)
			printf("Player %d ", i);
			dprintf(logFile, "Player %d ", i);
	printf("WON!!!!\n");
	dprintf(logFile, "WON!!!!\n");

	printAll(kingdomCards, state, 1, logFile);

	close(logFile);

	free(state);
	free(kingdomCards);

	return 0;
}
