package Assignment4;
	/****************************************************************
		** Program: GameDriver.java                                    **
		** Author: Jimmy                                               **
		** Date: 21 Oct 2012                                           **
		** Description: This class provides a main menu for the game   **
		** classes           										   **
		** Input and output are described in the java docs             **
		*****************************************************************/
	

//Start Import
import java.util.NoSuchElementException;
import java.util.Scanner;
//End Import


/**
 * This class provides a main menu for the game classes
 * <p>The main method is:<ul><li>
 * public static void main(String[] args) throws Exception
 * </ul>The utility methods are:<ul><li>
 * private static byte getByte(byte lowerRange, byte upperRange) throws Exception
 * <li>private static String getString(byte maxStrnLength) throws Exception</ul>
 *
 */
public class GameDriver {

	/**
	 * main creates the main menu and calls other classes to run games that use the Game interface
	 * @param args unused
	 * @throws Exception used to throw unhandled exceptions
	 */
	public static void main(String[] args) throws Exception {
		//start variable declaration
		CasinoPlayers  players;//contains the names and bank balances of players
		Scanner input = new Scanner(System.in);//created to clean up the stream
		String tempString;
		RandomGame randGame = new RandomGame();
		BlackJack bj = new BlackJack();
		CasinoWar cw = new CasinoWar();
		byte selection = 99;
		//end variable declaration
		
		System.out.println("Welcome to to wonderfull world of random console games!!!!!!!!!!");
		System.out.println("Please enter your name: ");
		
		try {
			tempString = getString((byte)10);//get player name
			players = new CasinoPlayers(tempString);//create casino players and add the player
			System.out.println("Thank you "+ players.getName((byte)1) + "\n");
			
			while(selection != 4){//main game menu
				System.out.println("With all this space think of all the activities we can do:\n");
				System.out.println("[1] " + randGame.getTitle());
				System.out.println("[2] " + bj.getTitle());
				System.out.println("[3] " + cw.getTitle());
				System.out.println("[4] You wouldn't quit on a best friend would you?");
				
				selection = getByte((byte)1,(byte)4);//get input
				
				if(selection == 1){//start games
					randGame.play();
				}else if (selection == 2){
					bj.play();
				}else if (selection == 3){
					cw.play();
				}//end if game selection
			}//end while main menu

		} catch (NoSuchElementException e) {//handles EOF in System.in gracefully
			System.out.println("Don't do that again\n");
			input.close();//garbage cleanup
			System.gc();//garbage cleanup
			System.exit(1);//exits with a status of 1
		}//end try-catch
		
		System.out.println("Well ok.  I thought we were best friends.  I'll just wait here playing roulette with this format program on your root.");
		input.close();//garbage cleanup
		System.gc();//garbage cleanup
		System.exit(0);//exits with a status of 0
	}//end main
	
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
	
	/**
	 * getString gets string input from the console and returns a string length equal to or less than the maxStrnLength
	 * @param maxStrnLength byte max length to return
	 * @return returns a string inputed from the console
	 * @throws Exception the exception is handled by main's handler
	 */
	private static String getString(byte maxStrnLength) throws Exception{
		//Start Variable Declaration
		String tempString = "";//empty string
		@SuppressWarnings("resource")
		Scanner input = new Scanner(System.in);
		//End Variable Declaration

		while(tempString == ""){
			if(input.hasNext()){
				
				tempString = input.next();
				tempString.trim();
				input.nextLine();//clears buffer
			
				if(tempString.length() > maxStrnLength){//Sets a max length for strings
					tempString = tempString.substring(0, maxStrnLength-1);
				}//end if max length

			}else{
				//Handles all non-byte input
				input.nextLine();//clears buffer or throws an exception
				System.out.println("Please try again");
			}//End if
		}//end while
		return tempString;
	}//End getString
	
}//end Class GameDriver