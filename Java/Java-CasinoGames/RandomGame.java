package Assignment4;
	/*****************************************************************
		** Program: RandomGame.java                                     **
		** Author: Jimmy                                                **
		** Date: 7 Oct 2012                                             **
		** Description: Random number and Card guessing game            **
		** Input: User console entry of integers in response to the     **
		** menu                                                         **
		** Output:                                                      **
		** Main menu selects the random number or random card game, or  **
		** quits                                                        **
		** Random number game main menu has the user select to choose   **
		** number or to have the computer choose a number to guess from **
		** a random number between 1-52                                 **
		** Random Card game main menu has the user select to choose     **
		** from a deck of 52 or have the computer choose a card to guess**
		*****************************************************************/
	

//Start Import
import java.util.NoSuchElementException;
import java.util.Scanner;
//End Import


public class RandomGame implements Game, Instructions {

	public void play(){//this is to make main compliant with game
		try {
			main();
		} catch (Exception e) {//it will never get here, main quits first
			// TODO Auto-generated catch block
			e.printStackTrace();
		}//end try catch
	}//end play
	
	
	public void main() throws Exception {
		/*************************************************
		 ** Method:Main()                               **	 
		 ** Description: Runs the main method launching ** 
		 ** other methods from this class               **
		 ** Input: Console                              **
		 ** Return: Exit code 0 on normal exit, 1 on    **
		 ** error                                       **
		 ************************************************/
		//Start Variable Declaration
		Scanner input = new Scanner(System.in);
		byte selection = 99;//Used for main menu input 99 is never a functional response
		//End Variable Declaration
		
			while(selection !=3){
				
				//Main menu
				System.out.println("\nSelect from the following:");
				System.out.println("(1)Guess my number game");
				System.out.println("(2)Guess my card game");
				System.out.println("(3)Quit back to the main menu\n");

				try {
					
					selection = getByte((byte)1,(byte)3);
					if(selection == 1){
						guessMyNumber();
					}else if(selection == 2){
						guessMyCard();
					}//end selection if
					
				} catch (NoSuchElementException e) {//handles EOF in System.in gracefully
					System.out.println("Don't do that again\n");
					input.close();//garbage cleanup
					//e.printStackTrace();//for debugging
					System.exit(1);//exits with a status of 1
				}//end Try-Catch
				
			}//End while
			
			
		System.gc();
		//System.out.println("Good bye!!!\n");
		//input.close();//garbage cleanup
		//System.exit(0);//exits normally with a status of 0
	}//end Main

	
	private static void guessMyCard() throws Exception {
		/*************************************************
		 ** Method:guessMyCard()                        **	 
		 ** Description: Runs the guess my card game    ** 
		 ** Input: Console                              **
		 ** Return: None                                **
		 ************************************************/
		//start variable declaration
		byte cardValue;//numeric card value
		byte suitValue;//numeric suit value
		byte faceValue;//value of the card
		byte selection = 99;//used for user input
		byte myCard;//random number
		byte yourGuess;
		byte highGuess;//used to limit the range
		byte lowGuess;//used to limit the range
		byte tries;//used to limit tries
		String pastGuesses = "";//keeps track of prior guesses
		//end variable declaration
		
		while(selection != 4){//loops until quitting
			
			//Reset game variables for each iteration
			myCard = (byte) ((Math.random()*52)+1);//random number, also used for the computer guesses
			highGuess = 52;//used to limit the range
			lowGuess = 1;//used to limit the range
			tries = 5;//used to limit tries
			pastGuesses = "";//keeps track of prior guesses
			selection = 99;//updated to prevent an infinite loop on EOF error
			//end reset

			//main menu
			System.out.println("Select from the following options:");
			System.out.println("(1)You guess my card");
			System.out.println("(2)I guess your card");
			System.out.println("(3)Card value instructions");
			System.out.println("(4)Quit to the main menu\n");
			
			selection = getByte((byte)1,(byte)4);

			if(selection == 1){//guess the computer's number
				while(tries != 0){
					System.out.println("My card is between " + getCardName(lowGuess) +" and " + getCardName(highGuess));
				
					if(pastGuesses ==""){
						System.out.println("You have 5 guesses remaining");
					}else{
						System.out.println("You have tried: " + pastGuesses + " and have " + tries +" guesses remaining" );
					}
					
					System.out.println("What suit is my Card?");
					System.out.println("(1)Heart	(3)Spades");
					System.out.println("(2)Diamonds	(4)Clubs");
					
					suitValue = (byte) (getByte((byte)1,(byte)4)-1);
					
					System.out.println("(1)Ace		(8)Eight");
					System.out.println("(2)Two		(9)Nine");
					System.out.println("(3)Three	(10)Ten");
					System.out.println("(4)Four		(11)Jack");
					System.out.println("(5)Five		(12)Queen");
					System.out.println("(6)Six		(13)King");
					System.out.println("(7)Seven");
					
					faceValue = getByte((byte)1,(byte)13);
					cardValue = (byte) (faceValue + (suitValue *13));
					
					if(cardValue == myCard){//correct guess
						pastGuesses = "Congratulations you got my card in " + (6 - tries ) + " guesses!!!!";
						break;
					}else if(cardValue> myCard) {//too high
						System.out.println("Close, but too high.");
						highGuess = cardValue;
						//pastGuesses = pastGuesses + " " + yourGuess;//Append last guess to guess string
					}else {
						System.out.println("Close, but too low.");
						lowGuess = cardValue;
						//pastGuesses = pastGuesses + " " + yourGuess;//Append last guess to guess string
					}//end guess checking if

					pastGuesses = pastGuesses + getCardName(cardValue) + ", ";//Append last guess to guess string
					--tries;//subtract one from tries
					
					if(tries == 0){
						pastGuesses = ("Sorry but you have run out of guesses.  My card was " + getCardName(myCard) + ".  Please try again!\n");
					}//update final out
					
				}//end no tries while
				
				System.out.println(pastGuesses);//final message
				
			}//end guess my number if
			else if(selection == 2){//guess the user's number
				myCard = 26;
				while(tries != 0){
					System.out.println("Your card is between "+ getCardName(lowGuess) +" and " + getCardName(highGuess));
				
					if(pastGuesses == ""){
						System.out.println("I have 5 guesses remaining");
					}else{
						System.out.println("I have tried: " + pastGuesses + " and have " + tries +" guesses remaining" );
					}
				
					System.out.println("What is your card?  Is it " + getCardName(myCard) + "?");
					System.out.println("Remember the suit values are:");
					System.out.println("(1)Heart	(3)Spades");
					System.out.println("(2)Diamonds	(4)Clubs");
					System.out.println("\n(1)Too low\n(2)Too high\n(3)That is my card");

					yourGuess = getByte((byte)1,(byte)3);
					
					if(yourGuess == 3){//correct guess
						pastGuesses = "Congratulations to me!!! I got your card in " + (6 - tries) + " guesses!!!!\n";
						break;
					}else if(yourGuess == 2) {//too high
						System.out.println("I was close, but too high.");
						highGuess = myCard;
						pastGuesses = pastGuesses + " " + getCardName(myCard);//Append last guess to guess string
						myCard = (byte) ((myCard - (highGuess - lowGuess)/2));//Update myGuess
					}else {
						System.out.println("I was close, but too low.");
						lowGuess = myCard;
						pastGuesses = pastGuesses + " " + getCardName(myCard);//Append last guess to guess string
						myCard = (byte) (myCard + (highGuess - lowGuess)/2);//Update myGuess
					}//end guess checking if

					
					//pastGuesses = pastGuesses + " " + myNumber;//Append last guess to guess string
					--tries;//subtract one from tries

					if(myCard>highGuess || myCard<lowGuess){//Corrects for rounding errors
						if(myCard>highGuess){
							myCard = (byte) (myCard-1);
						}else{
							myCard =(byte) (myCard+1);
						}
					}//end myCard guess update
					
					if(tries == 0){
						pastGuesses = ("Oh no!! Looks like your card will be a secret for all time.  Please let me try again!\n");
					}//update final out
					
				}//end no tries while
				
				System.out.println(pastGuesses);//final message				
				
			}//end guess your card if
			else if(selection == 3){//instructions
				System.out.println("\nThe face value of the cards are as follows:");
				System.out.println("(1)Ace		(8)Eight");
				System.out.println("(2)Two		(9)Nine");
				System.out.println("(3)Three	(10)Ten");
				System.out.println("(4)Four		(11)Jack");
				System.out.println("(5)Five		(12)Queen");
				System.out.println("(6)Six		(13)King");
				System.out.println("(7)Seven");
				System.out.println("\nThe suit values are as follows:");
				System.out.println("(1)Heart	(3)Spades");
				System.out.println("(2)Diamonds	(4)Clubs");
				System.out.println("Example: an Ace of Hearts is lower than an Ace of Clubs\n");
			}
		}//end main menu while	
	}//end card guessing


