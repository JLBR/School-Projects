TITLE Program01     (Program01.asm)

; Author: Jimmy 
; Course CS271 / Project ID  : Program01              Date: 17 Jan 2013
; Description:	 This program takes two numbers given by the user and adds, subtracts, muliplies, and divides them
;			 displaying the results as integers and in the final division as a floating point number rounded
;			 to 3 decimal places.  Finaly the user can repeat as many times as they would like.

INCLUDE Irvine32.inc

; (insert constant definitions here)

.data

titleLine		 BYTE    "         Elementary Arithmetic by Jimmy ",0Dh,0Ah,0
instructions	 BYTE    "Enter 2 numbers, and I'll show you the sum, "
			 BYTE    "difference, product, quotient,",0Dh,0Ah
			 BYTE    "and remainder.",0
orderError	 BYTE    "Please enter the numbers with the second number "
			 BYTE    "smaller than the first.", 0Dh,0Ah
			 BYTE    "Thank you.",0
floatingPoint	 BYTE    "Floating",0
firstPrompt	 BYTE    "First number: ",0
secondPrompt	 BYTE    "Second number: ",0
menu			 BYTE    "Want to go again(Y/N)? ",0
invalidEntry	 BYTE    "Please enter Y or N.  Thank you. ",0
goodBye		 BYTE    "Thank you for playing.  Good Bye.",0

remainder		 BYTE    " remainder ",0
plus	   		 BYTE    " + ",0
equal		 BYTE    " = ",0
minus		 BYTE    " - ",0
times		 BYTE    " x ",0
divide		 BYTE    " ",0F6h," ",0
point		 BYTE    ".",0
threeO		 BYTE    "000",0

firstNum		 DWORD   0
secondNum		 DWORD   0
remainderX	 DWORD   0
result		 DWORD   0
tempString	 BYTE    ?



.code
main PROC

;introduction
    mov	  edx, OFFSET titleLine
    call	  WriteString
    call	  Crlf

;get the data
    mov	  edx, OFFSET instructions
    call	  WriteString
    call	  Crlf
    call	  Crlf

GetNumbers:
    mov	  edx, OFFSET firstPrompt	;Prompt for first number
    call	  WriteString
    call	  ReadDec
    mov	  firstNum, eax			;Store first number in firstNum
    

    mov	  edx, OFFSET secondPrompt	;Prompt for second number
    call	  WriteString
    call	  ReadDec
    mov	  secondNum, eax			;Store second number in secondNum
    call	  Crlf

;handle second number smaller than first error
    cmp	  eax, firstNum			;Check that the second number in eax is smaller than firstNum
    jb	  GoodNumbers				;jump if eax > firstNum

    WrongNumbers:
	   mov	  edx, OFFSET orderError	;Give error message
	   call	  WriteString
	   call	  Crlf
	   call	  Crlf
	   jmp GetNumbers				;go back to getting the numbers

GoodNumbers:
;calculate values

;display results firstNum + secondNum
    mov	  eax, firstNum
    call	  WriteDec				;display the first number
    mov	  edx, OFFSET plus
    call	  WriteString				;display the operator
    mov	  eax, secondNum
    call	  WriteDec				;display the second number
    mov	  edx, OFFSET equal
    call	  WriteString				;display equals
    add	  eax, firstNum			;add second number to the first number
    mov	  result, eax				;store the result
    call	  WriteDec				;display the results
    call	  Crlf
   
;display results firstNum - secondNum
    mov	  eax, firstNum
    call	  WriteDec				;display the first number
    mov	  edx, OFFSET minus
    call	  WriteString				;display the operator
    mov	  eax, secondNum
    call	  WriteDec				;display the second number
    mov	  edx, OFFSET equal
    call	  WriteString				;display equals
    mov	  eax, firstNum
    sub	  eax, secondNum			;subtract the second number from the first
    mov	  result, eax				;store the result
    call	  WriteDec				;display the results
    call	  Crlf

;display results firstNum x secondNum
    mov	  eax, firstNum
    call	  WriteDec				;display the first number
    mov	  edx, OFFSET times
    call	  WriteString				;display the operator
    mov	  eax, secondNum
    call	  WriteDec				;display the second number
    mov	  edx, OFFSET equal
    call	  WriteString				;display equals
    mov	  ebx, firstNum
    mul	  ebx					;multiply ebx the first number by the second number eax
    mov	  result, eax				;store the result
    call	  WriteDec				;display the results
    call	  Crlf

