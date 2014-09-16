	    /****************************************************************
		** Class: AnagramSolverDriver.java                             **
		** Author: Jimmy                                               **
		** Date: 2 Dec 2012                                            **
		** Description: This is the driver that interprets the command **
		** line and outputs the results of the anagram lookup          **
		*****************************************************************/

import java.io.File;
import java.io.FileNotFoundException;

/**
 * This class creates the dictionary objects and calls the anagramSolver
 * methods.  It outputs the word if found or null if not, and
 * the average time spent looking up words in the dictionary.
 */
public class AnagramSolverDriver {

	/**
	 * Outputs one deciphered anagram if found and the average time spent looking up the
	 * permutations.
	 * @param args format < anagram > < dictionary file > < dictionary type >
	 */
/**	public static void main(String[] args) {

		if(args.length!=3){
			System.out.println("Please use the format AnagramSolverDriver < anagram > < dictionary file > < dictionary type >");
			System.exit(1);
		}else {
			args[2] = args[2].toLowerCase();
			if(args[2].equalsIgnoreCase("l")&&args[2].equalsIgnoreCase("b")&&args[2].equalsIgnoreCase("h")){
				System.out.println("Please use the format AnagramSolverDriver < anagram > < dictionary file > < dictionary type >");
				System.out.println("Acceptable dictionary switches are:\nl - Linear Dictionary" +
						"\nb - Binary Dictionary\nh - Hashed Dictionary");
				System.exit(1);
			}
		}
		
		File dictionaryX = new File(args[1]);
		
		try {
			
			Dictionary dictionary = null;
			
			if(args[2]=="h"){
				dictionary = new HashDictionary(dictionaryX);
			}else if(args[2]=="b"){
				dictionary = new BinaryDictionary(dictionaryX);
			}else{
				dictionary = new LinearDictionary(dictionaryX);
			}

			AnagramSolver solver = new AnagramSolver(dictionary);
			
			String result = solver.solve(args[0]);
			
			if(result == null){
				System.out.println("No word was found, please try differnt dictionary file.");
			}else{
				System.out.println("Your deciphered results are "+ result);
			}
			
			System.out.print("Average lookup time in seconds: ");
			System.out.format("%.2f%n", (dictionary.getAverageLookupTime())/100);
			
		} catch (FileNotFoundException e) {
			
			System.out.println("ERROR: no file found for " + args[1]);
			System.exit(1);
		}	
		System.exit(0);
	}**/
}
