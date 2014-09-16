	    /****************************************************************
		** Class: RandomAI.java                                        **
		** Author: Jimmy                                               **
		** Date: 18 Nov 2012                                           **
		** Description: Sub class to AIPlayer.  Randomly selects a point*
		** to shoot.  Needs logic for when it successfully hits to     **
		** target nearby tiles                                         **
		*****************************************************************/

/**
 * AIPlayer subclass
 */
public class RandomAI extends AIPlayer {

	/**
	 * Generates a random coordinate to shoot at.  It keeps choosing random
	 * coordinates until it finds one that it has not hit.
	 * @return returns a coordinate in the form int[1][2]
	 */
	@Override
	public int[][] fireShot() {

		boolean hasShot = true;
		int row=0;
		int column=0;
		
		while(hasShot){
			
			row = (int) (Math.random()*10+1);
			column = (int) (Math.random()*10+1);
			
			hasShot = hasShot(row, column);
			
		}

		addShotFired(column, row);
		
		int[][] returnValue = new int[1][2];
		
		returnValue[0][0] = column;
		returnValue[0][1] = row;
		
		
	return returnValue;
	}
}

	
	