	private static void guessMyNumber() throws Exception {
		/*************************************************
		 ** Method:guessMyNumber()                      **	 
		 ** Description: Runs the guess my number game  ** 
		 ** Input: Console                              **
		 ** Return: None                                **
		 ************************************************/
		//start variable declaration
		byte selection = 99;//used for user input
		byte myNumber;//random number
		byte yourGuess;
		byte highGuess;//used to limit the range
		byte lowGuess;//used to limit the range
		byte tries;//used to limit tries
		String pastGuesses = "";//keeps track of prior guesses
		//end variable declaration
		
		while(selection != 3){//loops until quitting
			
			//Reset game variables for each iteration
			myNumber = (byte) ((Math.random()*52)+1);//random number, also used for the computer guesses
			highGuess = 52;//used to limit the range
			lowGuess = 1;//used to limit the range
			tries = 5;//used to limit tries
			pastGuesses = "";//keeps track of prior guesses
			selection = 99;//updated to prevent an infinite loop on EOF error
			//end reset

			//main menu
			System.out.println("Select from the following options:");
			System.out.println("(1)You guess my number");
			System.out.println("(2)I guess your number");
			System.out.println("(3)Quit to the main menu\n");
			
			selection = getByte((byte)1,(byte)3);

			if(selection == 1){//guess the computer's number
				while(tries != 0){
					System.out.println("My number is between " + lowGuess +" and " + highGuess);
				
					if(pastGuesses ==""){
						System.out.println("You have 5 guesses remaining");
					}else{
						System.out.println("You have tried: " + pastGuesses + " and have " + tries +" guesses remaining" );
					}
				
					System.out.println("What is my number?");
					yourGuess = getByte(lowGuess,highGuess);
					
					if(yourGuess == myNumber){//correct guess
						pastGuesses = "Congratulations you got my number in " + (6 - tries ) + " guesses!!!!";
						break;
					}else if(yourGuess> myNumber) {//too high
						System.out.println("Close, but too high.");
						highGuess = yourGuess;
						}else {
						System.out.println("Close, but too low.");
						lowGuess = yourGuess;
					}//end guess checking if

					pastGuesses = pastGuesses + " " + yourGuess;//Append last guess to guess string
					--tries;//subtract one from tries
					
					if(tries == 0){
						pastGuesses = ("Sorry but you have run out of guesses.  My number was " + myNumber + ".  Please try again!\n");
					}//update final out
					
				}//end no tries while
				
				System.out.println(pastGuesses);//final message
				
			}//end guess my number if
			else if(selection == 2){//guess the user's number
				myNumber = 26;
				while(tries != 0){
					System.out.println("Your number is between " + lowGuess +" and " + highGuess);
				
					if(pastGuesses == ""){
						System.out.println("I have 5 guesses remaining");
					}else{
						System.out.println("I have tried: " + pastGuesses + " and have " + tries +" guesses remaining" );
					}
				
					System.out.println("What is your number?  Is it " + myNumber + "?");
					System.out.println("\n(1)Too low\n(2)Too high\n(3)That is my number");
					yourGuess = getByte((byte)1,(byte)3);
					
					if(yourGuess == 3){//correct guess
						pastGuesses = "Congratulations to me!!! I got your number in " + (6 - tries) + " guesses!!!!\n";
						break;
					}else if(yourGuess == 2) {//too high
						System.out.println("I was close, but too high.");
						highGuess = myNumber;
						pastGuesses = pastGuesses + " " + myNumber;//Append last guess to guess string
						myNumber = (byte) ((myNumber - (highGuess - lowGuess)/2));//Update myGuess
					}else {
						System.out.println("I was close, but too low.");
						lowGuess = myNumber;
						pastGuesses = pastGuesses + " " + myNumber;//Append last guess to guess string
						myNumber = (byte) (myNumber + (highGuess - lowGuess)/2);//Update myGuess
					}//end guess checking if

					--tries;//subtract one from tries

					//myNumber = (byte) (myNumber/2);//Update myGuess
					if(myNumber>highGuess || myNumber<lowGuess){//Corrects for rounding errors
						if(myNumber>highGuess){
							myNumber = (byte) (myNumber-1);
						}else{
							myNumber =(byte) (myNumber+1);
						}
					}//end myNumber guess update
					
					if(tries == 0){
						pastGuesses = ("Oh no!! Looks like your number will be a secret for all time.  Please let me try again!\n");
					}//update final out
					
				}//end no tries while
				
				System.out.println(pastGuesses);//final message				
				
			}//end guess your number if
		}//end main menu while
	}//End guessMyNumber

