package Assignment4;
	/****************************************************************
		** Class: Hand.java                                            **
		** Author: Jimmy                                               **
		** Date: 14 Oct 2012                                           **
		** Description: This class manages an ArrayList of Cards       **
		*****************************************************************/
	

import java.util.ArrayList;


/**
 * The Hand class manages the cardsInHand array.
 * <p>The constructor is:
 * <ul><li>	Hand(byte firstCard, boolean faceDown)</ul>
 * <p> Utility methods are(these change the size of the array):
 * <ul><li>	public void dealCard(byte cardValue, boolean faceDown) appends one card to the array
 * <li>	public String discard(byte cardIndex) removes a card from the array
 * <li> public Hand flipCard(byte cardId)</ul>
 * <p> Accessor methods are:
 * <ul><li>public String getShowing(int cardIndex)
 * <li> public byte getFaceValue(int cardIndex)
 * <li>	public byte getHandSize()
 * <li>	public String discard(byte cardIndex)
 * </ul>
 */
public class Hand {
	
	//begin class variables
	ArrayList<Card>cardsInHand = new ArrayList<Card>();//Store card objects
	//end class variables
	
	/**
	 * Constructor method adds the first card to the hand
	 * @param firstCard value from deal card in shoe
	 * @param faceDown value set based on game and style
	 */
	Hand(byte firstCard, boolean faceDown){
		this.cardsInHand.add(new Card(firstCard, faceDown));
	}//end constructor
	
	/**
	 * GetFaceValue method returns the face value of a card
	 * @param cardIndex used to identify a specific card in the hand
	 * @return face value of the card
	 */
	public byte getFaceValue(int cardIndex){
		Card tempCard = cardsInHand.get(cardIndex);
		return tempCard.faceValue();
	}//end getFaceValue
	
	/**
	 * getShowing method returns the name of the card or face-down
	 * @param cardIndex used to identify a specific card in the hand
	 * @return name of the card or face-down
	 */
	public String getShowing(int cardIndex){
		Card tempCard = cardsInHand.get(cardIndex);
		return tempCard.showing();
	}//end getShowing
	
	/**
	 * getHandSize method returns the number of cards in the hand
	 * @return byte with the total number of card objects
	 */
	public byte getHandSize(){
		return (byte) cardsInHand.size();
	}//end getHandSize
	
	/**
	 * discard method removes a card from the hand and returns the name of the discarded card
	 * @param cardIndex
	 * @return card name
	 */
	public String discard(byte cardIndex){
		
		Card tempCard = cardsInHand.get(cardIndex);
		String discarded = tempCard.showing();
		
		cardsInHand.remove(cardIndex);
		return discarded;		
	}//end discard
	
	/**
	 * dealCard adds a card to the hand
	 * @param cardValue byte 1-52
	 * @param faceDown true = face-down
	 */
	public void dealCard(byte cardValue, boolean faceDown){
		cardsInHand.add(new Card(cardValue, faceDown));
	}//end dealCard
	
	/**
	 * Calls flip card and replaces the card in the ArrayList with a flipped version of the card
	 * @param cardId byte indicating which card in hand
	 * @return returns a hand with a filpped card
	 */
	public Hand flipCard(byte cardId){
		Card tempCard;
		tempCard = cardsInHand.get(cardId);
		cardsInHand.set(cardId, tempCard.flipCard());
		return this;
	}//end flipCard
}//end Hand Class
