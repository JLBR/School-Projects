	    /****************************************************************
		** Class: Round.java                                           **
		** Author: Jimmy                                               **
		** Date: 29 Oct 2012                                           **
		** Description: This class does the work of processing and     **
		** displaying the results                                      **
		*****************************************************************/



/**
 * Class Round contains an array of players and a course object storing par and player score information
 * <p>Constructor<ul><li>
 * Round(byte score, byte par)</ul>
 * <p>Accessor methods<ul><li>
 * 	public void setScore(byte hole, byte player, byte score)
 * <li>	public void setPar(byte hole, byte par)
 * <li>	public byte getNumberOfPlayers()
 * <li>	public byte getNumberOfHoles()
 * <li>	public void showRound()
 * 	<li>public byte getPar(byte hole)
 * <li> public byte getScore(byte player, byte hole)
 */
public class Round {
	
	private Player[] player; 
	private Course course;
	
	/**
	 * Round constructor
	 * @param score first player score on hole 1
	 * @param par par for hole 1 set to 0 if unknown and set it with setPar
	 */
	Round(byte score){
		
		player = new Player[1];//set to 1 because it is the first read
		course = new Course((byte) 0);
		
		player[0] = new Player(score);//set to 0 because there is only one player so far
		
	}//end constructor
	
	/**
	 * Prints the output of the round
	 */
	public void showRound(){
		

		byte highScore = 99;
		int roundPar = course.getPar((byte) 0, true);
		byte scores[] = new byte[player.length];
		
		for(byte i = 0; i<(player.length);i++){

			scores[i] = (byte) (player[i].getScore((byte) i, true) - roundPar);

			if(highScore>scores[i]){
				highScore = scores[i];
			}//end if high score
		}//end for loop get high score

		for(byte i = 0; i<player.length;i++){
			
			String winner = "";
			String underOver = "";
			
			if(scores[i]>0){
				underOver = scores[i] + " Over ";
			} else if(scores[i]<0){
				underOver = (-1*scores[i]) + " Under ";
			} //end under over
			
			if(scores[i] == highScore){
				winner = "WINNER";
			}//end winner
			
			System.out.println("Player " + (i+1) + " shot " + underOver + "par " + winner);
			
		}//end for loop get high score
		
		System.out.println("\n");
		
	}//end showRound
	
	/**
	 * sets the player score for a hole in the player object
	 * @param hole
	 * @param player
	 * @param score
	 */
	public void setScore(byte hole, byte player, byte score){
		
		
		if(this.player.length<player+1){

			Player tempPlayer[] = new Player[player+1];
			
			for(byte i = 0;i<this.player.length; i++){
				
				tempPlayer[i] = this.player[i];
				
			}//end assign new player values
			
			tempPlayer[player] = new Player(score);
			this.player = tempPlayer;
			
		}else {
					this.player[player].setScore(hole, score);
		}//end if add player		
	}//end setScore
	
	/**
	 * Sets par for a hole
	 * @param hole
	 * @param par
	 */
	public void setPar(byte hole, byte par){
		course.setPar(hole, par);
	}//end setPar
	
	/**
	 * getNumberOfPlayers returns the number of players
	 * @return byte number of players
	 */
	public byte getNumberOfPlayers(){
		return (byte) this.player.length;
	}//end getNumberOfPlayers
	
	/**
	 * getNumberOfHoles returns the number of holes from the Course object
	 * @return byte number of holes
	 */
	public byte getNumberOfHoles(){
		return course.getNumberOfHoles();
	}//end getNumberOfHoles
	
	/**
	 * getScore returns an individual score
	 * @param player
	 * @param hole
	 * @return byte for individual player and hole
	 */
	public byte getScore(byte player, byte hole){
		return (byte) this.player[player].getScore(hole, false);
	}//end get score
	
	/**
	 * getPar returns par for the hole
	 * @param hole
	 * @return byte of par
	 */
	public byte getPar(byte hole){
		return (byte) course.getPar(hole, false);
	}//end getPar
	
}//end class round
