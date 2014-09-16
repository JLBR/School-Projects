	/****************************************************************
		** Class: BlackJack.java                                       **
		** Author: Jimmy                                               **
		** Date: 14 Oct 2012                                           **
		** Description: This class runs the BlackJack game             **
		** Currently the player can play single deck blackjack with    **
		** four other players                                          **
		*****************************************************************/
	
package Assignment4;

import java.util.ArrayList;
import java.util.NoSuchElementException;
import java.util.Scanner;

/**
 * The BlackJack class implements Game and the extended Instruction interface.
 * To be compliant with Game and still handle exceptions the main method Play()
 * calls another method called main().  Main handles the exception or passes them to play
 * who does not do throw them.<p>
 * 
 * This class provides all the logic for the AIs playing, and displaying the playing field.<br>
 * <p> The following methods are in the class:<ul><li>
 * 	public void play()
 * 	<li>private void main() throws Exception
 * 	<li>private void newTable() throws Exception
 * 	<li>private Table dealerPlay(Table newT)
 *  <li>private Table aiPlay(Table newT)
 *  <li>private Table flipAllCards(Table newT)
 *  <li>private byte getScore(byte playerId, Table newT, boolean isFinal)
 *  <li>public String getTitle()
 *  <li>public void showInstructions()
 *  <li>private static byte getByte(byte lowerRange, byte upperRange) throws Exception
 *  <li>private static byte getByte(byte lowerRange, byte upperRange) throws Exception
 */
public class BlackJack implements Game, Instructions {
	
