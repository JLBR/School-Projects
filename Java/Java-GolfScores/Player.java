	    /****************************************************************
		** Class: Player.java                                          **
		** Author: Jimmy                                               **
		** Date: 29 Oct 2012                                           **
		** Description: This class contains a players score by hole    **
		*****************************************************************/

/**
 * Player class provides access to an individual player's score stored by hole
 * <p>Constructor:<ul><li>
 * Player(byte score)</ul>
 * <p>Accessor Methods<ul><li>
 * public void setScore(byte newHole, byte score)
 * <li>public int getScore(byte hole, Boolean course)
 */
public class Player {

	private byte hole[];
	
	/**
	 * Creates a player object with one score on hole 1
	 * @param score
	 */
	Player(byte score){
		hole = new byte[1];
		hole[0] = score;
	}//constructor
	
	/**
	 * Sets the player score
	 * @param newHole
	 * @param score
	 */
	public void setScore(byte newHole, byte score){

		if( (newHole)> (byte) hole.length){

			byte holeTemp[] = new byte[newHole];//temp array
			
			for(byte i = 0; i<hole.length;i++){
				holeTemp[i] = hole[i];
			}//end for update array

			holeTemp[newHole-1]= score;
			hole = holeTemp;
			
		}else {
			hole[newHole-1]=score;
		}//end if too short
	}//end setScore
	
	/**
	 * returns an int with the player score
	 * @param hole
	 * @param course true returns the sum
	 * @return player score
	 */
	public int getScore(byte hole, Boolean course){
		
		if(course){

			int score=0;
			
			for(byte i = 0; i<  this.hole.length;i++){
			score = score + this.hole[i];
			}//end for
			return score;
		
		}else{

			return this.hole[hole];
			
		}//end if
	}//end getScore
}
