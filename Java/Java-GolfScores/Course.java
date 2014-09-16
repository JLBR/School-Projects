	    /****************************************************************
		** Class: Course.java                                          **
		** Author: Jimmy                                               **
		** Date: 29 Oct 2012                                           **
		** Description: Course contains and provides access to the     **
		** Par for all holes in a round                                **
		*****************************************************************/

/**
 * class Course used to hold the par data for a round at a course
 * <p>Constructor<ul><li>
 * 	Course(byte par)
 * </ul><p>Accessor methods<ul><li>
 * public void setPar(byte hole, byte par)
 * <li>public int getPar(byte hole, Boolean course) course set to true for total par false for per hole
 * <li>	public byte getNumberOfHoles()
 */
public class Course {

	private byte holePar[];

	/**
	 * Course constructor
	 * @param par par for hole 1
	 */
	Course(byte par){
		holePar = new byte[1];
		holePar[0]=par;
	}//empty course object
	
	/**
	 * setPar by hole
	 * @param hole
	 * @param par
	 */
	public void setPar(byte hole, byte par){
		
		if( (hole+1)> (byte) holePar.length){
			byte holeParTemp[] = holePar;//temp array
			holePar = new byte[hole];
			
			for(byte i = 0; i<holeParTemp.length;i++){
				holePar[i]= holeParTemp[i];
			}//end for update array
			
			holePar[hole-1]=par;
			
		}//end if too short
	}//end setPar
	
	/**
	 * getPar by hole if course is false otherwise it returns the sum of pars
	 * @param hole
	 * @param course true returns sum of pars, false by hole
	 * @return individual par for hole, or sum of pars
	 */
	public int getPar(byte hole, Boolean course){
		
		if(course){
			
			int par=0;
			for(byte i = 0; i<holePar.length;i++){
			par = par + holePar[i];
			}//end for
			return par;
		
		}else{
		
			return holePar[hole];
			
		}//end if
	}//end getPar
	
	/**
	 * getNumberOfHoles returns the current size of the array
	 * @return byte number of holes
	 */
	public byte getNumberOfHoles(){
		return (byte) holePar.length;
	}//end getNumberOfHoles
	
}//end class Course
