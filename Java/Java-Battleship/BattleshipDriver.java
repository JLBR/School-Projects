

	    /****************************************************************
		** Class: BattleshipDriver.java                                **
		** Author: Jimmy                                               **
		** Date: 18 Nov 2012                                           **
		** Description: The main class for launching the battleship    **
		** game.  This class also reads from .txt ship files to set    **
		** up the main game objects.                                   **
		*****************************************************************/

import java.io.File;
import java.io.FileNotFoundException;
import java.util.Scanner;

/**
 * BattleshipDriver launches the game and reads the ship setup files
 */
public class BattleshipDriver {

	final static byte START_ROW = 0;
	final static byte START_COLUMN = 1;
	final static byte END_ROW = 2;
	final static byte END_COLUMN =3;
	
	/**
	 * The battleship game Main method
	 * @param args in the format of <filename> <filename> <npc playstyle> 
	 */
	public static void main(String[] args) {
		
		if(args.length != 3){
			System.out.println("Required switches missing.\n The correct format is: \n" +
					"<filename> <filename> <npc playstyle>");
			System.out.println("Acceptable parameters for npc playstyle are:\n R - random" +
					"\n S - systematic");
			System.exit(1);
		}
		
		String npcStyleIn = args[2];
		boolean npcStyle = false;
		
		npcStyleIn = npcStyleIn.toLowerCase();

		if(npcStyleIn.charAt(0) == "r".charAt(0)){
			npcStyle=true;
		}else if(npcStyleIn.charAt(0) == "s".charAt(0)){
			;
		}else{
			System.out.println("Acceptable parameters for playstyle are:\n R - random" +
					"\n S - systematic");
			System.exit(1);
		}
		
		BattleshipBoard playersBoard = new BattleshipBoard(10,10);
		BattleshipBoard piratesBoard = new BattleshipBoard(10,10);
		
		try{
			File playerFile = new File(args[1]);
			File oppnentFile = new File(args[0]);

			//File oppnentFile =  new File("./FleetFiles/playerBoard.txt");
			//File playerFile = new File("./FleetFiles/opponentBoard.txt");
			
			if(playerFile.exists()){
				
				int ships[][] = readFile(playerFile);
				
				for(byte i = 0; i<5; i++){
					playersBoard.placeShip(ships[i][START_COLUMN], ships[i][START_ROW], 
							ships[i][END_COLUMN], ships[i][END_ROW]);
				}

			}else {
				throw new FileNotFoundException();
			}
			
			if(oppnentFile.exists()){
				int ships[][] = readFile(oppnentFile);
				
				for(byte i = 0; i<5; i++){
					piratesBoard.placeShip(ships[i][START_COLUMN], ships[i][START_ROW], 
							ships[i][END_COLUMN], ships[i][END_ROW]);
				}
				
			}else {
				throw new FileNotFoundException();
			}
			
		} catch (FileNotFoundException e){
			System.out.println("Chart files not found.  Check the spelling and try again");
			//e.printStackTrace();
			System.exit(1);
		}
		
		GameBoardGUI gameBoard = new GameBoardGUI(playersBoard, piratesBoard, npcStyle);
		gameBoard.setVisible(true);

	}

	/**
	 * Reads the ship .txt setup files
	 * @param boardFile the file object from main
	 * @return an array with the ships start and end coordinates.
	 */
	private static int[][] readFile(File boardFile) {
		
		int ships[][] = new int[5][4];//5 ships with 2 (x,y) coordinates
		int[][] tempShip = new int[5][4];
		
		try{

			if(boardFile.canRead()){

				Scanner fileReader = new Scanner(boardFile);

				int row = 1;
				Boolean[] isVert = new Boolean[5];
				
				while(fileReader.hasNextLine()){
					//System.out.println(row);
					if(row>10){
						fileReader.close();
						throw new CorruptFileException();
					}
					
					String newRow = fileReader.nextLine();
					
					if(newRow.length() != 10){
						fileReader.close();
						throw new CorruptFileException();
					}
					
					for(int column = 1; column<newRow.length()+1;column++){
						//System.out.println(column);
						if(column>10){
							fileReader.close();
							throw new CorruptFileException();
						}
						
						byte tileInfo = (byte) Character.getNumericValue(newRow.charAt(column-1));
						
						if( tileInfo < 5 && tileInfo >-1 ){
							if(ships[tileInfo][START_ROW] == 0){
								ships[tileInfo][START_ROW] = row;
								ships[tileInfo][START_COLUMN] = column;
								ships[tileInfo][END_ROW] = row;
								ships[tileInfo][END_COLUMN] = column;
							}else{
								ships[tileInfo][END_ROW] = row;
								ships[tileInfo][END_COLUMN] = column;
								
								if(isVert[tileInfo] != null){
									if((ships[tileInfo][END_COLUMN]==ships[tileInfo][START_COLUMN]) 
									&& isVert[tileInfo]==false){
										fileReader.close();
										throw new CorruptFileException();
									}else if((ships[tileInfo][END_ROW]==ships[tileInfo][START_ROW]) 
									&& isVert[tileInfo]==true){
										fileReader.close();
										throw new CorruptFileException();
									}
								}else if((ships[tileInfo][END_COLUMN]==ships[tileInfo][START_COLUMN])){
									isVert[tileInfo]=true;
								}else{
									isVert[tileInfo]=false;
								}
							}
						}
					}
					row++;
				}
				fileReader.close();
				if(row <10){
					throw new CorruptFileException();
				}
			}
			
			//tests for a minimum of 5 ships
			for(byte i=0;i<5;i++){
				if(ships[i][START_ROW] == 0){
					throw new CorruptFileException();
				}
			}

			
			for(byte i=0;i<5;i++){
	
				byte size = (byte) ((ships[i][END_ROW]-ships[i][START_ROW])+
						(ships[i][END_COLUMN]-ships[i][START_COLUMN])+1);
				
				switch(size){
				
				case 2:
					for(byte j=0;j<4;j++){
						tempShip[0][j] = ships[i][j];
					}
					break;
				case 3:
					if(tempShip[1][0] == 0){
						for(byte j=0;j<4;j++){
							tempShip[1][j] = ships[i][j];
						}
					}else{
						for(byte j=0;j<4;j++){
							tempShip[2][j] = ships[i][j];
						}
					}
					break;
				case 4:
					for(byte j=0;j<4;j++){
						tempShip[3][j] = ships[i][j];
					}
					break;
				case 5:
					for(byte j=0;j<4;j++){
						tempShip[4][j] = ships[i][j];
					}
					break;
				default:
					throw new CorruptFileException();	
				}
			}

		} catch (CorruptFileException e) {
			System.out.println("The ship placement files are corrupt.");
			System.exit(1);
		} catch (FileNotFoundException e) {
			System.out.println("Chart files not found.  Check the spelling and try again");
			System.exit(1);
			//e.printStackTrace();
		} 
		return tempShip;
	}
}
	
