
	    /****************************************************************
		** Class: BattleshipBoard.java                                 **
		** Author: Jimmy                                               **
		** Date: 02 Nov 2012                                           **
		** Updated: 14 Nov 2012                                        **
		** Description: This class runs the back end of the battleship **
		** game.  It contains 3 subclasses for handling a dynamic      **
		** number of ships and two custom exceptions.                  **
		*****************************************************************/

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;
import javax.swing.ImageIcon;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JLayeredPane;

public class MainMenuGUI extends JFrame{
	
	/**
	 * @param args
	 * @throws IOException 
	 */
	public static void main(String[] args) throws IOException {
		MainMenuGUI bsb = new MainMenuGUI();
		bsb.setVisible(true);
		
	}
	
	private JLayeredPane  mainMenuPanes;
    private java.awt.Button playGameButton;
    private java.awt.Button quitGameButton;
    private java.awt.Button editCharts;
	
	MainMenuGUI(){
		
		setDefaultCloseOperation(DISPOSE_ON_CLOSE);
		
		mainMenuPanes = new JLayeredPane();
        playGameButton = new java.awt.Button();
        quitGameButton = new java.awt.Button();
        editCharts = new java.awt.Button();
        
        playGameButton.setLabel("Play A Round");
        playGameButton.setBounds(80, 120, 160, 24);
        mainMenuPanes.add(playGameButton, JLayeredPane.DEFAULT_LAYER);
        
        quitGameButton.setLabel("Quit");
        quitGameButton.setBounds(80, 210, 160, 24);
        mainMenuPanes.add(quitGameButton, JLayeredPane.DEFAULT_LAYER);
        
        editCharts.setLabel("Update Nautical Charts");
        editCharts.setBounds(80, 170, 160, 24);
        mainMenuPanes.add(editCharts, JLayeredPane.DEFAULT_LAYER);
		
        File imageFile = new File("images/battleship_poster1.jpg");
        BufferedImage image = null;
        
		try {
			image = ImageIO.read(imageFile);
		} catch (IOException e) {
			//TODO fill in error handler
			e.printStackTrace();
		}
        
		//BUG possible bug if the file is missing
        JLabel backGroundImage = new JLabel(new ImageIcon(image));
		backGroundImage.setBounds(0, 0, 320, 440);
		backGroundImage.setVerticalAlignment(JLabel.TOP);
		backGroundImage.setHorizontalAlignment(JLabel.LEFT);
        mainMenuPanes.add(backGroundImage, JLayeredPane.DEFAULT_LAYER);
       
        playGameButton.addActionListener(new PlayGameActionListener());
        quitGameButton.addActionListener(new QuitGameActionListener());
        editCharts.addActionListener(new EditChartsActionListener());

		//this section was generated with NetBeans
        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(mainMenuPanes, javax.swing.GroupLayout.DEFAULT_SIZE, 624, Short.MAX_VALUE)
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(mainMenuPanes, javax.swing.GroupLayout.DEFAULT_SIZE, 435, Short.MAX_VALUE)
        );
		
        pack();
        setSize(320,440);//same as background image
        setResizable(false);
        setLocationRelativeTo(null);
			
	}
		
	private class PlayGameActionListener implements ActionListener{

		@Override
		public void actionPerformed(ActionEvent arg0) {
			// TODO Auto-generated method stub
			System.out.println("got here");
			
		}
	}
	
	private class QuitGameActionListener implements ActionListener{

		@Override
		public void actionPerformed(ActionEvent arg0) {
			// TODO Auto-generated method stub
			System.out.println("got here");
			
		}
	}
	
	private class EditChartsActionListener implements ActionListener{

		@Override
		public void actionPerformed(ActionEvent arg0) {
			// TODO Auto-generated method stub
			System.out.println("got here");
			
		}
	}
	
}

	

