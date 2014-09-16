	    /****************************************************************
		** Class: LinearDictionary.java                                **
		** Author: Jimmy                                               **
		** Date: 2 Dec 2012                                            **
		** Description: This is a dictionary class that looks up words **
		** using a linear algorithm                                    **
		*****************************************************************/

import java.io.File;
import java.io.FileNotFoundException;
import java.util.Scanner;

/**
 * This class creates a dictionary that uses a linear search
 */
public class LinearDictionary implements Dictionary {
	String[] dictionaryWords = null;
	double totalLookupTime = 0;
	int numberOfLookups = 0;
	
	/**
	 * The constructor calls the helper loadDictionary
	 * @param dictionary a file object
	 * @throws FileNotFoundException
	 */
	public LinearDictionary(File dictionary) throws FileNotFoundException {
		loadDictionary(dictionary);
	}

	/**
	 * Contains searches the dictionary
	 * @param lookupWord the word to find
	 * @return returns true if found
	 */
	@Override
	public boolean contains(String lookupWord) {
		
		boolean found = false;
		int index = 0;
		
		double lookupStartTime = System.currentTimeMillis();
		
		while(found == false){

			if(dictionaryWords[index].compareToIgnoreCase(lookupWord)==0){
				found = true;
			}
			index++;

			if(index>=dictionaryWords.length){
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
		int index = 0;
		dictionaryWords = new String[100];
		
		while(words.hasNext()){
			
			if(dictionaryWords.length<=index){
				
				String[] tempDic = dictionaryWords;
				dictionaryWords = new String[dictionaryWords.length+100];
				
				for(int i=0; i<tempDic.length;i++){
					dictionaryWords[i] = tempDic[i];	
				}
			}
			dictionaryWords[index] = words.nextLine();
			index++;

		}
		
		words.close();	
		
		//Remove unused array
		String[] tempDic = dictionaryWords;
		dictionaryWords = new String[index];
			
		for(int i=0; i<index;i++){
			dictionaryWords[i] = tempDic[i];	
		}
		
	}
}
