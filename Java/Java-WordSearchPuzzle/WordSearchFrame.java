	    /****************************************************************
		** Class: WordSearchFrame.java                                 **
		** Author: Jimmy                                               **
		** Date: 25 Nov 2012                                           **
		** Description: This class finds words in a word search puzzle **
		** The search will stop on the first word found                **
		*****************************************************************/
import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.StringTokenizer;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.SwingConstants;
import javax.swing.border.EtchedBorder;
import javax.swing.border.TitledBorder;

/**
 * This class represents a word search puzzle frame. It displays the word search
 * along with the list of words to find in the word search.
 * 
 * @author wong
 * 
 */
public class WordSearchFrame extends JFrame {

	private final byte FORWARD = 0;
	private final byte BACKWARDS = 1;
	private final byte UP = 2;
	private final byte DOWN = 3;
	private final byte DIAG_UP = 4;
	private final byte DIAG_DOWN = 5;
	private final byte DIAG_UP_BACK = 6;
	private final byte DIAG_DOWN_BACK = 7;
	
	// Leave the line below in your code. You won't need to know what it is for
	// this course.
	private static final long serialVersionUID = 1L;

	// The number of columns in the word list
	private static final int COLS_IN_WORDLIST = 1;

	// The width of the frame
	private static final int FRAME_WIDTH = 640;

	// The height of the frame
	private static final int FRAME_HEIGHT = 480;

	private int lastWord=-1;
	
	// The grid used in the word search
	private JLabel[][] wordSearch;

	// The labels used to display the words in the word list
	private JLabel[] wordListLabels;

	// The panel that the word list is inside
	private JPanel wordListPanel;

	// The panel that the word search is inside
	private JPanel wordsearchPanel;

	// The panel on the right hand side
	private JPanel rightSidePanel;

	// The panel on the left hand side
	private JPanel leftSidePanel;

	// The panel that the search button is inside
	private JPanel searchButtonPanel;

	// The search button
	private JButton searchButton;

	// The number of rows in the word search
	private int numRows;

	// The number of columns in the word search
	private int numCols;

	// The listener for the search button
	private ActionListener searchButtonListener;

	// The list of words to be found in the word search
	private ArrayList<String> wordList;

	private int row;
	private int col;
	private byte direction;
	private boolean hasFoundFalse=false;
	
	/**
	 * The listener for the search button
	 */
	class SearchListener implements ActionListener {
		/**
		 * This event handler causes the current word in the word list to be
		 * colored in red and finds the word in the word search
		 * 
		 * @param event
		 *            The event corresponding to the search button being clicked
		 */
		public void actionPerformed(ActionEvent event) {
			// To be filled in by the student
			if(lastWord == -1){
				lastWord = 0;
				wordListLabels[lastWord].setForeground(Color.RED);
				findWord();
			}else if(lastWord == wordListLabels.length-1){
				clearWord();
				wordListLabels[lastWord].setForeground(Color.BLACK);
				lastWord =0;
				wordListLabels[lastWord].setForeground(Color.RED);
				findWord();
			}else {
				clearWord();
				wordListLabels[lastWord].setForeground(Color.BLACK);
				lastWord +=1;
				wordListLabels[lastWord].setForeground(Color.RED);
				findWord();
			}
		}
		
		/**
		 * helper for actionPerformed
		 * this method searches for the first letter in the word, then tries each
		 * direction to see if the word is present.  It stops after finding the word
		 */
		private void findWord(){
			
			Character firstLetter = wordListLabels[lastWord].getText().charAt(0);
			boolean hasFound = false;
			row = 0;
			col = 0;
			hasFoundFalse=false;
			
			while(!hasFound){

				Character currentChar = wordSearch[col][row].getText().charAt(0);
				
				if(firstLetter.equals(currentChar)){
					if(isForward(wordListLabels[lastWord].getText(), row, col, 0)){
						hasFound=true;
					}else if(isUp(wordListLabels[lastWord].getText(), row, col, 0)){
						hasFound=true;
					}else if(isDown(wordListLabels[lastWord].getText(), row, col, 0)){
						hasFound=true;
					}else if(isBackwards(wordListLabels[lastWord].getText(), row, col, 0)){
						hasFound=true;
					}else if(isDiagnalDown(wordListLabels[lastWord].getText(), row, col, 0)){
						hasFound=true;
					}else if(isDiagnalUp(wordListLabels[lastWord].getText(), row, col, 0)){
						hasFound=true;
					}else if(isDiagnalUpBack(wordListLabels[lastWord].getText(), row, col, 0)){
						hasFound=true;
					}else if(isDiagnalDownBack(wordListLabels[lastWord].getText(), row, col, 0)){
						hasFound=true;
					}
				}
				
				col++;
				
				if(col==numCols){
					col = 0;
					row++;
				}
				
				//ends if not found
				if(row==numRows){
					wordListLabels[lastWord].setForeground(Color.GREEN);
					hasFoundFalse=true;
					hasFound = true;
				}
			}	
		}
		
