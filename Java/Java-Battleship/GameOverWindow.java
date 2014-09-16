import java.awt.GridLayout;

import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;


public class GameOverWindow extends JFrame {

	private static final long serialVersionUID = -17372546317594127L;

	GameOverWindow(boolean win){
		
		setDefaultCloseOperation(DISPOSE_ON_CLOSE);
		this.add(setupGameOver(win));
		
	}
	
	private JPanel setupGameOver(boolean win){
		
		JPanel gameOver = new JPanel(new GridLayout(1,1));
		
		JLabel endMessage = new JLabel();
		
		
		
		return gameOver;
	}
	
}