;display results firstNum / secondNum and remainder
    mov	  eax, firstNum
    call	  WriteDec				;display the first number
    mov	  edx, OFFSET divide
    call	  WriteString				;display the operator
    mov	  eax, secondNum
    call	  WriteDec				;display the second number
    mov	  edx, OFFSET equal
    call	  WriteString				;display equals
    mov	  eax, firstNum   
    cdq							;extend the sign
    div	  secondNum				;divide eax by secondNum
    mov	  result, eax				;store the result
    call	  WriteDec				;display the results
    mov	  remainderX, edx			;store the remainder from edx into remainderX
    mov	  edx, OFFSET remainder		
    call	  WriteString				;display the word remainder
    mov	  eax, remainderX 
    call	  WriteDec				;display the remainder
    call	  Crlf

;display results firstNum / secondNum and remainder floating point
    mov	  eax, firstNum
    call	  WriteDec				;display the first number
    mov	  edx, OFFSET divide	 
    call	  WriteString				;display the operator
    mov	  eax, secondNum		 
    call	  WriteDec				;display the second number
    mov	  edx, OFFSET equal
    call	  WriteString				;display equals
    fild	  firstNum				;load first number into the FPU ST(0)
    fidiv	  secondNum				;divide the first number by the second number

    mov	  remainderX, 1000			;store 1000 for use
    fimul	  remainderX				;multiply ST(0) by 1000
    fist	  firstNum				;store the result as a rounded integer in firstNum
    mov	  eax, firstNum			
    cdq							;prepair for integer divison
    div	  remainderX				;divide firstNum by 1000
    mov	  result, eax				;store the result
    mov	  remainderX, edx			;store the remainder of the divide by 1000 in remainderX
    
    call	  WriteDec				;write the integer result of the divison
    mov	  edx, OFFSET point			
    call	  WriteString				;print the decimal point
			
    
;add decimal places if less than 3
    cmp	  remainderX, 0			;If the remainder is not zero jump to adding decimal places
    jne	  addZeros
	   mov	  edx, OFFSET threeO	;display 3 zeros if the remainder is zero
	   call	  WriteString
        call	  Crlf
	   call	  Crlf
	   jmp	  PlayAgain			;jump to the quit continue menu

addZeros:
    cmp	  remainderX, 10			;if the remainder is less than 10, multiply by 100 increasing precision by two
    ja	  addOneZero
	   mov	 result, 100
	   mov	 eax, remainderX		;put the remainder in eax for multiplication			
	   mul	 result
	   mov	 remainderX, eax	 	;move the results to remainderX
	   jmp	 threeGood

addOneZero:
    cmp	  remainderX, 100			;if the remainder is less than 100, multiply by 10 increasing precision by one
    ja	  threeGood
	   mov	 result, 10
	   mov	 eax, remainderX		;put the remainder in eax for multiplication
	   mul	 result				;multiply the remainder by
	   mov	 remainderX, eax	 	;move the results to remainderX

threeGood:
    mov	  eax, remainderX
    call	  WriteDec				;display the first 3 digits of the floating point number rounded up


 ;seperate calculations from the continue quit menu
    call	  Crlf
    call	  Crlf

PlayAgain:

;play again?

;get input
    mov	  edx, OFFSET menu
    call	  WriteString				;display quit or restart
    call	  ReadChar				;read the first key pressed
    mov	  tempstring, al

;convert input to uppercase if needed
    cmp	  al, "a"					;determin the case of the character
    jb	  YorN					;if "a" < then it already is uppercase

    ToUpper:
	   sub	  al, "a"				;subtract the smalest lowercase character
	   add	  al, "A"				;add the smallest uppercase charater
	   mov	  tempstring, al

;processes input
YorN:
    mov	  edx, OFFSET tempstring
    call	  WriteString			 ;echo the keyboard in uppercase
    call	  Crlf
    call	  Crlf
    cmp	  al, "Y"				 ;test if Y was pressed
    je	  GetNumbers			 ;jump to the begining if Y
    cmp	  tempString, "N"		 ;test if N was pressed
    je	  GoodByeX			 ;quit if N was pressed

;handle invalid input
	   mov	 edx, OFFSET invalidEntry
	   call	 WriteString		 ;if y or N was not pressed dissplay an error
	   call	 Crlf
	   call	 Crlf
	   jmp	 PlayAgain		 ;jump back to asking to quit or restart

GoodByeX:

;say goodbuy
    mov	  edx, OFFSET goodBye
    call	  WriteString			 ;display goodbye string
    call	  Crlf
    call	  Crlf

    exit						 ;exit to operating system
main ENDP

; (insert additional procedures here)

END main
