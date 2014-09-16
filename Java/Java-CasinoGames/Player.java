package Assignment4;
	/****************************************************************
		** Class: Player.java                                          **
		** Author: Jimmy                                               **
		** Date: 14 Oct 2012                                           **
		** Description: This class manages the Player object           **
		*****************************************************************/
	

/**
 * The Player player class defines the players human and AI
 * <p>The constructor is:<ul>
 * <li>public Player(String playerName, double startingBalance, boolean type)</ul>
 * <p>Accessor methods are:
 * <ul><li>	public String getName()  returns the player name
 * <li>	public boolean getType()  returns the type of player
 * <li>	public double getBalance() returns the cash on hand
 * </ul> All other methods are not implemented yet
 */
public class Player {

	//declare class variables
	private String name;
	private double balance;
	private boolean npc;
	//end class variables
	
	/**
	 * Constructor for player
	 * @param playerName String of the player name
	 * @param startingBalance Starting cash on hand
	 * @param type Human = false or AI = true
	 */
	public Player(String playerName, double startingBalance, boolean type){//class constructor
		this.name = playerName;
		this.balance = startingBalance;
		this.npc = type;
	}//end constructor
	
	/**
	 * Returns the player name
	 * @return String player name
	 */
	public String getName(){//getter for name
		return this.name;
	}//end get name
	
	/**
	 * Returns the type of player
	 * @return true = AI, false = human
	 */
	public boolean getType(){//getter for player type
		return this.npc;
	}//end getType
	
	/**
	 * Not implemented
	 * @return
	 */
	public boolean addBalance(){//add to balance
		boolean success = false;		
		return success;
	}//end addBalance
	
	/**
	 * not implemented
	 * @return
	 */
	public boolean reduceBalance(){	//reduce balance
		boolean success = false;		
		return success;
	}

	/**
	 * Returns the current balance
	 * @return double balance
	 */
	public double getBalance() {//getter for balance
		return this.balance;
	}//end getBalence

}//end Player class
