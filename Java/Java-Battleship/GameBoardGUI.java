	    /****************************************************************
		** Class: GameBoardGUI.java                                    **
		** Author: Jimmy                                               **
		** Date: 18 Nov 2012                                           **
		** Description: This class creates and operates the main GUI   **
		** for the battleship game.  The position of all visual elements*
		** are set by the constants, so do not change the settings     **
		** directly                                                    **
		*****************************************************************/

import java.awt.Color;
import java.awt.GridLayout;
import java.awt.Image;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JLayeredPane;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.border.Border;

/**
 * Creates the two game boards used in battleship, and a fire button. 
 * The fire button takes the last user selected cell and updates that
 * cell with a hit or miss, then recursively runs the AI's turn.  If the
 * game ends before the AI shoots the user will be declared the winner.
 * If the game ends after the AI shoots the user will be declared the
 * loser.
 */
public class GameBoardGUI extends JFrame implements MouseListener{

	private static final long serialVersionUID = 2871618396271409789L;

	private JLabel gameTiles[][] = new JLabel[11][11];//fixed board size of 10x10 with a label row and column
	private JLabel lastSelected;
	private JLayeredPane gamePanes;
	private AIPlayer npc;
	
	private Image imageTarget = null;
	private Image imageWater = null;
	private Image imageHit = null;
	private Image imagePlayer1 = null;
	private Image imagePirate = null;
	private Image imageBackground = null;
	private Image imageRedButton = null;
	private Image imagePatrolBoat = null;
	private Image imageAircraftCarrier = null;
	private Image imageDestroyer = null;
	private Image imageBattleship = null;
	private Image imageSubmarean = null;
	private Image imagePatrolBoatVert = null;
	private Image imageAircraftCarrierVert = null;
	private Image imageDestroyerVert = null;
	private Image imageBattleshipVert = null;
	private Image imageSubmareanVert = null;
	
	final private int GAME_BORD_HEIGHT = 400;
	final private int GAME_BORD_WIDTH = 400;
	final private byte PLAYER_TITLE_HEIGHT = 50;
	final private byte ORGIN_X = 0;
	final private byte ORGIN_Y = 0;
	final private byte TILE_HEIGHT = 36;
	final private byte TILE_WIDTH = 36;
	final private byte TILE_BUFFER = 2;
	final private int BACKGROUND_WIDTH = 1200;
	final private int BACKGROUND_HEIGHT = 700;
	final private byte LEFT_BUFFER = 35;
	final private int NPC_BOARD_ADJUSTMENT = 750;
	final private int BIG_RED_BUTTON_HIGHT = 100;
	final private int BIG_RED_BUTTON_WIDTH = 100;
	final private int CENTER_ADJUST = 540;
	final private int TOP_ADJUST = 200;
	
	//final private byte HIDDEN_LAYER = 0;
	final private byte BACKGROUND_LAYER = 50;
	final private byte SHIP_LAYER = 75;
	final private byte GAME_BORD_LAYER = 100;
	//final private int TOP_LAYER = 150;
	
	final private boolean HUMAN_PLAYER = true;
	final private boolean NPC_PLAYER = false;
	final private boolean SYSTEMATIC = false;
	//final private boolean RANDOM = true;
	
	final private byte PATROL_BOAT = 0;
	final private byte SUBMARINE = 1;
	final private byte DESTROYER = 2;
	final private byte BATTLESHIP =3;
	final private byte AIRCRAFT_CARRIER = 4;

	private BattleshipBoard humanBoard;
	private BattleshipBoard npcBoard;
	
	/**
	 * GameBoardGUI constructor DISPOSE_ON_CLOSE set for future expansion
	 * @param playersBoard Users battleship object
	 * @param piratesBoard AIs battleship object
	 * @param npcStyle Systematic is true, random is false
	 */
	GameBoardGUI(BattleshipBoard playersBoard, BattleshipBoard piratesBoard, boolean npcStyle){
		
		setDefaultCloseOperation(DISPOSE_ON_CLOSE);
		
		loadAssets();
		gameBoardSetup(playersBoard, piratesBoard);
		showShips(piratesBoard, NPC_PLAYER);
		
		if(npcStyle==SYSTEMATIC){
			npc = new SystematicAI();
		}else{
			npc = new RandomAI();
		}

	}