		/**
		 * Helper method searches in one direction for a word based on the index
		 * @param word word to find
		 * @param row index of the puzzle
		 * @param col index of the puzzle
		 * @param position which letter in the word (0 is the first letter)
		 * @return true if found
		 */
		private boolean isForward(String word, int row, int col, int position){
			
			boolean returnValue = false;
			
			if(position == word.length()-1){
				returnValue = true;
			}else{
				
				if(col!=numCols){
					
					Character testValue = word.charAt(position);
					Character cellValue = (char)wordSearch[col][row].getText().charAt(0);
					
					if(testValue.equals(cellValue)){
						returnValue = isForward(word, row, col+1, position+1);
					}
					
					if(returnValue){
						direction = FORWARD;
						wordSearch[col][row].setForeground(Color.RED);
					}
				}
			}
			return returnValue;
		}
		
		/**
		 * Helper method searches in one direction for a word based on the index
		 * @param word word to find
		 * @param row index of the puzzle
		 * @param col index of the puzzle
		 * @param position which letter in the word (0 is the first letter)
		 * @return true if found
		 */
		private boolean isBackwards(String word, int row, int col, int position){
			
			boolean returnValue = false;
			
			if(position == word.length()){
				returnValue = true;
			}else{
				
				if(col!=-1){
					
					Character testValue = word.charAt(position);
					Character cellValue = (char)wordSearch[col][row].getText().charAt(0);
					
					if(testValue.equals(cellValue)){
						returnValue = isBackwards(word, row, col-1, position+1);
					}
					
					if(returnValue){
						direction = BACKWARDS;
						wordSearch[col][row].setForeground(Color.RED);
					}
				}
			}
			return returnValue;
		}
		
		/**
		 * Helper method searches in one direction for a word based on the index
		 * @param word word to find
		 * @param row index of the puzzle
		 * @param col index of the puzzle
		 * @param position which letter in the word (0 is the first letter)
		 * @return true if found
		 */
		private boolean isUp(String word, int row, int col, int position){
			
			boolean returnValue = false;
			
			if(position == word.length()){
				returnValue = true;
			}else{
				
				if(row != -1){
					
					Character testValue = word.charAt(position);
					Character cellValue = (char)wordSearch[col][row].getText().charAt(0);
					
					if(testValue.equals(cellValue)){
						returnValue = isUp(word, row-1, col, position+1);
					}
					
					if(returnValue){
						direction = UP;
						wordSearch[col][row].setForeground(Color.RED);
					}
				}
			}
			return returnValue;
		}
		
		/**
		 * Helper method searches in one direction for a word based on the index
		 * @param word word to find
		 * @param row index of the puzzle
		 * @param col index of the puzzle
		 * @param position which letter in the word (0 is the first letter)
		 * @return true if found
		 */
		private boolean isDown(String word, int row, int col, int position){
			
			boolean returnValue = false;
			
			if(position == word.length()){
				returnValue = true;
			}else{
				
				if(row!=numRows){
				
					Character testValue = word.charAt(position);
					Character cellValue = (char)wordSearch[col][row].getText().charAt(0);

					if(testValue.equals(cellValue)){
						returnValue = isDown(word, row+1, col, position+1);
					}
					
					if(returnValue){
						direction = DOWN;
						wordSearch[col][row].setForeground(Color.RED);
					}
				}
			}
			return returnValue;
		}
		
		/**
		 * Helper method searches in one direction for a word based on the index
		 * @param word word to find
		 * @param row index of the puzzle
		 * @param col index of the puzzle
		 * @param position which letter in the word (0 is the first letter)
		 * @return true if found
		 */
		private boolean isDiagnalDown(String word, int row, int col, int position){
			
			boolean returnValue = false;
			
			if(position == word.length()){
				returnValue = true;
			}else{
				
				if(row!=numRows && col!=numCols){
					
					Character testValue = word.charAt(position);
					Character cellValue = (char)wordSearch[col][row].getText().charAt(0);
					
					if(testValue.equals(cellValue)){
						returnValue = isDiagnalDown(word, row+1, col+1, position+1);
					}
					
					if(returnValue){
						direction = DIAG_DOWN;
						wordSearch[col][row].setForeground(Color.RED);
					}
				}
			}
			return returnValue;
		}
		
