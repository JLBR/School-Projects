	    /****************************************************************
		** Class: GolfScoreDriver.java                                 **
		** Author: Jimmy                                               **
		** Date: 29 Oct 2012                                           **
		** Description: This class runs the golf score processing      **
		** It handles most of the significant errors in the main method**
		** Files used by and created by the program end in .sco        **
		*****************************************************************/

import java.io.File;
import java.io.IOException;
import java.nio.file.DirectoryIteratorException;
import java.nio.file.DirectoryStream;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

/**
 * GolfScoreDriver runs the main method and gets a directory listing of .sco files
 * <p>Main method<ul><li>
 * 	public static void main(String[] args) throws IOException, Exception
 * </ul><P>Utility methods are:<ul><li>
 * 	private static File choseFile(List<Path> dir) throws Exception
 * <li>private static List<Path> listScoreFiles() throws IOException
 * <li>private static byte getByte(byte lowerRange, byte upperRange) throws Exception
 * <li>private static String getString(byte maxStrnLength) throws Exception
 */
public class GolfScoreDriver {

	//private static final Exception NoScoreFiles = null;

	/**
	 * main calls the menus and handles major errors
	 * @param args not used
	 * @throws Exception from getByte to handle EOF in the console stream
	 * @throws IOException 
	 */
	public static void main(String[] args) throws IOException, Exception {
		
		File myFiles = null;
		Round round;
		byte selection = -1;
				
		System.out.println("Welcome to the golf score processing factory\n");
		
		while(selection !=3){
			
			System.out.println("What would you like to do");
			System.out.println("[1]Process an existing file");
			System.out.println("[2]Try your luck creating a random file");
			System.out.println("[3]Go back to something productive");
			
			selection = getByte((byte)1, (byte) 3);
			
			if(selection == 1){
				
				try{
					myFiles = choseFile(listScoreFiles());
					round = ReadRound.readRound(myFiles);
					
					round.showRound();
					
				} catch (NoScoreFiles e){
					System.out.println("No score files are in the current directory\n");
				} catch (CorruptFileException e){
					System.out.println("This file is corrupt");
				}	//end Try-Catch
						
			}else if(selection == 2){
				String fileName;
				
				System.out.println("\nWhat would you like to call the file?(maximum 8 characters)");
				fileName = getString((byte) 8);
				
				RandomScore.randomScore(fileName);
			}else{
				System.out.println("Goodby");
			}//end menu if
			
		}//end main loop
		
		System.gc();
		System.exit(0);
		
	}//end main
	
	/**
	 * choseFile creates the menu for files of .sco and returns a File object to open the file
	 * @param dir of List<Path> type passed from listFiles()
	 * @return File object selected
	 * @throws Exception throws exceptions for Path creation and the custom NoScoreFiles if no .sco files
	 * are in the directory
	 */
	private static File choseFile(List<Path> dir) throws Exception{
		
		if(dir.size()==0){
			throw new NoScoreFiles();
		}//end if no files exist
		
		System.out.println("Chose a round to view:");
		
		for(byte i = 0; i<dir.size();i++){
			String filesX = dir.get(i).toString();
			System.out.println("[" + (i+1) + "] " + filesX.substring(2));
		}//end for
		
		Path tempPath= dir.get(getByte((byte)1, (byte)dir.size())-1);
		
		return   tempPath.toFile();
		
	}//end choseFile
	
	/**
	 * listScoresFiles returns a list of paths containing the names of all .scr files in
	 * the local directory that could be used.
	 * This was modified from the code at http://openjdk.java.net/projects/nio/javadoc/java/nio/file/DirectoryStream.html
	 * 
	 * @return list object with .scr file paths
	 * @throws IOException thrown if the path is unreadable
	 */
	private static List<Path> listScoreFiles() throws IOException {
	   
		Path dir = Paths.get(".");//local directory
		List<Path> result = new ArrayList<>();//used to return the list object
       
		try (DirectoryStream<Path> stream = Files.newDirectoryStream(dir, "*.sco")) {
           
			for (Path entry: stream) {
				result.add(entry);
			}//end for
           
		} catch (DirectoryIteratorException e) {
			// I/O error encountered during the iteration, the cause is an IOException
			throw e.getCause();
		}//end try-catch
       
		return result;
   
	}//end
	   
	/**
	 * getByte gets a byte from the console and forces the user to keep trying until it is in range.
	 * The upper range must be lower than 99.
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
	 * getString returns a string of maxStrnLength or less from the console
	 * @param maxStrnLength
	 * @return String
	 * @throws Exception to catch EOF or EOS gracefully
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
	
}//end class GolfScoreDriver
