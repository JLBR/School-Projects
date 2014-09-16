	    /****************************************************************
		** Class: SystematicAI.java                                    **
		** Author: Jimmy                                               **
		** Date: 18 Nov 2012                                           **
		** Description: Sub class to AIPlayer.  Starts shooting from   **
		** the top left (A1) and continues until it has filled the     **
		** board                                                       **
		*****************************************************************/

/**
 * Sub class of AIPlayer.  
 */
public class SystematicAI extends AIPlayer {
	
	/**
	 * Shoots starting from the top left, and adds one to each shot.
	 * @return returns the coordinate shot at
	 */
	@Override
	public int[][] fireShot() {

		int row;
		int column;
		
		if(shotsFired == null){
			column = 1;
			row = 1;
		}else{
			
			row = shotsFired[shotsFired.length-1][1];
			column = shotsFired[shotsFired.length-1][0];
			
			if(column == 10){
				column = 1;
				row += 1;
			}else{
				column += 1;
			}
			
		}
		
		addShotFired(column, row);
		
		int[][] returnValue = new int[1][2];
		
		returnValue[0][0] = column;
		returnValue[0][1] = row;
		
		
	return returnValue;
	}
}
