	    /****************************************************************
		** Class: BattleshipBoard.java                                 **
		** Author: Jimmy                                               **
		** Date: 02 Nov 2012                                           **
		** Updated: 14 Nov 2012                                        **
		** Description: This class runs the back end of the battleship **
		** game.  It contains 3 subclasses for handling a dynamic      **
		** number of ships and two custom exceptions.                  **
		** UPDATE: Added get ship method for use in the file reader    **
		*****************************************************************/

/**
 * This class represents a board in the classic game of Battleship.
 */
public class BattleshipBoard {

	private int rows;
	private int cols;
	private Ship ship[];
	private int numShipRemaining;
	
	/**
	 * Constructor for the Battleship Board. It allows for boards of arbitrary
	 * rows and columns to be created
	 * 
	 * @param numRows
	 *            The number of rows
	 * @param numCols
	 *            The number of columns
	 */
	public BattleshipBoard(int numRows, int numCols) {
		rows = numRows;
		cols = numCols;
		ship = null;
		numShipRemaining = 0;
	}
	

	/**
	 * Getter for the number of rows in the board
	 * 
	 * @return The number of rows in the Battleship board
	 */
	public int getNumRows() {
		return rows;
	}

	/**
	 * Getter for the number of columns in the board
	 * 
	 * @return The number of columns in the Battleship board
	 */
	public int getNumCols() {
		return cols;
	}

	/**
	 * Places the battleship at the starting coordinates (startCol,startRow) and the
	 * end coordinates (endCol,endRow) inclusive.  This will not move an existing ship.
	 * 
	 * @precondition Must have already validated that the new ship does not exceed the maximum number of ships
	 * 
	 * @param startCol
	 *            The starting Col coordinate
	 * @param startRow
	 *            The starting Row coordinate
	 * @param endCol
	 *            The ending Col coordinate
	 * @param endRow
	 *            The ending Row coordinate
	 * @throws ShipOverlapException 
	 * @throws Exception
	 *             If ship is placed diagonally
	 *             If the coordinates are out of bounds. A coordinate is in bounds if 0 <= col < (number of columns - 1) and 0 <= row < (number of rows - 1).  
	 *             If it overlaps with another battleship  
	 *             If startCol > endCol or startRow > endRow. 
	 */
	public void placeShip(int startCol, int startRow, int endCol, int endRow) {
		//System.out.println( startCol+" " +startRow +" "+ endCol+" "+ endRow);	
		try {
			if(startCol<=0 || startCol>=cols+1 || startRow<=0 || startRow>=rows+1 || endCol<=0 || 
					endCol>=cols+1 || endRow<=0 || endRow>=rows+1 ){
					throw new InvalidPlacementException();
			}//end if off the board
			if((startCol > endCol || startRow > endRow) || (startRow != endRow && startCol != endCol)){
				throw new InvalidPlacementException();
			}//end if diagonal
			if((startCol > endCol || startRow > endRow)){
				throw new InvalidPlacementException();
			}//end if off the if the end point is larger than the start point
		} catch (InvalidPlacementException e) {
			System.out.println("Ship placement files are corupt.  Ships are diagnal.");
			System.exit(1);
			//e.printStackTrace();
		}
		if(ship == null){
			ship = new Ship[1];//first ship
			ship[0] = new Ship(startCol, startRow, endCol, endRow);
			numShipRemaining++;
		}else{
			
			//check for collision with existing ships
			for(byte i = 0;i<this.ship.length;i++){
				
				int[][] tempShip = this.ship[i].getShipPosition();
				
				
					if(!(tempShip[1][1] < startRow || tempShip[0][1]> endRow) && !(tempShip[1][0]< startCol || 
						tempShip[0][0]> endCol)) {
					try {
						throw new ShipOverlapException();
					} catch (ShipOverlapException e) {
						System.out.println("Ship placement files are corrupt.  Ships overlap.");
						//e.printStackTrace();
						System.exit(1);
					}
				}
			}
			
			Ship tempShip[] = new Ship[ship.length+1];
		
			for(byte i = 0;i<this.ship.length; i++){
				tempShip[i] = this.ship[i];
			}

			ship = tempShip;
			ship[ship.length-1] = new Ship(startCol, startRow, endCol, endRow);
			numShipRemaining++;
		}
	}

	/**
	 * Returns a clone of the ship based on its index 0-4
	 * @param shipIndex
	 * @return ship clone
	 */
	public Ship getShip(byte shipIndex){
		Ship[] tempShip =  this.ship.clone();
		return tempShip[shipIndex];
	}
	
