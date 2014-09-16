	    /****************************************************************
		** Class: BinaryDictionary.java                                **
		** Author: Jimmy                                               **
		** Date: 2 Dec 2012                                            **
		** Description: This is a dictionary class that looks up words **
		** using a binary algorithm                                    **
		*****************************************************************/

import java.io.File;
import java.io.FileNotFoundException;
import java.util.ArrayList;
import java.util.Scanner;

/**
 * This class creates a dictionary that uses a binary search
 */
public class BinaryDictionary implements Dictionary {
	ArrayList<String> dictionaryWords = new ArrayList<String>();
	double totalLookupTime = 0;
	int numberOfLookups = 0;
	
	/**
	 * The constructor calls the helper loadDictionary
	 * @param dictionary a file object
	 * @throws FileNotFoundException
	 */
	public BinaryDictionary(File dictionary) throws FileNotFoundException {
		loadDictionary(dictionary);
	}

	@Override
	/**
	 * Contains searches the dictionary
	 * @param lookupWord the word to find
	 * @return returns true if found
	 */
	public boolean contains(String lookupWord) {
		
		boolean found = false;
		int lowerIndex = 0;
		int upperIndex = dictionaryWords.size()-1;
		
		double lookupStartTime = System.currentTimeMillis();
		
		while(found == false){
			
			int index = (upperIndex-lowerIndex)/2 + lowerIndex;
			byte compareValue = (byte) lookupWord.compareToIgnoreCase(dictionaryWords.get(index));
			
			if(compareValue == 0){
				found = true;
			}else if(compareValue<0){
				upperIndex = --index;
			}else{
				lowerIndex = ++index;
			}
			
			if(upperIndex<lowerIndex){
				break;
			}
			
		}
		
		totalLookupTime +=  System.currentTimeMillis() - lookupStartTime;

		numberOfLookups++;
		return found;
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
			dictionaryWords.add(words.nextLine());
		}
		
		words.close();	
		
		dictionaryWords.trimToSize();

	}
		
}