	/**
	 * creates the JLayeredPayne by calling all the JPane constructor helper methods
	 * @param playersBoard
	 * @param piratesBoard
	 */
	private void gameBoardSetup(BattleshipBoard playersBoard, BattleshipBoard piratesBoard){

		humanBoard = playersBoard;
		npcBoard = piratesBoard;
		
		gamePanes = new JLayeredPane();
		
		gamePanes.add(backgroundSetup(), new Integer(BACKGROUND_LAYER));
		gamePanes.add(playerTitleSetup(HUMAN_PLAYER), new Integer(GAME_BORD_LAYER));
		gamePanes.add(playerTitleSetup(NPC_PLAYER), new Integer(GAME_BORD_LAYER));
		gamePanes.add(playerBoardSetup(HUMAN_PLAYER, playersBoard), new Integer(GAME_BORD_LAYER));
		gamePanes.add(playerBoardSetup(NPC_PLAYER, piratesBoard), new Integer(GAME_BORD_LAYER));
		gamePanes.add(fireControlsSetup(), new Integer(GAME_BORD_LAYER));
		gamePanes.add(addShips(piratesBoard, NPC_PLAYER), new Integer(SHIP_LAYER));
		gamePanes.add(addShips(playersBoard, HUMAN_PLAYER), new Integer(SHIP_LAYER));
		
		gamePanes.setOpaque(false);
		
		this.add(gamePanes);
		this.setSize(BACKGROUND_WIDTH, BACKGROUND_HEIGHT);
		this.setResizable(false);
		
	}
	
	/**
	 * Puts an image in the background of a 1200x700 window
	 * @return JPanel
	 */
	private JPanel backgroundSetup(){
		
		JPanel backgroundImage = new JPanel();
		backgroundImage.setLayout(null);
		backgroundImage.setBounds(ORGIN_X, ORGIN_Y, BACKGROUND_WIDTH, BACKGROUND_HEIGHT);
		backgroundImage.setOpaque(false);
		
		JLabel background = new JLabel(new ImageIcon(imageBackground));
		background.setBounds(ORGIN_X, ORGIN_Y, BACKGROUND_WIDTH, BACKGROUND_HEIGHT);
		backgroundImage.add(background);
		
		return backgroundImage;		
	}
	
	/**
	 * Puts the fire button in the center of the window, and calls FireButtonActionListener
	 * @return JPanel
	 */
	private JPanel fireControlsSetup(){
		
		JPanel fireControls = new JPanel(new GridLayout(1,1));
		fireControls.setOpaque(false);
		fireControls.setBackground(Color.BLACK);
		fireControls.setBounds(ORGIN_X+CENTER_ADJUST, ORGIN_Y+TOP_ADJUST, BIG_RED_BUTTON_WIDTH, BIG_RED_BUTTON_HIGHT);
		
		JButton fireButton = new JButton(new ImageIcon(imageRedButton));
		fireButton.addActionListener(new FireButtonActionListener());
		
		fireControls.add(fireButton);
		
		return fireControls;
	}
	
