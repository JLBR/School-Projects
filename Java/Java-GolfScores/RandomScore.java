	    /****************************************************************
		** Class: RandomScore.java                                     **
		** Author: Jimmy                                               **
		** Date: 29 Oct 2012                                           **
		** Description: This class creates an imaginary round of up to **
		** 8 players and 18 holes                                      **
		*****************************************************************/

import java.io.File;
import java.io.FileNotFoundException;
import java.io.PrintStream;

/**
 * RandomScore class creates a file .sco with a random number of players and holes
 * <p>Main method<li>
 * 	public static void randomScore(String fileName)
 *
 */
public class RandomScore {

	public static void randomScore(String fileName){
		
		byte randomNums = (byte) (Math.random()*8+1);
		Round round = new Round(randomNums);
		byte players = (byte) (Math.random()*8+1);
		byte holes = (byte) (Math.random()*18+1);
		
		for(byte i= 0;i<holes;i++){
			for(byte j = 0; j<players;j++){
				round.setScore((byte) (i+1), j, (byte)(Math.random()*6+1));
			}//end players loop
			round.setPar(i, (byte) (Math.random()*6+1));
		}//end holes loop
		
		File file = new File(fileName+".sco");
		
		try {
			
			PrintStream myGolfFile = new PrintStream(file);
			
			for(byte i = 0; i<round.getNumberOfHoles();i++){
				String output = "";
				for(byte j = 0; j<round.getNumberOfPlayers();j++){
					output = output + " " + round.getScore((byte)j, (byte) i);
				}//end for players
				output = output +" "+ round.getPar(i);
				myGolfFile.println(output);
			}//end for holes
			
			myGolfFile.close();
			
		} catch (FileNotFoundException e) {
			System.out.println("That file does not exist");
			//e.printStackTrace();
		} catch (SecurityException e){
			System.out.println("You do not have access to that file");
		}//end Try-Catch write file
		

		
	}//end randomScore

}//end class RandomScore