package Assignment4;
	/****************************************************************
		** Class: Shoe.java                                            **
		** Author: Jimmy                                               **
		** Date: 14 Oct 2012                                           **
		** Description: This class manages an array of Cards and keeps **
		** track of the next one to be dealt                           **
		*****************************************************************/
	

/**
 * This class contains the shoe which cards are dealt from
 * <p>The constructor is:<ul>
 * <li>Shoe(byte numOfDecks)  the number of cards in shoe are set to numOfDecks*52</ul>
 * <p> Accessor methods are:<ul>
 * <li>public void shuffle()  Shuffles the deck
 * <li>public byte dealCard()  Deals a single card returning a value 1-52
 * <li>public int remaining()  returns the number of cards left in the shoe</ul>
 */
public class Shoe {
	
	//start class variables
	private byte deck[];//contains number of decks * 52 cards
	private int deckMarker;//contains the current location of the next card to be dealt
	//end class variables
	
	/**
	 * This is the constructor for Shoe.  The deck is shuffled on creation
	 * @param numOfDecks byte representing the number of decks to put into the shoe
	 */
	Shoe(byte numOfDecks){
		
		this.deck = new byte[52*numOfDecks];
		
		for(byte i=0; i<numOfDecks; i++ ){ 
			
			byte index = 1;
			while(index<53){
				deck[(index-1)+(i*52)]= index; 
				//System.out.println(deck[index-1]);
				index++;
			}//end while
		
		}//end for	
		
		this.shuffle();
		deckMarker = -1;
		
	}//end constructor
	
	
	/**
	 * Shuffles the deck
	 */
	public void shuffle(){
		
		int deckSize = this.deck.length;
		
		for(int i=0; i<deckSize; i++){
			byte tempCard1;
			byte tempCard2;
			int randomPosition = (int) (Math.random()*deckSize);
			
			tempCard1 = deck[i];
			tempCard2 =deck[randomPosition];
			deck[i] = tempCard2;
			deck[randomPosition] = tempCard1;
		}//end for loop
	}//end shuffle method
	
	/**
	 * Deals one card from the top of the deck
	 * @return byte 1-52 representing a card
	 */
	public byte dealCard(){
		
		deckMarker++;
		return deck[deckMarker];
	}//end dealCard method
	
	/**
	 * Returns how many cards are remaining.
	 * @return int of how many cards are remaining
	 */
	public int remaining(){
		return (deck.length-deckMarker-1);
	}
	
}//end Class Shoe
