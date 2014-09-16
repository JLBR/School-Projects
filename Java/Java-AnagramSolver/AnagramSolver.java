import java.io.File;
import java.io.FileNotFoundException;

	    /****************************************************************
		** Class: AnagramSolver.java                                   **
		** Author: Jimmy                                               **
		** Date: 2 Dec 2012                                            **
		** Description: In conjunction with the dictionary classes     **
		** this class permutes and looks up anagrams                   **
		*****************************************************************/


/**
 * AnagramSolver class tries every permutation of a provided letter set compared with 
 * a provided dictionary and returns NULL if not found, or the deciphered anagram
 */
public class AnagramSolver {

	final String NOT_FOUND = null;
	Dictionary dictionary;
	String returnString;
	
	/**
	 * Outputs one deciphered anagram if found and the average time spent looking up the
	 * permutations.
	 * @param args format < anagram > < dictionary file > < dictionary type >
	 */
	public static void main(String[] args) {

		if(args.length!=3){
			System.out.println("Please use the format AnagramSolver < anagram > < dictionary file > < dictionary type >");
			System.exit(1);
		}else {
			args[2] = args[2].toLowerCase();
			if(args[2].equalsIgnoreCase("l")&&args[2].equalsIgnoreCase("b")&&args[2].equalsIgnoreCase("h")){
				System.out.println("Please use the format AnagramSolver < anagram > < dictionary file > < dictionary type >");
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
				System.out.println("Your deciphered result is: "+ result);
			}
			
			System.out.print("Average lookup time in seconds: ");
			System.out.format("%.2f%n", (dictionary.getAverageLookupTime())/100);
			
		} catch (FileNotFoundException e) {
			
			System.out.println("ERROR: no file found for " + args[1]);
			System.exit(1);
		}	
		System.exit(0);
	}
	
	/**
	 * The constructor loads a provided dictionary reference
	 * @param dictionary
	 */
	AnagramSolver(Dictionary dictionary){
		this.dictionary = dictionary;
	}

	/**
	 * solve checks the dictionary to see if the anagram is not ciphered
	 * If not, it tries each permutation until it finds an answer, or runs
	 * out of permutations
	 * @param anagram
	 * @return returns null if not found, or the string representing the dictionary word if found
	 */
	public String solve(String anagram){
		
		returnString = NOT_FOUND;
		
		if(dictionary.contains(anagram)){
			returnString = anagram;
		}else{
			if(returnString == NOT_FOUND){
				anagramShuffle("", anagram);
			}
		}
		return returnString;
	}
	
	
	/**
	 * Based off of the example from page 575
	 * This is a tail recursive permutation generate.  It will breakdown
	 * the original string and generate permutations on the smaller sections.
	 * This is not 100% efficient in that it will generate duplicate permutations
	 * @param permutation needs to be an empty string when called the first time
	 * @param original the original anagram
	 * @return not used anymore
	 */
	private String anagramShuffle(String permutation, String original){
		
		 if (original.length() <= 1){
		 
			if(dictionary.contains(permutation + original)){
				returnString = permutation + original;
			}

		 } else {
			  
			 for (int i = 0; i < original.length(); i++) {
				 
				 String newString = original.substring(0, i) + original.substring(i + 1);
				 
				 if(returnString == null){
					 anagramShuffle(permutation + original.charAt(i), newString);
				 }
			 } 
		 }
		return original;
	}
}
