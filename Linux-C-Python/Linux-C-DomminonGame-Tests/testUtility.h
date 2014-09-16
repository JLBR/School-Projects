#ifndef __TEST_UTILITY_H
#define __TEST_UTILITY_H

char * getCardName(int card);
int getCardCost(int cardNumber);
void printResult(int result);
int valueOfTreasuerInHand(int player, struct gameState *state);

#define SUCCESS 0
#define FAILURE -1

#define TRUE 1
#define FALSE 0


#define COPPER 1
#define SILVER 2
#define GOLD 3

#define MAX_TREASURE_CARDS 130//from the rules
#define MAX_COPPER 60
#define MAX_SILVER 40
#define MAX_GOLD 30
#define MAX_BONUS 9 //(remodel a province) or (use mine on gold)

#define MAX_KINGDOM_CARDS_NO_WIN 92 //maximum non-victory kingodom cards in deck without ending the game

#endif