		/**
		 * Helper method searches in one direction for a word based on the index
		 * @param word word to find
		 * @param row index of the puzzle
		 * @param col index of the puzzle
		 * @param position which letter in the word (0 is the first letter)
		 * @return true if found
		 */
		private boolean isDiagnalUp(String word, int row, int col, int position){
			
			boolean returnValue = false;
			
			if(position == word.length()){
				returnValue = true;
			}else{
				
				if(row!=-1 && col!=numCols){
					
					Character testValue = word.charAt(position);
					Character cellValue = (char)wordSearch[col][row].getText().charAt(0);
					
					if(testValue.equals(cellValue)){
						returnValue = isDiagnalUp(word, row-1, col+1, position+1);
					}
					
					if(returnValue){
						direction = DIAG_UP;
						wordSearch[col][row].setForeground(Color.RED);
					}
				}
			}
			return returnValue;
		}
		
		/**
		 * Helper method searches in one direction for a word based on the index
		 * @param word word to find
		 * @param row index of the puzzle
		 * @param col index of the puzzle
		 * @param position which letter in the word (0 is the first letter)
		 * @return true if found
		 */
		private boolean isDiagnalUpBack(String word, int row, int col, int position){
			
			boolean returnValue = false;
			
			if(position == word.length()){
				returnValue = true;
			}else{
				
				if(row!=-1 && col!=-1){
					
					Character testValue = word.charAt(position);
					Character cellValue = (char)wordSearch[col][row].getText().charAt(0);
					
					if(testValue.equals(cellValue)){
						returnValue = isDiagnalUpBack(word, row-1, col-1, position+1);
					}
					
					if(returnValue){
						direction = DIAG_UP_BACK;
						wordSearch[col][row].setForeground(Color.RED);
					}
				}
			}
			return returnValue;
		}
		
		/**
		 * Helper method searches in one direction for a word based on the index
		 * @param word word to find
		 * @param row index of the puzzle
		 * @param col index of the puzzle
		 * @param position which letter in the word (0 is the first letter)
		 * @return true if found
		 */
		private boolean isDiagnalDownBack(String word, int row, int col, int position){
			
			boolean returnValue = false;
			
			if(position == word.length()){
				returnValue = true;
			}else{
				
				if(row!=numRows && col!=-1){
					
					Character testValue = word.charAt(position);
					Character cellValue = (char)wordSearch[col][row].getText().charAt(0);
					
					if(testValue.equals(cellValue)){
						returnValue = isDiagnalDownBack(word, row+1, col-1, position+1);
					}
					
					if(returnValue){
						direction = DIAG_DOWN_BACK;
						wordSearch[col][row].setForeground(Color.RED);
					}
				}
			}
			return returnValue;
		}
	}

	/**
	 * resets the last word found to black
	 */
	private void clearWord(){

		int wordLength = wordListLabels[lastWord].getText().length();
		int col= this.col-1;
		int row = this.row;
		
		if(hasFoundFalse){
			return;
		}
		
		switch(direction){
			case FORWARD:
				for(int i = 0;i<wordLength;i++){
					wordSearch[col][row].setForeground(Color.BLACK);
					col++;
				}
				break;
			case BACKWARDS:
				for(int i = 0;i<wordLength;i++){
					wordSearch[col][row].setForeground(Color.BLACK);
					col--;
				}
				break;
			case UP:
				for(int i = 0;i<wordLength;i++){
					wordSearch[col][row].setForeground(Color.BLACK);
					row--;
				}
				break;
			case DOWN:
				for(int i = 0;i<wordLength;i++){
					wordSearch[col][row].setForeground(Color.BLACK);
					row++;
				}
				break;
			case DIAG_UP:
				for(int i = 0;i<wordLength;i++){
					wordSearch[col][row].setForeground(Color.BLACK);
					col++;
					row--;
				}
				break;
			case DIAG_DOWN:
				for(int i = 0;i<wordLength;i++){
					wordSearch[col][row].setForeground(Color.BLACK);
					col++;
					row++;
				}
				break;
			case DIAG_UP_BACK:
				for(int i = 0;i<wordLength;i++){
					wordSearch[col][row].setForeground(Color.BLACK);
					row--;
					col--;
				}
				break;
			case DIAG_DOWN_BACK:
				for(int i = 0;i<wordLength;i++){
					wordSearch[col][row].setForeground(Color.BLACK);
					row++;
					col--;
				}
				break;
		}
	}
	
	
	/**
	 * Builds the panel on the left hand side, which consists of the word search
	 * 
	 * @throws WordSearchException
	 *             Throws a WordSearchException if the word search file isn't in
	 *             the proper format
	 * @throws IOException
	 *             Throws an IOException if loading the word search file has
	 *             problems
	 */
	private void buildLeftSidePanel() throws WordSearchException, IOException {
		// Create leftSidePanel
		leftSidePanel = new JPanel();
		leftSidePanel.setLayout(new BorderLayout());
		leftSidePanel.setBorder(new TitledBorder(new EtchedBorder(),
				"Word Search"));

		// Build wordsearch panel
		wordsearchPanel = new JPanel();
		wordsearchPanel.setLayout(new GridLayout(numRows, numCols));
		leftSidePanel.add(wordsearchPanel, BorderLayout.CENTER);

		// Add leftSidePanel panel to main frame
		this.getContentPane().add(leftSidePanel, BorderLayout.CENTER);
	}

