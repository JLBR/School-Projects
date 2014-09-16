package Assignment4;
	/****************************************************************
		** Class: CasinoPlayers.java                                   **
		** Author: Jimmy                                               **
		** Date: 14 Oct 2012                                           **
		** Description: This class manages an ArrayList of players     **
		*****************************************************************/
	

//begin import
import java.util.ArrayList;
//end import

/**
 * This class wraps the Player class creating a flexible array to add and remove players.<p>
 * The constructor methods are:<ul><li>
 * public CasinoPlayers(String humanName)
 * <li>public CasinoPlayers()</ul>
 * <p>Static accessor member methods are:
 * <li>public static String getName(int playerId)
 * <li>public static double getBalance(int playerId)
 * <li>public boolean isNPC(int playerId)
 * <li>public boolean addPlayer(String name, double balance, boolean npc)</ul>
 * 
 */
public class CasinoPlayers {
	
	//declares class variables
	private static ArrayList<Player>players = new ArrayList<Player>();//Lists all players in the casino
	//private ArrayList<Double> bets;//for future use
	//end declaration
	
	/**
	 * Creates an array of Player objects with 6 players.  Spot 0 will always be the dealer.  Spot 1 will always be the human.
	 * @param humanName adds a name for the human player
	 */
	public CasinoPlayers(String humanName){//Constructor
		Player newPlayer = new Player("Dealer Jammie", 70000.0, true);//the dealer is always in slot 0
		CasinoPlayers.players.add(newPlayer);
		 newPlayer = new Player(humanName, 200.0, false);//the human player is always in slot 1
		CasinoPlayers.players.add(newPlayer);
		 newPlayer = new Player("Mark", 2000.0, true);//NPC
		CasinoPlayers.players.add(newPlayer);
		 newPlayer = new Player("George", 70000.0, true);//NPC
		CasinoPlayers.players.add(newPlayer);
		 newPlayer = new Player("Vince", 50000.0, true);//NPC
		CasinoPlayers.players.add(newPlayer);
		 newPlayer = new Player("Dizzy", 10000.0, true);//NPC
		CasinoPlayers.players.add(newPlayer);
		//this.bets = null;
	}//end Constructor
	
	/**
	 * Overloaded constructor to allow access to the static accessor methods
	 */
	public CasinoPlayers(){//empty constructor
	}
	
	/**
	 * Used to add a player.  Currently this only sets the human players information
	 * @param name The player's name
	 * @param balance The player's starting balance
	 * @param npc Sets the player as an AI or human
	 * @return true if successful
	 */
	public boolean addPlayer(String name, double balance, boolean npc){//add a player to the casino
		boolean success = false;//Return false to indicate failure, ****not implemented yet ****
		//int listLenght;//used to test needing to append or replace a player
		Player newPlayer = new Player(name, balance, npc);
		
		if (npc == false){//the human player is always in slot 1
			CasinoPlayers.players.set(1, newPlayer);
			success = true;//return true to indicate success
		}//end if npc
		
		return success;
	}//end addNewPlayer method
	
	/**
	 * Used to get the player's balance from the ArrayList
	 * @param playerId position in Players ArrayList
	 * @return double with the player's balance
	 */
	public  double getBalance(int playerId){//getBalance wrapper for player class
		Player tempPlayer = CasinoPlayers.players.get(playerId);
		return tempPlayer.getBalance();
	}//end getBalance method
	
	/**
	 * Used to get the player's name from the ArrayList
	 * @param playerId position in Players ArrayList
	 * @return String with the player's name
	 */
	public  String getName(int playerId){//getName wrapper for player class
		Player tempPlayer = CasinoPlayers.players.get(playerId);
		return tempPlayer.getName();
	}//end getName method

	/**
	 * isNPC returns true if the player is an AI or false if it is a human
	 * @param playerId int
	 * @return boolean
	 */
	public boolean isNPC(int playerId){
		Player tempPlayer = players.get(playerId);
		return tempPlayer.getType();
	}
	
}//end CasinoPlayers class
