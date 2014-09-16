package Assignment4;
	/****************************************************************
		** Class: Table.java                                           **
		** Author: Jimmy                                               **
		** Date: 14 Oct 2012                                           **
		** Description: This class manages an ArrayList of Hands and   ** 
		** the Shoe                                                    **
		*****************************************************************/
	

import java.util.ArrayList;

/**
 * The Table class manages creation and dealing from the shoe.  
 * It also manages dealing to hands. 
 * <p>XXXXXCurrently one player may only have one handXXXXXXX
 * <p>The constructor is:<ul><li>
 * 	public Table(byte numberOfPlayers, byte numberOfDecks)</ul>
 * <p>The utility methods are:<ul><li>
 * 	public void shuffle()
 * <li>	public void dealCard(byte hand, boolean faceDown)
 * <li>	public void newHand(byte numberOfPlayers)
 * <li>public void flipCard(byte hand, byte cardId)</ul>
 * <p> The accessor methods are:<ul><li>
 * 	public int remainingShoe()
 * <li> public byte getHandSize(byte hand)
 * <li>	public byte getFaceValue(byte hand, byte card)
 * <li>	public String getShowing(byte hand, byte card)</ul>
 *
 */
public class Table {

	private Shoe newShoe;
	private ArrayList<Hand> hands;
	
	/**
	 * Constructor for Table
	 * @param  numberOfPlayers will equal number of hands//not used
	 * @param  numberOfDecks used to construct the shoe
	 */
	public Table(byte numberOfPlayers, byte numberOfDecks){
		newShoe = new Shoe(numberOfDecks);
		hands = new ArrayList<Hand>(numberOfPlayers);
	}//end constructor
	
	/**
	 * shuffle method calls shuffle from shoe class
	 */
	public void shuffle(){
		newShoe.shuffle();
	}//end shuffle
	
	/**
	 * remainingShoe returns the number of cards left in the shoe
	 * @return int remaining cards in shoe
	 */
	public int remainingShoe(){
		return newShoe.remaining();
	}//end remaining
	
	/**
	 * getFaceValue returns the face value 1-13 of a card in the hand
	 * @param  hand is the same as the player number 
	 * @param  card is the index of the cards in the hand
	 * @return byte face value 1-13
	 */
	public byte getFaceValue(byte hand, byte card){
		Hand tempHand = hands.get(hand);
		return tempHand.getFaceValue(card);
	}//end getFaceValue
	
	/**
	 * getShowing returns a String with the text name or face-down
	 * @param hand is the same as the player number
	 * @param card is the index of cards in the hand
	 * @return String with the text name of the card or face-down
	 */
	public String getShowing(byte hand, byte card){
		Hand tempHand = hands.get(hand);
		return tempHand.getShowing(card);
	}//end getShowing
	
	/**
	 * getHandSize returns a byte with the size of the requested hand
	 * @param hand the specific player's hand
	 * @return byte hand size
	 */
	public byte getHandSize(byte hand){
		Hand tempHand = hands.get(hand);
		return tempHand.getHandSize();
	}//end getHandSize
	
	/**
	 * deakCard calls dealCard from the shoe and adds a card to the hand with the dealCard utility method
	 * @param hand index same as player id
	 * @param faceDown face up or downs status
	 */
	public void dealCard(byte hand, boolean faceDown){
		if(hands.size() <= hand){//checks to see if the hand already exist
			hands.add(new Hand(this.newShoe.dealCard(), faceDown));
		}else {//adds a card to an already existing hand
			Hand tempHand = hands.get(hand);
			tempHand.dealCard(this.newShoe.dealCard(), faceDown);
			hands.set(hand,tempHand);
		}//end if hand isEmpty
	}//end dealCard
	
	/**
	 * NewHand Clears all old data but not the shoe, the shoe should be checked for having enough cards
	 * @param numberOfPlayers same as number of hands
	 */
	public void newHand(byte numberOfPlayers){
		hands.clear();
		hands = new ArrayList<Hand>(numberOfPlayers);
	}//end newHand
	
	/**
	 * Flips a card in a hand
	 * @param hand the hand to flip
	 * @param cardId the card to flip
	 */
	public void flipCard(byte hand, byte cardId){
		Hand tempHand = hands.get(hand);
		hands.set(hand, tempHand.flipCard(cardId));
	}//end flipCard
}//end class Table