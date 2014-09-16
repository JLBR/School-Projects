
/****************************************************************
** Program:Assignment1.java                              **
** Author: Jimmy                                         **
** Date: 29 Sept 2012                                          **
** Description: Assignment1 Primitive integer data types and   **
** variables limits and behavior demonstration                 **
** Input: none                                                 **
** Output: Console text showing the behavior and limits of     **
** primitive integer data types                                **
*****************************************************************/

//Start Import///////////////////////////////////
import java.math.BigInteger;//http://docs.oracle.com/javase/7/docs/api/java/math/BigInteger.html
//End Import/////////////////////////////////////

public class brewerjiAssignment1 {
	public static void main(String[] args) {
	
		//Start variable declaration/////////////////
		short shVar1;//used for short calculations
		int   intVar2;//used for integer calculations
		long  LoVar3;//used for long calculations
		BigInteger BigVar1 = BigInteger.valueOf(0);//used to show correct output
		//End variable declaration///////////////////
		
		//Start Objective 1a/Prints Min/Max From Built in Data Type Classes/
		System.out.println("Maximum/Minimum Values"); 
		System.out.println("Short   MAX_VALUE =  " + Short.MAX_VALUE);
		System.out.println("Short   MIN_VALUE = " + Short.MIN_VALUE);
		System.out.println("Integer MAX_VALUE =  " + Integer.MAX_VALUE);
		System.out.println("Integer MIN_VALUE = " + Integer.MIN_VALUE);
		System.out.println("Long    MAX_VALUE =  " + Long.MAX_VALUE);
		System.out.println("Long    MIN_VALUE = " + Long.MIN_VALUE);
		//End Objective 1a////////////////////////////
		
		//Start Objective 1b/2/Computes Theoretical minimum and maximums/
		System.out.println("\nJava Computed Maximum/Minimum Values");
		BigVar1 = BigInteger.valueOf(2).pow(15);
		BigVar1 = BigVar1.subtract(BigInteger.valueOf(1));
		System.out.println("Short   maximum value (2^15)-1:  " + BigVar1);
		BigVar1 = BigInteger.valueOf(2).pow(15);
		BigVar1 = BigVar1.multiply(BigInteger.valueOf(-1));
		System.out.println("Short   minimum value (2^15)-1: " + BigVar1);
		BigVar1 = BigInteger.valueOf(2).pow(31);
		BigVar1 = BigVar1.subtract(BigInteger.valueOf(1));
		System.out.println("Integer maximum value (2^31)-1:  " + BigVar1);
		BigVar1 = BigInteger.valueOf(2).pow(31);
		BigVar1 = BigVar1.multiply(BigInteger.valueOf(-1));
		System.out.println("Integer minimum value (2^31)-1: " + BigVar1);
		BigVar1 = BigInteger.valueOf(2).pow(63);
		BigVar1 = BigVar1.subtract(BigInteger.valueOf(1));
		System.out.println("Long    maximum value (2^63)-1:  " + BigVar1);
		BigVar1 = BigInteger.valueOf(2).pow(63);
		BigVar1 = BigVar1.multiply(BigInteger.valueOf(-1));
		System.out.println("Long    minimum value (2^63)-1: " + BigVar1);
		//End Objective 1b/2//////////////////////////
		
		//Start Objective 3/4/5/6/Demonstrate overflow//
		System.out.println("\nOverflow Demonstration");
		shVar1 = (Short.MAX_VALUE);
		BigVar1 = BigInteger.valueOf(shVar1);
		shVar1 = (short)(shVar1+1);//This causes an overflow
		BigVar1 = BigVar1.add(BigInteger.valueOf(1));
		System.out.println("Short      MAX_VALUE + 1 =        " + shVar1);
		System.out.println("BigInteger Short.MAX_VALUE + 1 =   " + BigVar1);
		intVar2 = (Integer.MAX_VALUE);
		BigVar1 = BigInteger.valueOf(intVar2);
		intVar2 = (intVar2+1);//This causes an overflow
		BigVar1 = BigVar1.add(BigInteger.valueOf(1));
		System.out.println("Integer    MAX_VALUE + 1 =        " + intVar2);
		System.out.println("BigInteger Integer.MAX_VALUE + 1 = " + BigVar1);
		LoVar3 = (Long.MAX_VALUE);
		BigVar1 = BigInteger.valueOf(LoVar3);
		LoVar3 = (LoVar3+1);//This causes an overflow
		BigVar1 = BigVar1.add(BigInteger.valueOf(1));
		System.out.println("Long       MAX_VALUE + 1 =        " + LoVar3);
		System.out.println("BigInteger Long.MAX_VALUE + 1 =    " + BigVar1);
		//End Objective 3/4/5/6////////////////////////
		
		//Start Objective Additional Objective 1/Demonstrate adding MAX_VALUE to itself/
		System.out.println("\nMax Values added to themselves");
		BigVar1 = BigInteger.valueOf(Short.MAX_VALUE);
		BigVar1 = BigVar1.add(BigInteger.valueOf(Short.MAX_VALUE));
		System.out.println("Short      MAX_VALUE + MAX_VALUE =                  "+ (Short.MAX_VALUE+Short.MAX_VALUE));//This does not cause an overflow
		System.out.println("BigInteger Short.MAX_VALUE + Short.MAX_VALUE =      " + BigVar1);
		BigVar1 = BigInteger.valueOf(Integer.MAX_VALUE);
		BigVar1 = BigVar1.add(BigInteger.valueOf(Integer.MAX_VALUE));
		System.out.println("Integer    MAX_VALUE + MAX_VALUE =                 "+ (Integer.MAX_VALUE+Integer.MAX_VALUE));//This causes an overflow
		System.out.println("BigInteger Integer.MAX_VALUE + Integer.MAX_VALUE =  " + BigVar1);
		BigVar1 = BigInteger.valueOf(Long.MAX_VALUE);
		BigVar1 = BigVar1.add(BigInteger.valueOf(Long.MAX_VALUE));
		System.out.println("Long       MAX_VALUE + MAX_VALUE =                 "+ (Long.MAX_VALUE+Long.MAX_VALUE));//This causes an overflow
		System.out.println("BigInteger Long.MAX_VALUE + Long.MAX_VALUE =        " + BigVar1);
		//End Objective Additional Objective 1//////////

		//Start Objective Additional Objective 2/Demonstrate adding 1 to MAX_VALUE literal/
		System.out.println("\nMax Values added to 1");
		System.out.println("Short   32767 + 1 =                "+ (32767+1));
		System.out.println("Integer 2147483647 + 1 =          "+ (2147483647+1));//This causes an overflow
		System.out.println("Long    9223372036854775807 + 1 = "+ (9223372036854775807L+1));//This causes an overflow//I found out about adding the L to inline type cast literals from http://en.wikiversity.org/wiki/Introduction_to_Programming_in_Java/Integer_variables
		//End Objective Additional Objective 2//////////
		
	}//end main

}//end class
