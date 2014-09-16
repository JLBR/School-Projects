	    /****************************************************************
		** Class: BattleshipBoardTest.java                             **
		** Author: Jimmy                                               **
		** Date: 10 Nov 2012                                           **
		** Description: This is the JUnit test file for BattleshipBoard**
		** It tests the constructor, placeShip, and fireShot methods.  **
		*****************************************************************/

import static org.junit.Assert.*;

import org.junit.Test;

/**
 * JUnit test class for BattleshipBoard
 */
public class BattleshipBoardTest {

	/**
	 * Empty constructor
	 */
	public BattleshipBoardTest() {
	}

	/**
	 * tests the constructor sets the column correctly
	 * @throws BattleshipException
	 */
	@Test
	public void testBattleshipBoardCol() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(19,20);
		assertTrue("Column = 20", BSB.getNumCols()==20);
	}

	/**
	 * tests the constructor sets the row correctly
	 * @throws BattleshipException
	 */
	@Test
	public void testBattleshipBoardRow() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		assertTrue("Row = 20", BSB.getNumRows()==20);	
	}
	
	/**
	 * tests the constructor throws an exception when trying to assign an incorrect column
	 * @throws BattleshipException
	 */
	@Test(expected=BattleshipException.class)
	public void testExceptionBattleshipBoardCol() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(19,-1);
	}

	/**
	 * tests the constructor throws an exception when trying to assign an incorrect Row
	 * @throws BattleshipException
	 */
	@Test(expected=BattleshipException.class)
	public void testExceptionBattleshipBoardRow() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(-1,19);
	}
	
	/**
	 * tests the constructor sets number of ships left to 0
	 * @throws BattleshipException
	 */
	@Test
	public void testBattleshipBoardShipsLeft() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		assertTrue("ShipsLeft = 0", BSB.getNumBattleshipsLeft()==0);	
	}
	
	/**
	 * tests placing a single ship normally.  Failure is throwing an exception
	 * @throws BattleshipException 
	 */
	@Test
	public void testPlaceShip() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(0,0, 0, 0);
	}

	/**
	 * tests placing a single ship on a spot less than zero starting column
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test(expected=IndexOutOfBoundsException.class)
	public void testPlaceShipShortCol() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(-1,0, 0, 0);
	}
	
	/**
	 * tests placing a single ship on a spot less than zero starting row
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test(expected=IndexOutOfBoundsException.class)
	public void testPlaceShipShortRow() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(0,-1, 0, 0);
	}
	
	/**
	 * tests placing a single ship with a smaller ending point
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test(expected=BattleshipException.class)
	public void testPlaceShipBackwardsCol() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(1,0, 0, 0);
	}
	
	/**
	 * tests placing a single ship out of the long bounds end column
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test(expected=IndexOutOfBoundsException.class)
	public void testPlaceShipLongCol() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(0,0, 20, 0);
	}
	
	/**
	 * tests placing a single ship out of the long bounds end row
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test(expected=IndexOutOfBoundsException.class)
	public void testPlaceShipLongRow() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(0,0, 0, 20);
	}
	
	/**
	 * tests placing a single ship on another ship
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test(expected=BattleshipException.class)
	public void testPlaceShipCollision() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(0,0, 0, 0);
		BSB.placeShip(0,0, 0, 0);
	}
	
	/**
	 * tests Sinking two ships by hitting one ship
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test
	public void testPlaceShipSunk() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(1,0, 1, 0);
		BSB.placeShip(0,0, 0, 0);
		assertTrue("number of ships is 2", BSB.getNumBattleshipsLeft()==2);
		BSB.fireShot(0, 0);
		BSB.fireShot(0, 0);
		assertTrue("number of ships is 1", BSB.getNumBattleshipsLeft()==1);
	}
	
	/**
	 * tests placing a single ship diagonally
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test(expected=BattleshipException.class)
	public void testPlaceShipDiagnal() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(0,0, 1, 1);
	}
	
	/**
	 * tests placing a single ship with a smaller ending point
	 * @throws BattleshipException
	 * @throws IndexOutOfBoundsException
	 */
	@Test(expected=BattleshipException.class)
	public void testPlaceShipBackwardsRow() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(0,1, 0, 0);
	}
	
	/**
	 * test missing with no ships on the board
	 * @throws IndexOutOfBoundsException
	 * @throws BattleshipException 
	 */
	@Test
	public void testFireShot() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		assertTrue("false is a miss",!BSB.fireShot(0, 0));
	}

	/**
	 * test missing with ships on the board this also tests place ship
	 * @throws IndexOutOfBoundsException
	 * @throws BattleshipException 
	 */
	@Test
	public void testFireShotWithShips() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(1, 1, 1, 1);
		assertTrue("false is a miss",!BSB.fireShot(0, 0));
	}
	
	/**
	 * test hitting with ships on the board this also tests place ship
	 * @throws IndexOutOfBoundsException
	 * @throws BattleshipException
	 */
	@Test
	public void testFireShotHit() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(1, 1, 1, 1);
		assertTrue("true is a hit",BSB.fireShot(1, 1));
	}
	
	/**
	 * test sinking a ship this also tests place ship
	 * @throws IndexOutOfBoundsException
	 * @throws BattleshipException
	 */
	@Test
	public void testFireShotHitSunk() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.placeShip(1, 1, 1, 1);
		assertTrue("number of battleships is 1", BSB.getNumBattleshipsLeft()==1);
		assertTrue("true is a hit",BSB.fireShot(1, 1));
		assertTrue("number of battleships is 0", BSB.getNumBattleshipsLeft()==0);
	}
	
	/**
	 * test index out of bounds when less than zero
	 * @throws IndexOutOfBoundsException
	 * @throws BattleshipException
	 */
	@Test(expected=IndexOutOfBoundsException.class)
	public void testFireShotExceptionShortCol() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.fireShot(-1, 1);
	}
	
	/**
	 * test index out of bounds when less than zero
	 * @throws IndexOutOfBoundsException
	 * @throws BattleshipException
	 */
	@Test(expected=IndexOutOfBoundsException.class)
	public void testFireShotExceptionShortRow() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.fireShot(1, -1);
	}
	
	/**
	 * test index out of bounds when greater than zero
	 * @throws IndexOutOfBoundsException and BattleshipException
	 */
	@Test(expected=IndexOutOfBoundsException.class)
	public void testFireShotExceptionLongRow() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.fireShot(1, 20);
	}
	
	/**
	 * test index out of bounds when greater than zero
	 * @throws IndexOutOfBoundsException
	 * @throws BattleshipException
	 */
	@Test(expected=IndexOutOfBoundsException.class)
	public void testFireShotExceptionLongCol() throws BattleshipException {
		BattleshipBoard BSB = new BattleshipBoard(20,19);
		BSB.fireShot(21, 1);
	}
}