	/**
	 * Fires a shot at a battleship
	 * 
	 * @param col
	 *            The col coordinate of the shot
	 * @param row
	 *            The row coordinate of the shot
	 * @return true if an enemy battleship is hit, false otherwise
	 * @throws Exception
	 *             if col or row are out of bounds
	 */
	public boolean fireShot(int col, int row) throws Exception {
		
		if((col<=0 || col>cols) || (row<=0 || row>rows)){
			throw new InvalidPlacementException();
		}//end if off the board
		
		for(byte i = 0;i<ship.length;i++){
			
			if(ship[i].hitShip(col, row)){
				if(ship[i].isSunk()){
					numShipRemaining--;
				}
				
				return true;//hit
			}
		}
		return false;//miss
	}

	/**
	 * Gets the number of Battleships left
	 * 
	 * @return The number of battleships left
	 */
	public int getNumBattleshipsLeft() {
		return numShipRemaining;
	}//end getNumBattleshipsLeft
	
	/**
	 * Returns true if game is over, false otherwise
	 * @return true if game is over, false otherwise
	 */
	public boolean isGameOver() {
		if(numShipRemaining == 0){
			return true;
		}//end if all sunk Davy Jones wins
		return false;
	}//end isGameOver
	
	/**
	 * SubClass ship used to hold ship placement, health status, and determine if a shot hits
	 * <p>Constructor<ul><li>
	 * Ship(int startCol, int startRow, int endCol, int endRow)
	 * </ul><p>Accessor methods<ul><li>
	 * public boolean hitShip(int shotCol, int shotRow)
	 * <li>public int[][] getShipPosition()
	 * <li>public boolean isSunk()
	 */
	protected class Ship{
		
		private int endCol;
		private int startCol;
		private int startRow;
		private int endRow;
		private int[][] hitLoc;
		private byte health;
		
		/**
		 * Constructor for Ship, calculates "health" on creation
		 * @param startCol int lower value of X
		 * @param startRow int lower value of Y
		 * @param endCol int upper value of X
		 * @param endRow int upper value of Y
		 */
		Ship(int startCol, int startRow, int endCol, int endRow){
			
			// assign X
			this.startCol = startCol;
			this.endCol = endCol;
			
			//end if assign Y
			this.startRow = startRow;
			this.endRow = endRow;
			
			health = (byte) ((this.endCol-this.startCol)+(this.endRow-this.startRow)+1);//equal to the size of the ship		
			hitLoc = new int[health][2];//keeps track of prior hits to prevent double counting of hits
			
			for(byte i = 0;i<(hitLoc.length-health);i++){
				for(byte j = 0;j<(2);j++){
					hitLoc[i][j]=-1;
				}//end inner for
			}//end outer for initialize hit locations to -1 (not on the board)	
		}//end constructor
		
		/**
		 * hitShip returns true if hit the first time decrementing health by one, 
		 * and false if the shot missed or the ship has already been hit at that location
		 * @param shotCol int
		 * @param shotRow int
		 * @return true if hit, false if miss
		 */
		public boolean hitShip(int shotCol, int shotRow){
			
			if((shotRow >= startRow && shotRow <= endRow) && (shotCol>= startCol && shotCol<= endCol)){
				
				boolean hasHit = true;//true if not hit in that loc already
				
				for(byte i = 0;i<(hitLoc.length-health);i++){
					if(hitLoc[i][0] == shotRow && hitLoc[i][1] == shotCol){
						hasHit = false;
						break;
					}//end duplicate hit
				}//end for if duplicate hit
				
				if(hasHit){
					hitLoc[hitLoc.length-health][0] = shotRow;
					hitLoc[hitLoc.length-health][1] = shotCol;
					health--;
					return true;//hit
				}//end if hit decrement health and assign prior shot locations
				
			}//if hit
			return false;//miss
		}//end hitShip
		
		/**
		 * getShipPosition returns a 2x2 array with the ship position<p>
		 * 
		 * shipPosition[0][0] = startCol (X)</br>
		 * shipPosition[0][1] = startRow (Y)</br>
		 * shipPosition[1][0] = endCol (X)</br>
		 * shipPosition[1][1] = endRow (Y)</br>
		 * 
		 * @return 2x2 array
		 */
		public int[][] getShipPosition(){			
			
			int[][] tempPosition = new int[2][2];
			
			tempPosition[0][1] = startRow;
			tempPosition[0][0] = startCol;
			tempPosition[1][1] = endRow;
			tempPosition[1][0] = endCol;
			
			return tempPosition;
		}
		
		/**
		 * isSunk returns true if all parts of the ship have been hit, 
		 * and false if at least one section of the ship is not hit
		 * @return true ship is dead and sunk, false still fighting
		 */
		public boolean isSunk(){
			if(health==0){
				return true; 
			}
			return false;
		}
	}

	/**
	 * InvalidPlacementException thrown when a ship is place out of the board bounds or diagonally.
	 */
	public class InvalidPlacementException extends Exception {
		private static final long serialVersionUID = 1L;
		InvalidPlacementException(){
		}
	}

	/**
	 * ShipOverlapException is thrown when ships overlap on placement
	 */
	public class ShipOverlapException extends Exception {
		private static final long serialVersionUID = 1L;
		ShipOverlapException(){
		}
	}
}
