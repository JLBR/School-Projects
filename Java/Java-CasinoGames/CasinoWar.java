	/********************************************************************
		** Program: CasinoWar.java                                      **
		** Author: Jimmy                                                **
		** Date: 21 Oct 2012                                            **
		** Description: This class plays the simple card game casino war**
		** Input and output are described in the java docs              **
		*****************************************************************/

package Assignment4;

import java.util.ArrayList;
import java.util.Scanner;

/**
 * CasinoWar plays the simple card comparison game war
 * <p>The main external method is:<ul><li>
 *	public void play() </ul>
 *<p>The internal methods are:<ul><li>
 *	private void main() throws Exception
 * 	<li>private void newTable() throws Exception
 * <li>	private void drawTable(Table newT, boolean isFinal)
 * <li> public void showInstructions()
 * <li>	public String getTitle()
 * <li>	private static byte getByte(byte lowerRange, byte upperRange) throws Exception
 */
public class CasinoWar implements Game, Instructions {

	/**
	 * play calls main for casino war
	 */
	public void play() {
			
		try {
			main();
		} catch (Exception e) {//never gets here

			e.printStackTrace();
		}//end try catch


	}//end play

	/**
	 * main Runs the war game.  It needs to be updated to handle betting.
	 * @throws Exception
	 */
	private void main() throws Exception{
		
		//start variable declaration
		byte selection = 99;
		//end variable declaration
		
		while(selection != 3){//main game menu
			System.out.println("Lets go to WAR!!!:\n");
			System.out.println("[1] Go to WAR!!!" );
			System.out.println("[2] Read the instructions" );
			System.out.println("[3] Off to other fun things.");
			
			selection = getByte((byte)1,(byte)3);//get input
			
			if(selection == 1){//start games
				newTable();
			}else if (selection == 2){
				showInstructions();
			}//end if game selection
		}//end while main menu
		
	}//end main
	
	/**
	 * newTable creates the deck and plays the main loop of the game
	 * @throws Exception
	 */
	private void newTable() throws Exception{
		
		//Start variable declaration
		byte selection = 99;
		Table newT = new Table((byte)6,(byte)1);
		@SuppressWarnings("unused")
		CasinoPlayers players = new CasinoPlayers();
		//byte maxHand = 0;
		//end variable declaration

		
		for(byte i = 0; i<2; i++){//deal first card
				newT.dealCard(i, false);
		}//end for deal first card
		
		drawTable(newT, false);//draw the table for the first time
		
		//System.out.println("\nOther players at the table are playing right now\nPlease wait.");
		//Thread.sleep(4000);//pauses to make it appear as that others are playing
		
		//newT = aiPlay(newT);
		
		while(selection != 2){
			byte playerScore = newT.getFaceValue((byte) 1, (byte) (newT.getHandSize((byte) 1)-1));
			byte dealerScore = newT.getFaceValue((byte) 0, (byte) (newT.getHandSize((byte) 0)-1));
			
			
			if(playerScore>dealerScore){//if win
				System.out.println("You WIN!!!!");
				selection = 2;	
			}else if(playerScore==dealerScore){//go to war
				System.out.println("[1]Go to War or [2]Surrender(why would you do this?)");
				selection = getByte((byte)1,(byte)2);
				if(selection == 1){
					System.out.println("Discarding");
					Thread.sleep(2000);
					newT.dealCard((byte) 0, false);
					newT.dealCard((byte) 1, false);
					drawTable(newT, true);
					playerScore = newT.getFaceValue((byte) 1, (byte) (newT.getHandSize((byte) 1)-1));
					dealerScore = newT.getFaceValue((byte) 0, (byte) (newT.getHandSize((byte) 0)-1));
					
					if(playerScore>=dealerScore){
						System.out.println("You WIN!!!!");
						selection = 2;	
					}else{
						System.out.println("You lose, come back soon!!!");
						selection = 2;	
					}//end check last card
					
				}else{
					System.out.println("You lose, come back soon!!!");
					selection = 2;	
				}
			}else{//you lose
				System.out.println("You lose, come back soon!!!");
				selection = 2;	
			}//end win lose draw
		}//end while loop

	}//end newTable
	
	/**
	 * drawTable creates the play field where all the cards, scores, and players are shown.
	 * @param newT provides the hand information
	 * @param isFinal flips all the cards face up and show the final scores.//not used in war always true
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
		System.out.format("|" + middle + "-" + "%-17s" + middle + "-" +  "|%n", ("Total: " + newT.getFaceValue((byte)0,  (byte) (newT.getHandSize((byte) 0)-1) )));

		for(byte i=0;i<1;i++){
			System.out.format("|" + middle + "-" + "%-17s" + middle + "-" +  "|%n", newT.getShowing((byte) 0, (byte) (newT.getHandSize((byte)0)-1)));
		}//end for print dealers hand
		System.out.println("|" + top + "|");
		
		System.out.format("|%-17s|%-17s|%-17s|%-17s|%-17s|%n", "", "",players.getName(1), "","");
		

		for(byte i = 1; i<6; i++){

			tempPrintx.add((" " + newT.getFaceValue((byte)1,  (byte) (newT.getHandSize((byte) 1)-1))));
			if(i==5){
				System.out.format("|%-17s|%-17s|%-17s|%-17s|%-17s|%n", "", "","Total:" + tempPrintx.get(0),"","");
			}//end if print on last iteration
		}//end for print scores
		

		for(byte i = 1; i<2;i++){
			if(maxHand<newT.getHandSize(i)){
				maxHand=newT.getHandSize(i);
			}//end if get max hand size
		}//end for hand size
		
		for(byte i=0;i<1;i++){
			ArrayList<String> tempPrint = new ArrayList<String>(5);
			for(byte j = 1;j<2;j++){
				if(newT.getHandSize(j)>(i)){
					tempPrint.add(newT.getShowing(j, (byte) (newT.getHandSize(j)-1)));
				}else{
					tempPrint.add(" ");
				}//end for does player have a card
			}//end for prep tempPrint
			
			System.out.format("|%-17s|%-17s|%-17s|%-17s|%-17s|%n","", "", tempPrint.get(0),"", "");
		}//end for print players cards
		System.out.println("|" + top + "|");
		//end Drawing the table
	}//end draw table
	
	/**
	 * Prints the instructions for casino war
	 */
	public void showInstructions() {
		System.out.println("Casino war is played with a 52 card poker deck.");
		System.out.println("Cards are valued from Ace(1) to King(13).\n");
		System.out.println("At the beginning the dealer will deal one card to themselves and one to the player face up.");
		System.out.println("If the player has a higher value than the dealer the player wins.");
		System.out.println("If the player has an equal value to the dealer then the dealer and player go to War.\n");
		System.out.println("During war the dealer discards three cards, then deals one to the player.");
		System.out.println("Next the dealer discards thee more and deals one to themselves.");
		System.out.println("If the score is tied or the player has a higher score the player wins.\n");
	}//end showInstructions

	/**
	 * getTitle returns the string Casino War
	 */
	public String getTitle() {
		return "Casino War";
	}//end getTitle

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
	
}//end class CasinoWar