	/**
	 * play is required for implementing Game, but does not throw exceptions.  It simply calls main().
	 */
	@Override
	public void play() {
		
		try {
			main();
		} catch (Exception e) {//never gets here
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}//end play

	/**
	 * main This does what play() would if play could have thrown exceptions.
	 * This provides the main menu for Blackjack and quits back to the GameDriver main menu.
	 * @throws Exception for human data entry
	 */
	private void main() throws Exception {
		//start variable declaration
		byte selection = 99;
		Scanner input = new Scanner(System.in);
		//end variable declaration
		
		try {//main blackjack menu
			
			while(selection != 3){//main game menu
				System.out.println("Lets play some Blackjack:\n");
				System.out.println("[1] Go join a single deck table" );
				System.out.println("[2] Read the instructions" );
				System.out.println("[3] Off to other fun things.");
				
				selection = getByte((byte)1,(byte)3);//get input
				
				if(selection == 1){//start games
					newTable();
				}else if (selection == 2){
					showInstructions();
				}//end if game selection
			}//end while main menu

		} catch (NoSuchElementException e) {//handles EOF in System.in gracefully
			System.out.println("Don't do that again\n");
			input.close();//garbage cleanup
			System.gc();//garbage cleanup
			System.exit(1);//exits with a status of 1
		}//end try-catch
	}//end main
	
	/**
	 * newTable was originally planed to have in the menu an option to set how many 
	 * decks to play with, and to be able to keep playing with the same deck for card counting strategies.
	 * @throws Exception due to human inputs
	 */
	private void newTable() throws Exception{
		
		//Start variable declaration
		byte selection = 99;
		Table newT = new Table((byte)6,(byte)1);
		CasinoPlayers players = new CasinoPlayers();
		//byte maxHand = 0;
		//end variable declaration

		
		for(byte i = 0; i<6; i++){//deal first card face down
			if(players.isNPC(i)){
				newT.dealCard(i, true);
			}else{
				newT.dealCard(i, false);
			}//end if player is NPC deal face-down
		}//end for deal first card
		
		for(byte i= 0; i<6; i++){//deal second card face up
			newT.dealCard(i, false);
		}//end for deal second card
		
		drawTable(newT, false);//draw the table for the first time
		
		System.out.println("\nOther players at the table are playing right now\nPlease wait.");
		Thread.sleep(4000);//pauses to make it appear as that others are playing
		
		newT = aiPlay(newT);
		
		drawTable(newT, false);//draw the table after the AIs have played
		
		while(selection != 2){//player menu
			System.out.println("Would you like to [1]Hit or [2]Stand?");
			
			selection = getByte((byte)1,(byte) 2);
			if(selection == 1){
				newT.dealCard((byte) 1, false);//deal face up
				drawTable(newT, false);//draw the with new card
			}else{
				newT = dealerPlay(newT);
				newT = flipAllCards(newT);
				drawTable(newT, true);//draw the final table
			}//end if hit or stand
			
			if(getScore((byte) 1, newT, true)>21){
				newT = dealerPlay(newT);
				newT = flipAllCards(newT);
				drawTable(newT, true);//draw the final table
				System.out.println("You busted!!!");
				selection = 2;
			}//end if busted
		}//end while game loop
	}//end newTable
	
	/**
	 * dealerPlay is specific to the dealer, where the AIs can be updated to have decision tables, 
	 * the dealer will always hit when less than 17 or up to the top score if the top score is less than 17.
	 * @param newT the table to be updated
	 * @return the updated table
	 */
	private Table dealerPlay(Table newT) {
		byte score;
		byte maxScore=0;
		
		for(byte i = 1; i<6 ; i++){
			score = getScore(i, newT, true);
			if((maxScore < score) && (score <22)){
				maxScore = score;
				score = 0;
			}//end if update max score if it is not busted
		}//end for cycle through the players
		
		score = getScore((byte) 0, newT, true);
		while((score< 17 ) && (score < maxScore)){
			newT.dealCard((byte) 0, false);//deals face up
			score = getScore((byte) 0, newT, true);
		}//deals until 17 or greater
		return newT;
	}//end dealerPlay

	/**
	 * aiPlay handles game play for non-human players.  The current algorithm is limited to 
	 * checking that their score is less than 17, so Hit.
	 * @param newT provides the Table to update
	 * @return Table is returned with updated moves
	 */
	private Table aiPlay(Table newT) {
		
		for(byte i = 2; i<6 ; i++){
			@SuppressWarnings("unused")
			byte score;
			while((score = getScore(i, newT, true))<17){
				newT.dealCard(i, false);//deals face up
				score = getScore(i, newT, true);
			}//deals until 17 or greater
		}//cycles through each player
		
		return newT;
	}//end aiPlay

	/**
	 * flipAllCards flips all face down cards to face up
	 * @param newT provides the Table to flip the cards
	 * @return Table with the cards fliped up
	 */
	private Table flipAllCards(Table newT){
		
		byte maxHand = 0;
		
		// FLIP ALL CARDS FOR FINAL
		for(byte i = 1; i<6;i++){
			if(maxHand<newT.getHandSize(i)){
				maxHand=newT.getHandSize(i);
			}//end if get max hand size
		}//end for hand size

		for(byte i=0;i<maxHand;i++){
			for(byte j = 0;j<6;j++){
				if(newT.getHandSize(j)>(i)){
					newT.flipCard(j, i);
				}//end for does player have a card
			}//end for select player
		}//end for flip cards
		return newT;
	}//end flipAllCards
	
	/**
	 * drawTable creates the play field where all the cards, scores, and players are shown.
	 * @param newT provides the hand information
	 * @param isFinal flips all the cards face up and show the final scores.
	 */
	private void drawTable(Table newT, boolean isFinal){
		CasinoPlayers players = new CasinoPlayers();
		String top = "-";
		String middle = "";
		byte maxHand = 0;
		ArrayList<String> tempPrintx = new ArrayList<String>(5);
		
		for(byte i=1;i<89;i++){
			top = top + "-";
		}//end top construction
		for(byte i=1;i<36;i++){
			middle = middle + "-";
		}
		
		//Start Drawing the table
		System.out.println("|" + top + "|");
		System.out.format("|" + middle + "-" + "%-17s" + middle + "-" +  "|%n", players.getName(0));
		System.out.format("|" + middle + "-" + "%-17s" + middle + "-" +  "|%n", ("Total: " + getScore((byte) 0, newT, isFinal)));

		for(byte i=0;i<newT.getHandSize((byte)0);i++){
			System.out.format("|" + middle + "-" + "%-17s" + middle + "-" +  "|%n", newT.getShowing((byte) 0, i));
		}//end for print dealers hand
		System.out.println("|" + top + "|");
		
		System.out.format("|%-17s|%-17s|%-17s|%-17s|%-17s|%n", players.getName(1), players.getName(2),players.getName(3), players.getName(4),players.getName(5));
		

		for(byte i = 1; i<6; i++){

			tempPrintx.add((" " + getScore(i, newT, isFinal)));
			if(i==5){
				System.out.format("|%-17s|%-17s|%-17s|%-17s|%-17s|%n", "Total:" + tempPrintx.get(0), "Total:" + tempPrintx.get(1),"Total:" + tempPrintx.get(2),"Total:" + tempPrintx.get(3),"Total:" + tempPrintx.get(4));
			}//end if print on last iteration
		}//end for print scores
		

		for(byte i = 1; i<6;i++){
			if(maxHand<newT.getHandSize(i)){
				maxHand=newT.getHandSize(i);
			}//end if get max hand size
		}//end for hand size
		
		for(byte i=0;i<maxHand;i++){
			ArrayList<String> tempPrint = new ArrayList<String>(5);
			for(byte j = 1;j<6;j++){
				if(newT.getHandSize(j)>(i)){
					tempPrint.add(newT.getShowing(j, i));
				}else{
					tempPrint.add(" ");
				}//end for does player have a card
			}//end for prep tempPrint
			
			System.out.format("|%-17s|%-17s|%-17s|%-17s|%-17s|%n", tempPrint.get(0), tempPrint.get(1), tempPrint.get(2), tempPrint.get(3), tempPrint.get(4));
		}//end for print players cards
		System.out.println("|" + top + "|");
		//end Drawing the table
	}//end draw table
	
	
	/**
	 * getScore computes a score not including face down cards when isFinal is false, and computes with all cards when true.
	 * @param playerId also the same as handId, in the future this needs to be changed to handId
	 * @param newT passes the table to get the hands
	 * @param isFinal True computes the score including face down cards, False does not
	 * @return byte with the score
	 */
	private byte getScore(byte playerId, Table newT, boolean isFinal){
		byte score = 0;
		boolean hasAce = false;
		byte faceValue = 0;
		boolean notUsedAceHigh = true;
		if(isFinal){
			for(byte i=0;i<newT.getHandSize(playerId);i++){
				faceValue = newT.getFaceValue(playerId, i);
				if(faceValue>10){
					faceValue = 10;
				}//correct to 10 for face cards
				if(faceValue != 1){
					if(((score+faceValue>21) && hasAce)&& notUsedAceHigh){
						score = (byte) (score + faceValue - 10);
						notUsedAceHigh = false;// prevents the -10 a second time if they keep getting low cards
					}else{
						score = (byte) (score + faceValue);
					}//end if gets optimal score with aces
				}else{
					hasAce=true;
					if(score+11>21){
						score = (byte) (score + 1);
						notUsedAceHigh = false;
					} else {
						score = (byte) (score + 11);
					}//end if has ace
				}//end if card identification		
			}//end for cycle though cards on final score
		}else{
			for(byte i=0;i<newT.getHandSize(playerId);i++){
				if(newT.getShowing(playerId, i) != "Face-Down"){
					faceValue = newT.getFaceValue(playerId, i);
					if(faceValue>10){
						faceValue = 10;
					}//correct to 10 for face cards
					if(faceValue != 1){
						if(((score+faceValue>21) && hasAce)&& notUsedAceHigh){
							score = (byte) (score + faceValue - 10);
							notUsedAceHigh = false;// prevents the -10 a second time if they keep getting low cards
						}else{
							score = (byte) (score + faceValue);
						}//end if gets optimal score with aces
					}else{
						hasAce=true;
						if(score+11>21){
							score = (byte) (score + 1);
							notUsedAceHigh = false;
						} else {
							score = (byte) (score + 11);
						}//end if has ace
					}//end if card identification	
				}//end if face-down score = score + 0
			}//end for cycle though cards
		}//end if is final
		return score;
	}//end getScore
	
	/**
	 * Returns the title of the game
	 * @return	String with title
	 */
	@Override
	public String getTitle() {
		return "Casino Blackjack";
	}//end getTitle

	/**
	 * This shows the instructions for blackjack
	 */
	@Override
	public void showInstructions() {
		//System.out.println("The objective is to get more points than the dealer without going over 21.\n");	
		System.out.println("The scoring rules of blackjack are:");
		System.out.println("Face cards (Kings, Queens, and Jacks) are counted as ten points.");	
		System.out.println("The player and dealer can count their own Ace as 1 point or 11 points.");	
		System.out.println("All other cards are counted as the numeric value shown on the card.\n");	
		System.out.println("The dealing rules are:");	
		System.out.println("After receiving their initial two cards, players have the option of getting a \"hit\"(taking additional cards)");	
		System.out.println("to bring their total value of cards to 21 points, or as close as possible without exceeding 21 (called \"busting\"). ");	
		System.out.println("The dealer will usually take hits when his cards total less than 17 points.\n");	
		System.out.println("Methods of winning are:");
		System.out.println("Players who do not bust and have a total higher than the dealer, win.");
		System.out.println("The dealer will lose if he or she busts, or has a lesser hand than the player who has not busted.");
		System.out.println("If the player and dealer have the same point total, this is called a \"push\" and the player typically doesn't win or lose money on that hand.");
	}//end showInstructions
	
	/**
	 * getByte gets a byte from the console and forces the user to keep trying until it is in range
	 * @param lowerRange lowest acceptable range for input
	 * @param upperRange highest acceptable range for input
	 * @return a byte in the range of lower to upper
	 * @throws Exception exception handled by main's handler
	 */
	private static byte getByte(byte lowerRange, byte upperRange) throws Exception{
		//Start Variable Declaration
		byte byteIn = 99;//99 is never a valid argument
		@SuppressWarnings("resource")
		Scanner input = new Scanner(System.in);
		//End Variable Declaration

		while(byteIn == 99){
			if(input.hasNextByte()){
				
				byteIn = input.nextByte();
				input.nextLine();//clears buffer
			
				if(byteIn>upperRange || byteIn<lowerRange){//detects bounds for integers
					System.out.println("Please enter " + lowerRange + " to " + upperRange);
					byteIn = 99;
				}//end out of bounds if

			}else{
				//Handles all non-byte input
				input.nextLine();//clears buffer
				System.out.println("Please enter " + lowerRange + " to " + upperRange);
			}//End if
		}//end while
		return byteIn;
	}//End getByte
}//end class BlackJack