	/**
	 * This method calls the battlshipBoard fireShot method.  First it translates
	 * the coordinates from the targeted cell if triggered by the user.  Then it 
	 * checks to see if the game is over.  If not it calls itself with the NPC_PLAYER
	 * parameter which passes coordinates from the AI then checks for the end of the 
	 * game.
	 * @param playerType
	 */
	private void shotFired(boolean playerType){
		
		BattleshipBoard playersBoard;
		
		if(playerType == HUMAN_PLAYER){
			playersBoard = humanBoard;
		}else{
			playersBoard = npcBoard;
		}
			
		
		if(lastSelected != null&&lastSelected.getName()!="HIT"){
			
			boolean hit = true;
			String targetName = lastSelected.getName();
			char c =  targetName.charAt(0);
							
			targetName = targetName.substring(1);
			
			c -=64;//changes letter column to number;
			int column = c;
			int row = Integer.parseInt(targetName);
			
			try {
				hit = playersBoard.fireShot(column, row);
			} catch (Exception e) {
				//this will never be reached
				e.printStackTrace();
			}

			if(hit){
				lastSelected.setIcon(new ImageIcon(imageHit));
				lastSelected.setName("HIT");
			}else{
				lastSelected.setIcon(new ImageIcon(imageWater));
				lastSelected.setText("MISS");
				lastSelected.setForeground(Color.YELLOW);
			}
		}
		
		if(playersBoard.isGameOver() && playerType == HUMAN_PLAYER){
			JOptionPane.showMessageDialog(this, "YOU WIN!!!!!!",  "GAME OVER", JOptionPane.PLAIN_MESSAGE);
			System.exit(0);
		}else if (playersBoard.isGameOver()){
			showShips(humanBoard, HUMAN_PLAYER);
			JOptionPane.showMessageDialog(this, "YOU LOSE",  "GAME OVER", JOptionPane.PLAIN_MESSAGE);
			System.exit(0);
		}
		
		if(playerType == HUMAN_PLAYER && lastSelected != null){
			
			int[][] npcShot = npc.fireShot();
			JPanel gameBoard = (JPanel) gamePanes.getComponent(3);
			JLabel targetLocation = (JLabel) gameBoard.getComponent((npcShot[0][1]*11)+npcShot[0][0]);
			
			lastSelected = targetLocation;

			shotFired(NPC_PLAYER);
			
			lastSelected = null;
			
		}
	}
	
	/**
	 * Puts the titles on the top of the screen
	 * @param player human or npc
	 * @return
	 */
	private JPanel playerTitleSetup(boolean player){
		
		JPanel playerTitle = new JPanel(new GridLayout(1,1));

		if(player == HUMAN_PLAYER){
			
			playerTitle.setBounds(ORGIN_X+LEFT_BUFFER, ORGIN_Y, GAME_BORD_WIDTH, PLAYER_TITLE_HEIGHT);
			playerTitle.setOpaque(false);
			
			JLabel playerLabel = new JLabel(new ImageIcon(imagePlayer1));
			playerTitle.add(playerLabel);
		
		}else{
			
			playerTitle.setBounds(ORGIN_X+NPC_BOARD_ADJUSTMENT, ORGIN_Y, GAME_BORD_WIDTH, PLAYER_TITLE_HEIGHT);
			playerTitle.setOpaque(false);
			
			JLabel playerLabel = new JLabel(new ImageIcon(imagePirate));
			playerTitle.add(playerLabel);
			
		}
		return playerTitle;
	}
	
	/**
	 * Sets up the game boards.  Only the player's board fires mouse events.
	 * @param player human or npc
	 * @param playersBoard
	 * @return JPanel
	 */
	private JPanel playerBoardSetup(boolean player, BattleshipBoard playersBoard){
		
		JPanel gameBoard = new JPanel(new GridLayout(11, 11));

		if(player == HUMAN_PLAYER){
			gameBoard.setBounds(ORGIN_X+LEFT_BUFFER, PLAYER_TITLE_HEIGHT, GAME_BORD_WIDTH, GAME_BORD_HEIGHT);
			gameBoard.setOpaque(false);
			gameBoard.setBackground(Color.BLACK);
		}else{
			gameBoard.setBounds(ORGIN_X+NPC_BOARD_ADJUSTMENT, PLAYER_TITLE_HEIGHT, 
					GAME_BORD_WIDTH, GAME_BORD_HEIGHT);
			gameBoard.setOpaque(false);
			gameBoard.setBackground(Color.BLACK);
		}
		
		Border border = BorderFactory.createLineBorder(Color.black);

		for(int i = 0; i < 11; i++){
			for(int j = 0; j <11; j++){
				
				if(i == 0){
					gameTiles[i][j] = new JLabel();
					gameTiles[i][j].setText("" + " ABCDEFGHIJKLMNOPQRSTUVWXYZ".charAt(j));
					gameTiles[i][j].setBorder(border);
					gameTiles[i][j].setVerticalAlignment(JLabel.CENTER);
					gameTiles[i][j].setHorizontalAlignment(JLabel.CENTER);
					gameTiles[i][j].setBackground(Color.WHITE);
					gameTiles[i][j].setOpaque(true);
					gameBoard.add(gameTiles[i][j]);
				}else if(j == 0){
					gameTiles[i][j] = new JLabel(i+"");
					gameTiles[i][j].setBorder(border);
					gameTiles[i][j].setVerticalAlignment(JLabel.CENTER);
					gameTiles[i][j].setHorizontalAlignment(JLabel.CENTER);
					gameTiles[i][j].setBackground(Color.WHITE);
					gameTiles[i][j].setOpaque(true);
					gameBoard.add(gameTiles[i][j]);
				}else{
					String labelName = "" + " ABCDEFGHIJKLMNOPQRSTUVWXYZ".charAt(j)+i;
					gameTiles[i][j] = new JLabel(new ImageIcon(imageWater));
					gameTiles[i][j].setName(labelName);
					gameTiles[i][j].setBorder(border);
					gameTiles[i][j].setVerticalAlignment(JLabel.CENTER);
					gameTiles[i][j].setHorizontalAlignment(JLabel.CENTER);
					gameTiles[i][j].setHorizontalTextPosition(JLabel.CENTER);
					if(player == HUMAN_PLAYER){
						gameTiles[i][j].addMouseListener(this);
					}
					gameBoard.add(gameTiles[i][j]);
				}
			}
		}
		return gameBoard;
	}
	