	/**
	 * Determines the number of columns and rows in the word search file.
	 * 
	 * @param wordSearchFilename
	 *            The file name of the word search file
	 * @throws WordSearchException
	 *             Throws a WordSearchException if the word search file isn't in
	 *             the proper format
	 * @throws IOException
	 *             Throws an IOException if loading the word search file has
	 *             problems
	 */
	private void initGridFromFile(String wordSearchFilename)
			throws WordSearchException, IOException {
		this.numRows = 0;
		this.numCols = 0;
		BufferedReader reader = new BufferedReader(new FileReader(
				wordSearchFilename));
		String line = reader.readLine();
		while (line != null) {
			StringTokenizer tokenizer = new StringTokenizer(line, " ");
			if (this.numCols == 0) {
				this.numCols = tokenizer.countTokens();
			} else {
				if (tokenizer.countTokens() != this.numCols) {
					throw new WordSearchException(
							"Invalid number of columns in word search");
				}
			}
			line = reader.readLine();
			this.numRows++;
		}
		reader.close();
		this.wordSearch = new JLabel[numRows][numCols];
	}

	/**
	 * Loads the contents of the word search into the JLabels of the word search
	 * grid in the GUI
	 * 
	 * @param wordSearchfilename
	 *            The name of the word search file
	 * @throws IOException
	 *             Throws an IOException if loading the word search file has
	 *             problems
	 */
	protected void loadGridFromFile(String wordSearchFilename)
			throws IOException {
		int row = 0;
		BufferedReader reader = new BufferedReader(new FileReader(
				wordSearchFilename));
		String line = reader.readLine();
		while (line != null) {
			StringTokenizer tokenizer = new StringTokenizer(line, " ");
			int col = 0;
			while (tokenizer.hasMoreTokens()) {
				String tok = tokenizer.nextToken();
				wordSearch[row][col] = new JLabel(tok);
				wordSearch[row][col].setForeground(Color.BLACK);
				wordSearch[row][col]
						.setHorizontalAlignment(SwingConstants.CENTER);
				wordsearchPanel.add(wordSearch[row][col]);
				col++;
			}
			line = reader.readLine();
			row++;
		}
		reader.close();
	}

	/**
	 * Builds the right hand side panel which contains the list of words in the
	 * word search and the search button
	 */
	private void buildRightSidePanel() {
		// Build the panel on the right side
		rightSidePanel = new JPanel();
		rightSidePanel.setBorder(new TitledBorder(new EtchedBorder(),
				"Word List"));
		rightSidePanel.setLayout(new BorderLayout());

		// Build the word list
		wordListLabels = new JLabel[wordList.size()];
		wordListPanel = new JPanel();
		wordListPanel.setLayout(new GridLayout(wordList.size(), 1));
		for (int i = 0; i < this.wordList.size(); i++) {
			// If the line below won't compile in Java 1.4.2, make it
			// String word = (String)this.wordList.get(i);
			String word = this.wordList.get(i);
			wordListLabels[i] = new JLabel(word);
			wordListLabels[i].setForeground(Color.BLUE);
			wordListLabels[i].setHorizontalAlignment(SwingConstants.CENTER);
			wordListPanel.add(wordListLabels[i]);
		}
		rightSidePanel.add(wordListPanel, BorderLayout.CENTER);

		// Add search button
		searchButton = new JButton("Search");
		searchButtonListener = new SearchListener();
		searchButton.addActionListener(searchButtonListener);
		searchButtonPanel = new JPanel();
		searchButtonPanel.add(searchButton);
		rightSidePanel.add(searchButtonPanel, BorderLayout.SOUTH);

		// Add rightSidePanel to main panel
		this.getContentPane().add(rightSidePanel, BorderLayout.EAST);
	}

