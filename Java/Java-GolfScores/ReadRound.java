	    /****************************************************************
		** Class: ReadRound.java                                       **
		** Author: Jimmy                                               **
		** Date: 29 Oct 2012                                           **
		** Description: This class reads from an .sco file and returns **
		** a round object for use by the round class to output the     **
		** results                                                     **
		*****************************************************************/

import java.io.File;
import java.io.FileNotFoundException;
import java.util.NoSuchElementException;
import java.util.Scanner;

/**
 * ReadRound class reads from a file to create a round object
 * <p>Constructor<ul><li>
 * public static Round readRound(File myFile) throws CorruptFileException
 */
public class ReadRound {

	static Round round;
	
	public static Round readRound(File myFile) throws CorruptFileException{
		
			try{
			
			if(myFile.canRead()){
				
				Scanner fileReader = new Scanner(myFile);
				
				if(fileReader.hasNextByte()){
					
					String firstHole = fileReader.nextLine();
					Scanner thisLine = new Scanner(firstHole);
					
					byte score = thisLine.nextByte();
					
					if(thisLine.hasNextByte()==false || score<1){
						//if there are not at least 2 bytes on the first line the file is incorrectly formatted
						//If the score is not>0 then it is invalid
						
						fileReader.close();
						thisLine.close();
						throw new CorruptFileException();
						
					}//end incorrect format
					
					byte tempPlayers = 0;
					byte holes = 1;
					round = new Round(score);
					
					while(thisLine.hasNextByte()){

						score = thisLine.nextByte();
						
						if(score<1){
							fileReader.close();
							thisLine.close();
							throw new CorruptFileException();
						}//end if invalid score
						
						if(thisLine.hasNextByte()){
							
							tempPlayers++;
							round.setScore(holes, (tempPlayers), score);

						}else{
							round.setPar(holes, score);
						}//end if first hole
						
					}//first hole
					
					while(fileReader.hasNextLine()){
						
						firstHole = fileReader.nextLine();
						thisLine = new Scanner(firstHole);
						
						if(thisLine.hasNextByte()==false){
							
							fileReader.close();
							thisLine.close();
							throw new CorruptFileException();
				
						}//end if no bytes in line
						
						score = thisLine.nextByte();
						
						if(score<1){
							
							fileReader.close();
							thisLine.close();
							throw new CorruptFileException();
						}//end if invalid score
						
						tempPlayers = 0;
						holes++;

						round.setScore(holes, tempPlayers, score);

						while(thisLine.hasNextByte()){
	
							score = thisLine.nextByte();
							
							if(score<1){
								fileReader.close();
								thisLine.close();
								throw new CorruptFileException();
							}//end if invalid score
							
							if(thisLine.hasNextByte()){

								tempPlayers++;
								round.setScore(holes, tempPlayers, score);

							}else{
								round.setPar(holes, score);
							}//end if add hole
							
						}//end while next hole
						
						if(tempPlayers+1!=round.getNumberOfPlayers()){
							fileReader.close();
							thisLine.close();
							throw new CorruptFileException();
						}//Incorrect number of players
						
					}//end while read all other holes

					
					thisLine.close();
					
				} else{
					fileReader.close();
					throw new CorruptFileException();
				}//empty file
				
				fileReader.close();
				
			} else {
				System.out.println("This file is not accessable\n");
			}//end if readable

		} catch(FileNotFoundException e) {
			System.out.println("The file does not exist");
		} catch(SecurityException e){
			System.out.println("You do not have permission to read that file");
		} catch (NoSuchElementException e){
			throw new CorruptFileException();
		}
		
		return round;
		
	}//end readRound
	
}//end class ReadRound