	/**
	 * getByte gets a byte from the console and forces the user to keep trying until it is in range
	 * @param lowerRange lowest acceptable range for input
	 * @param upperRange highest acceptable range for input
	 * @return a byte in the range of lower to upper
	 * @throws Exception exception handled by main's handler
	 */
	private static byte getByte(byte lowerRange, byte upperRange) throws Exception{		
		//Start Variable Declaration
		byte byteIn = 99;
		@SuppressWarnings("resource")
		Scanner input = new Scanner(System.in);
		//End Variable Declaration

		while(byteIn == 99){
			if(input.hasNextByte()){
				
				byteIn = input.nextByte();
				input.nextLine();//clears buffer
			
				if(byteIn>upperRange || byteIn<lowerRange){//detects bounds for integers
					System.out.println("Please enter " + lowerRange + " to " + upperRange);
					byteIn = 99;
				}//end out of bounds if

			}else{
				//Handles all non-byte input
				input.nextLine();//clears buffer
				System.out.println("Please enter " + lowerRange + " to " + upperRange);
			}//End if
		}
		return byteIn;
	}//End getByte
	
	private static String getCardName(byte cardSumValue){
		/*************************************************
		 ** Method:getCardName()                        **	 
		 ** Description: Converts card values to text   ** 
		 ** Input: card values 1-52                     **
		 ** Return: string with the card name           **
		 ************************************************/
		//Constant declaration start
		final byte SUIT_HEARTS = 0;
		final byte SUIT_SPADES = 26;
		final byte SUIT_DIAMONDS = 13;
		final byte SUIT_CLUBS = 39;
		final byte CARD_KING = 13;
		final byte CARD_JACK = 11;
		final byte CARD_QUEEN = 12;
		final byte CARD_TEN = 10;
		final byte CARD_NINE = 9;
		final byte CARD_EIGHT = 8;
		final byte CARD_SEVEN = 7;
		final byte CARD_SIX = 6;
		final byte CARD_FIVE = 5;
		final byte CARD_FOUR = 4;
		final byte CARD_THREE = 3;
		final byte CARD_TWO = 2;
		final byte CARD_ACE = 1;
		//Constant declaration end
		
		//Start variable deceleration
		byte suitValue;//stores computed suit value
		byte faceValue;//stores computed face value
		String cardName = "";//Stores text name of the card
		//End variable declaration
		
		//Breaks down the values to specific cards
		if (cardSumValue>SUIT_CLUBS){
			suitValue = SUIT_CLUBS;
			faceValue = (byte) (cardSumValue - SUIT_CLUBS);
		} else if (cardSumValue>SUIT_SPADES){
			suitValue = SUIT_SPADES;
			faceValue = (byte) (cardSumValue - SUIT_SPADES);
		} else if(cardSumValue>SUIT_DIAMONDS){
			suitValue = SUIT_DIAMONDS;
			faceValue = (byte) (cardSumValue - SUIT_DIAMONDS);
		}else {
			suitValue = SUIT_HEARTS;
			faceValue = (byte) (cardSumValue - SUIT_HEARTS);
		}//end if card breakdown
		
		switch (suitValue)//Presents suits as text instead of numbers
		{
			case SUIT_HEARTS: cardName = String.format(" of Hearts");
			break;
			case SUIT_SPADES: cardName = String.format(" of Spades");
			break;
			case SUIT_DIAMONDS: cardName = String.format(" of Diamonds");
			break;
			case SUIT_CLUBS: cardName = String.format(" of Clubs");
			break;		
		}//end switch
		switch (faceValue)//Presents values as text instead of numbers
		{
			case CARD_KING: cardName = String.format("King" + cardName);
			break;
			case CARD_JACK: cardName = String.format("Jack" + cardName);
			break;
			case CARD_QUEEN: cardName = String.format("Queen" + cardName);
			break;
			case CARD_ACE: cardName = String.format("Ace" + cardName);
			break;
			case CARD_TWO: cardName = String.format("Two" + cardName);
			break;
			case CARD_THREE: cardName = String.format("Three" + cardName);
			break;
			case CARD_FOUR: cardName = String.format("Four" + cardName);
			break;
			case CARD_FIVE: cardName = String.format("Five" + cardName);
			break;
			case CARD_SIX: cardName = String.format("Six" + cardName);
			break;
			case CARD_SEVEN: cardName = String.format("Seven" + cardName);
			break;
			case CARD_EIGHT: cardName = String.format("Eight" + cardName);
			break;
			case CARD_NINE: cardName = String.format("Nine" + cardName);
			break;
			case CARD_TEN: cardName = String.format("Ten" + cardName);
			break;
		}//end switch
		return cardName;
	}//end getCardName


	@Override
	public void showInstructions() {
		System.out.println("Random Numbers:");
		System.out.println("The computer will select a number from 1-52, and you need to guess withing 5 attempts to win.");
		System.out.println("You can also choos a number and let the computer try and guess your number.  Is your secret safe?\n");
		System.out.println("Random Cards:");
		System.out.println("Like random numbers the computer or you can choose a card from a deck of 52 and one of you will attempt to guess the card.");
		System.out.println("\nThe face value of the cards are as follows:");
		System.out.println("(1)Ace		(8)Eight");
		System.out.println("(2)Two		(9)Nine");
		System.out.println("(3)Three	(10)Ten");
		System.out.println("(4)Four		(11)Jack");
		System.out.println("(5)Five		(12)Queen");
		System.out.println("(6)Six		(13)King");
		System.out.println("(7)Seven");
		System.out.println("\nThe suit values are as follows:");
		System.out.println("(1)Heart	(3)Spades");
		System.out.println("(2)Diamonds	(4)Clubs");
		System.out.println("Example: an Ace of Hearts is lower than an Ace of Clubs\n");
	}//end instructions


	@Override
	public String getTitle() {
		return "Everyones Guessing Random Numbers and Cards";
	}//end getTitle
	
}//end class