	/**
	 * Reveals the ships under the game board layer.
	 * @param playersBoard
	 * @param playerType human or npc
	 */
	private void showShips(BattleshipBoard playersBoard, boolean playerType){
		
		//2 is player , 3 is pirate
		JPanel gameBoard;
		if(playerType == HUMAN_PLAYER){
			gameBoard = (JPanel) gamePanes.getComponent(2);
		}else{
			gameBoard = (JPanel) gamePanes.getComponent(3);
		}
		
		for(byte i = 0; i<5;i++){
			
			BattleshipBoard.Ship ship = playersBoard.getShip(i);
			int[][] shipLocation = ship.getShipPosition();
			
			if(shipLocation[0][0]==shipLocation[1][0]){//is vertical
				
				for(byte j = 0;j<(shipLocation[1][1] - shipLocation[0][1]+1);j++){
					
					byte column = (byte) shipLocation[0][0];
					byte row =  (byte)(shipLocation[0][1]+j);
					
					JLabel gameTile = (JLabel) gameBoard.getComponent((row*11)+column);
					
					if((gameTile.getText()!="MISS" && gameTile.getName() != "HIT" )){		
						gameTile.setIcon(null);
						gameTile.setOpaque(false);
					}
				}
				
			}else{//is horizontal
				
				for(byte j = 0;j<(shipLocation[1][0] - shipLocation[0][0]+1);j++){
					
					byte column = (byte) (shipLocation[0][0]+j);
					byte row =  (byte)(shipLocation[0][1]);
					
					JLabel gameTile = (JLabel) gameBoard.getComponent((row*11)+column);
					
					if((gameTile.getText()!="MISS" && gameTile.getName() != "HIT" )){		
						gameTile.setIcon(null);
						gameTile.setOpaque(false);
					}
				}
			}
		}
	}
	