	/**
	 * Loads the list of words to find in the word search
	 * 
	 * @param wordListFilename
	 *            The file name of the file containing the list of words
	 * @throws WordSearchException
	 *             Throws a WordSearchException if the word search file isn't in
	 *             the proper format
	 * @throws IOException
	 *             Throws an IOException if loading the word search file has
	 *             problems
	 */
	private void loadWordList(String wordListFilename)
			throws WordSearchException, IOException {
		int row = 0;

		wordList = new ArrayList<String>();
		BufferedReader reader = new BufferedReader(new FileReader(
				wordListFilename));
		String line = reader.readLine();
		while (line != null) {
			StringTokenizer tokenizer = new StringTokenizer(line, " ");
			if (tokenizer.countTokens() != COLS_IN_WORDLIST) {
				throw new WordSearchException(
						"Error: only one word per line allowed in the word list");
			}
			String tok = tokenizer.nextToken();
			wordList.add(tok);
			line = reader.readLine();
			row++;
		}
		reader.close();
	}

	/**
	 * Constructor for the WordSearchFrame
	 * 
	 * @param wordSearchFilename
	 *            The name of the word search file
	 * @param wordListFilename
	 *            The name of the word list file
	 * @throws IOException
	 *             Throws an IOException if loading the word search file has
	 *             problems
	 * @throws WordSearchException
	 *             Throws a WordSearchException if the word search file isn't in
	 *             the proper format
	 */
	public WordSearchFrame(String wordSearchFilename, String wordListFilename) throws IOException, WordSearchException {
		this.setSize(FRAME_WIDTH, FRAME_HEIGHT);
		this.getContentPane().setLayout(new BorderLayout());
		this.initGridFromFile(wordSearchFilename);
		buildLeftSidePanel();
		this.loadGridFromFile(wordSearchFilename);
		loadWordList(wordListFilename);
		buildRightSidePanel();
	}

	/**
	 * Constructor which takes a wordSearch 2D String array and a wordList String array
	 * @param wordSearch The word search represented as a 2D String array
	 * @param wordList The list of words to search for
	 * @throws IOException Throws an IOException if loading the word search file has
	 *             problems
	 * @throws WordSearchException  Throws a WordSearchException if the word search file isn't in
	 *             the proper format
	 */
	public WordSearchFrame(String[][] wordSearch, String[] wordList) throws IOException, WordSearchException {
		this.setSize(FRAME_WIDTH, FRAME_HEIGHT);
		this.getContentPane().setLayout(new BorderLayout());
		this.numRows = wordSearch.length;
		this.numCols = wordSearch[0].length;
		this.wordSearch = new JLabel[this.numRows][this.numCols];
		buildLeftSidePanel();
		for( int row = 0; row < this.numRows; row++ ) {
			for( int col = 0; col < this.numCols; col++ ) {
				this.wordSearch[row][col] = new JLabel(wordSearch[row][col]);
				this.wordSearch[row][col].setForeground(Color.BLACK);
				this.wordSearch[row][col]
						.setHorizontalAlignment(SwingConstants.CENTER);
				this.wordsearchPanel.add(this.wordSearch[row][col]);
			}
		}

		this.wordList = new ArrayList<String>();
		for( int i = 0; i < wordList.length; i++ ) {
			this.wordList.add(wordList[i]);
		}
		buildRightSidePanel();
	}

	/**
	 * @param args
	 *            The first argument is the word search file, the second
	 *            argument is the word list.
	 */
	public static void main(String[] args) {
		try {
			if (args.length != 2) {
				System.out.println("Command line arguments: <word search file> <word list>");
			} else {
				WordSearchFrame frame = new WordSearchFrame(args[0], args[1]);
				//WordSearchFrame frame = new WordSearchFrame("java.wordsearch.txt", "java.wordlist.txt");
				frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
				frame.setVisible(true);
			}
		} catch (Exception e) {
			System.out.println(e.getMessage());
			e.printStackTrace();
		}
	}
}
