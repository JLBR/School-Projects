package Assignment4;
	/****************************************************************
		** Class: Card.java                                            **
		** Author: Jimmy                                               **
		** Date: 14 Oct 2012                                           **
		** Description: This class creates a poker card and allows     **
		** access to the values                                        **
		** The only value that can change is the faceDown boolean      **
		*****************************************************************/
	
/**
 * Card class creates the card object that holds the values representing a poker card from a standard 52 card deck.
 * <p>The constructor method is:
 * <ul>
 * <li> Card(byte passedValue, boolean passedFaceDown)</ul>
 * <p>Utility methods are:<ul><li>
 * 	public Card flipCard()</ul>
 * <p>Accessor methods include:
 * <ul><li> public String showing()  returns a string with Face-Down or the text value of  card
 * <li> public byte faceValue()  returns the numeric face value of the card.</ul>
 */
public class Card {
	
	final private byte value;//value 1-52
	private boolean faceDown;//true = face down
	final private String textValue;//text value computed during construction
	final private byte faceValue;//numeric value 1-13 (aces are 1, jacks, queens, kings are 11, 12, 13 respectively
	

	/**
	 * Constructor for card class.  All calculations are done in the construction rather than when using the accessor methods.
	 * @param passedValue raw 1-52 value representing a card
	 * @param passedFaceDown boolean display or hide value to the player
	 */
	Card(byte passedValue, boolean passedFaceDown){//Constructor
		
		//Constant declaration start
		final byte SUIT_HEARTS = 0;
		final byte SUIT_SPADES = 26;
		final byte SUIT_DIAMONDS = 13;
		final byte SUIT_CLUBS = 39;
		final byte CARD_KING = 13;
		final byte CARD_JACK = 11;
		final byte CARD_QUEEN = 12;
		final byte CARD_TEN = 10;
		final byte CARD_NINE = 9;
		final byte CARD_EIGHT = 8;
		final byte CARD_SEVEN = 7;
		final byte CARD_SIX = 6;
		final byte CARD_FIVE = 5;
		final byte CARD_FOUR = 4;
		final byte CARD_THREE = 3;
		final byte CARD_TWO = 2;
		final byte CARD_ACE = 1;
		//Constant declaration end
		
		//Start variable deceleration
		this.value = passedValue;
		byte suitValue;//stores computed suit value
		byte faceValue;//stores computed face value
		String cardName = "";//Stores text name of the card
		//End variable declaration
					
		//Breaks down the values to specific cards
		if (this.value>SUIT_CLUBS){
			suitValue = SUIT_CLUBS;
			faceValue = (byte) (this.value - SUIT_CLUBS);
		} else if (this.value>SUIT_SPADES){
			suitValue = SUIT_SPADES;
			faceValue = (byte) (this.value - SUIT_SPADES);
		} else if(this.value>SUIT_DIAMONDS){
			suitValue = SUIT_DIAMONDS;
			faceValue = (byte) (this.value - SUIT_DIAMONDS);
		}else {
			suitValue = SUIT_HEARTS;
			faceValue = (byte) (this.value - SUIT_HEARTS);
		}//end if card breakdown
			
		switch (suitValue)//Presents suits as text instead of numbers
		{
			case SUIT_HEARTS: cardName = String.format(" of Hearts");
			break;
			case SUIT_SPADES: cardName = String.format(" of Spades");
			break;
			case SUIT_DIAMONDS: cardName = String.format(" of Diamonds");
			break;
			case SUIT_CLUBS: cardName = String.format(" of Clubs");
			break;		
		}//end switch
			
		switch (faceValue)//Presents values as text instead of numbers
		{
			case CARD_KING: cardName = String.format("King" + cardName);
			break;
			case CARD_JACK: cardName = String.format("Jack" + cardName);
			break;
			case CARD_QUEEN: cardName = String.format("Queen" + cardName);
			break;
			case CARD_ACE: cardName = String.format("Ace" + cardName);
			break;
			case CARD_TWO: cardName = String.format("Two" + cardName);
			break;
			case CARD_THREE: cardName = String.format("Three" + cardName);
			break;
			case CARD_FOUR: cardName = String.format("Four" + cardName);
			break;
			case CARD_FIVE: cardName = String.format("Five" + cardName);
			break;
			case CARD_SIX: cardName = String.format("Six" + cardName);
			break;
			case CARD_SEVEN: cardName = String.format("Seven" + cardName);
			break;
			case CARD_EIGHT: cardName = String.format("Eight" + cardName);
			break;
			case CARD_NINE: cardName = String.format("Nine" + cardName);
			break;
			case CARD_TEN: cardName = String.format("Ten" + cardName);
			break;
		}//end switch
		
		this.faceValue = faceValue;
		this.textValue = cardName;
		this.faceDown = passedFaceDown;
	}//end constructor
	
	/**
	 * Accessor method for the faceValue property.
	 * @return byte face value
	 */
	public byte faceValue(){
		return this.faceValue;
	}//end faceValue
	
	/**
	 * Flips the card to face up if it was not previously
	 */
	public Card flipCard(){
		this.faceDown = false;
		return this;
	}//end flipCard
	
	/**
	 * Accessor method for the faceDown property and the text name of the card
	 * @return String with the text value or faced-down
	 */
	public String showing(){
		
		if(this.faceDown){
			return "Face-Down";
		} else{			
			return this.textValue;
		}//end if faceDown
	}//end showing
	
}