	/**
	 * Creates a JPanel with all of the ship icons.
	 * @param playerBoard
	 * @param playerType human or npc
	 * @return JPanel
	 */
	private JPanel addShips(BattleshipBoard playerBoard, boolean playerType){
		
		JPanel shipsPanel = new JPanel();
		shipsPanel.setLayout(null);
		
		if(playerType == HUMAN_PLAYER){
			shipsPanel.setBounds(LEFT_BUFFER+TILE_BUFFER, PLAYER_TITLE_HEIGHT+TILE_BUFFER, 
					GAME_BORD_WIDTH, GAME_BORD_HEIGHT);
		}else{
			shipsPanel.setBounds(ORGIN_X+NPC_BOARD_ADJUSTMENT+TILE_BUFFER, PLAYER_TITLE_HEIGHT+TILE_BUFFER, 
					GAME_BORD_WIDTH, GAME_BORD_HEIGHT);
		}
		
		shipsPanel.setOpaque(false);
		
		for(byte i = 0; i<5;i++){
			
			JLabel shipLabel = new JLabel();
			BattleshipBoard.Ship ship = playerBoard.getShip(i);
			int[][] shipPosition = ship.getShipPosition();
			
			if(shipPosition[0][0]==shipPosition[1][0]){//is vertical
				
				switch(i){
				
				case PATROL_BOAT:
					shipLabel.setIcon(new ImageIcon(imagePatrolBoatVert));
					shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
							TILE_WIDTH, TILE_HEIGHT*2);
					break;
				case SUBMARINE:
					shipLabel.setIcon(new ImageIcon(imageSubmareanVert));
					shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
							TILE_WIDTH, TILE_HEIGHT*3);
					break;
				case DESTROYER:
					shipLabel.setIcon(new ImageIcon(imageDestroyerVert));
					shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
							TILE_WIDTH, TILE_HEIGHT*3);
					break;
				case BATTLESHIP:
					shipLabel.setIcon(new ImageIcon(imageBattleshipVert));
					shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
							TILE_WIDTH, TILE_HEIGHT*4);
					break;
				case AIRCRAFT_CARRIER:
					shipLabel.setIcon(new ImageIcon(imageAircraftCarrierVert));
					shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
							TILE_WIDTH, TILE_HEIGHT*5);
					break;
				}
				
			}else{//is horizontal
				
				switch(i){
					
					case PATROL_BOAT:
						shipLabel.setIcon(new ImageIcon(imagePatrolBoat));
						shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
								TILE_WIDTH*2, TILE_HEIGHT);
						break;
					case SUBMARINE:
						shipLabel.setIcon(new ImageIcon(imageSubmarean));
						shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
								TILE_WIDTH*3, TILE_HEIGHT);
						break;
					case DESTROYER:
						shipLabel.setIcon(new ImageIcon(imageDestroyer));
						shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
								TILE_WIDTH*3, TILE_HEIGHT);
						break;
					case BATTLESHIP:
						shipLabel.setIcon(new ImageIcon(imageBattleship));
						shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
								TILE_WIDTH*4, TILE_HEIGHT);
						break;
					case AIRCRAFT_CARRIER:
						shipLabel.setIcon(new ImageIcon(imageAircraftCarrier));
						shipLabel.setBounds((TILE_WIDTH)*shipPosition[0][0], (TILE_HEIGHT)*shipPosition[0][1],
								TILE_WIDTH*5, TILE_HEIGHT);
						break;
				}		
			}
			shipsPanel.add(shipLabel);
		}
		return shipsPanel;
	}
	
	/**
	 * Loads all the images needed from the /images directory into memory
	 */
	private void loadAssets(){
		
		File imageFileTarget = new File("images/target.png");
		File imageFileWater = new File("images/water_deep.png");
		File imageFileHit = new File("images/Fireball.jpg");
		File imageFilePlayer1 = new File("images/player1.jpg");
		File imageFileBackground = new File("images/BattleshipBackground.jpg");
		File imageFilePirate = new File("images/PirateTitle.jpg");
		File imageFileRedButton = new File("images/redButton.jpg");
		File imageFilePatrolBoat = new File("images/PatrolBoat.jpg");
		File imageFileAircraftCarrier = new File("images/AircraftCarrier.jpg");
		File imageFileDestroyer = new File("images/Destroyer.jpg");
		File imageFileBattleship = new File("images/Battleship.jpg");
		File imageFileSubmarean = new File("images/Submarean.jpg");
		File imageFilePatrolBoatVert = new File("images/PatrolBoatVert.jpg");
		File imageFileAircraftCarrierVert = new File("images/AircraftCarrierVert.jpg");
		File imageFileDestroyerVert = new File("images/DestroyerVert.jpg");
		File imageFileBattleshipVert = new File("images/BattleshipVert.jpg");
		File imageFileSubmareanVert = new File("images/SubmareanVert.jpg");
		
		try {
			
			Image newImage = ImageIO.read(imageFileTarget);
			imageTarget = newImage.getScaledInstance(TILE_WIDTH, TILE_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileWater);
			imageWater = newImage.getScaledInstance(TILE_WIDTH, TILE_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileHit);
			imageHit = newImage.getScaledInstance(TILE_WIDTH, TILE_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFilePlayer1);
			imagePlayer1 = newImage.getScaledInstance((int)(GAME_BORD_WIDTH/1.5), 
					(int)(PLAYER_TITLE_HEIGHT/1.5), Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFilePirate);
			imagePirate = newImage.getScaledInstance((int)(GAME_BORD_WIDTH/1.5), 
					(int)(PLAYER_TITLE_HEIGHT/1.5), Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileBackground);
			imageBackground = newImage.getScaledInstance(BACKGROUND_WIDTH, BACKGROUND_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileRedButton);
			imageRedButton = newImage.getScaledInstance(TILE_WIDTH*3, TILE_HEIGHT*3, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFilePatrolBoat);
			imagePatrolBoat = newImage.getScaledInstance(TILE_WIDTH*2, TILE_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileAircraftCarrier);
			imageAircraftCarrier = newImage.getScaledInstance(TILE_WIDTH*5, TILE_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileDestroyer);
			imageDestroyer = newImage.getScaledInstance(TILE_WIDTH*3, TILE_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileBattleship);
			imageBattleship = newImage.getScaledInstance(TILE_WIDTH*4, TILE_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileSubmarean);
			imageSubmarean = newImage.getScaledInstance(TILE_WIDTH*3, TILE_HEIGHT, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFilePatrolBoatVert);
			imagePatrolBoatVert = newImage.getScaledInstance(TILE_WIDTH, TILE_HEIGHT*2, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileAircraftCarrierVert);
			imageAircraftCarrierVert = newImage.getScaledInstance(TILE_WIDTH, TILE_HEIGHT*5, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileDestroyerVert);
			imageDestroyerVert = newImage.getScaledInstance(TILE_WIDTH, TILE_HEIGHT*3, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileBattleshipVert);
			imageBattleshipVert = newImage.getScaledInstance(TILE_WIDTH, TILE_HEIGHT*4, Image.SCALE_SMOOTH);
			
			newImage = ImageIO.read(imageFileSubmareanVert);
			imageSubmareanVert = newImage.getScaledInstance(TILE_WIDTH, TILE_HEIGHT*3, Image.SCALE_SMOOTH);
			
		} catch (IOException e) {
			
			System.out.println("Image files not found in image directory.");
			System.exit(1);
			//e.printStackTrace();
		}
	}
	
	/**
	 * unused
	 */
	@Override
	public void mouseClicked(MouseEvent arg0) {
		// TODO Auto-generated method stub
	}

	/**
	 * unused
	 */
	@Override
	public void mouseEntered(MouseEvent arg0) {
		// TODO Auto-generated method stub
	}

	/**
	 * unused
	 */
	@Override
	public void mouseExited(MouseEvent arg0) {
		// TODO Auto-generated method stub
	}

	/**
	 * Places a targeting reticle on the tile the user clicks on, unless
	 * it has already been fired upon
	 */
	@Override
	public void mousePressed(MouseEvent arg0) {
		
		JLabel gameTile = (JLabel) arg0.getSource();
		
			if((lastSelected != null) && (lastSelected.getText()!="MISS" && lastSelected.getName() != "HIT" )){		
				lastSelected.setIcon(new ImageIcon(imageWater));
			}
		
		if(!(gameTile.getText()=="MISS"||gameTile.getName() == "HIT" )){
			
			gameTile.setIcon(new ImageIcon(imageTarget));
			lastSelected = gameTile;
			
		//prevents placing a target image on the last shot location
		}else if(lastSelected != null){
			if(lastSelected.getText()!="MISS" && lastSelected.getName() != "HIT" ){
				lastSelected.setIcon(new ImageIcon(imageTarget));
			}
		}
	}

	/**
	 * unused
	 */
	@Override
	public void mouseReleased(MouseEvent arg0) {
		// TODO Auto-generated method stub
	}
	
	/**
	 * Calls the shotFired method when clicked
	 */
	class FireButtonActionListener implements ActionListener{

		@Override
		public void actionPerformed(ActionEvent arg0) {
			
			//JButton fireButton = (JButton) arg0.getSource();
			//fireButton.setEnabled(false);
			shotFired(HUMAN_PLAYER);
			//fireButton.setEnabled(true);
		}
	}
}
