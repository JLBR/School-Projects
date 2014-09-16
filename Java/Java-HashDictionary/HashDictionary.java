	    /****************************************************************
		** Class: HashDictionary.java                                  **
		** Author: Jimmy                                               **
		** Date: 2 Dec 2012                                            **
		** Description: This is a dictionary class that looks up words **
		** using a hash table                                          **
		*****************************************************************/

import java.io.File;
import java.io.FileNotFoundException;
import java.util.Hashtable;
import java.util.Scanner;

/**
 * This class creates a dictionary that uses a hash search
 */
public class HashDictionary implements Dictionary {
	Hashtable<String, String> dictionaryWords = new Hashtable<String, String>();
	double totalLookupTime = 0;
	int numberOfLookups = 0;
	
	/**
	 * The constructor calls the helper loadDictionary
	 * @param dictionary a file object
	 * @throws FileNotFoundException
	 */
	public HashDictionary(File dictionary) throws FileNotFoundException {
		loadDictionary(dictionary);
	}
	
	/**
	 * Contains searches the dictionary
	 * @param lookupWord the word to find
	 * @return returns true if found
	 */
	@Override
	public boolean contains(String lookupWord) {
		
		lookupWord = lookupWord.toLowerCase();
		
		double lookupStartTime = System.currentTimeMillis();

		boolean contains = dictionaryWords.containsValue(lookupWord);
		
		totalLookupTime += System.currentTimeMillis() - lookupStartTime;
		
		numberOfLookups++;
		return contains;
	}
	
	/**
	 * getAverageLookupTime returns the total time divided by the number of lookups
	 */
	@Override
	public double getAverageLookupTime() {
		return totalLookupTime/numberOfLookups;
	}

	/**
	 * loadDictionary reads the dictionary file
	 * @param dictionary a file object
	 * @throws FileNotFoundException if the file is not found
	 */
	@Override
	public void loadDictionary(File dictionary) throws FileNotFoundException {
		
		Scanner words = new Scanner(dictionary);
		
		while(words.hasNext()){
			String nextWord = words.nextLine();
			nextWord = nextWord.toLowerCase();
			dictionaryWords.put(nextWord, nextWord);
		}
		
		words.close();
		
	}
}
