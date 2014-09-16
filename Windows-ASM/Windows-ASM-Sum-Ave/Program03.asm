TITLE Fibonacci     (Program03.asm)

; Author: Jimmy 
; Course CS271 / Project ID  : Assignment 03               Date: 10 Feb 2013
; Description: Takes a users name and echos it.
; Takes a series of integers 0-100 and displays the sum and average.

INCLUDE Irvine32.inc

introduction PROTO,
    bufferPtr:PTR BYTE,
    bufferSize:DWORD

getNumber PROTO,
    counterPTR:PTR DWORD,
    summationPTR:PTR DWORD

displayResults PROTO,
    counterX: DWORD,
    summationX: DWORD

threePrec PROTO,
    firstNum: DWORD,
    secondNum: DWORD

 farewell PROTO,
    namePtr:PTR BYTE

.data

   userName	 BYTE    30 Dup(0)	  ;user name max lenght 29
   counter	 DWORD   0		  ;total number of terms entered
   summation	 DWORD   0		  ;sum of entered terms

.code
main PROC

    call	  consoleSetup								    ;set the console to 80x300
    INVOKE  introduction,   ADDR userName, SIZEOF userName	    ;display the introduction and get the user's name
    call	  userInstructions							    ;display the instruction

GetSum:
    INVOKE  getNumber,	   ADDR counter, ADDR summation	    ;get a number 
    mov	  ecx, eax								    ;if getNumber returned 2 loop, if 1, end the loop
    loop    GetSum									    
    
    INVOKE  displayResults,  counter, summation			    ;display and calculate the results
    INVOKE  farewell,	   ADDR userName				    ;display the goodbye with the user's name

exit												    ;exit program
main ENDP

;---------------------------------------------
introduction PROC USES ECX EDX EAX,
    bufferPtr:PTR BYTE,
    bufferSize:DWORD
; Prints the program title and progamers name
; Receves: bufferPtr to place the data in, and bufferSize
; Returns: nothing
;---------------------------------------------

.data

    intro		 BYTE	"Welcome to the Integer Accumulator Programed by Jimmy ",0Dh,0Ah,0Dh,0Ah
			 BYTE	"What's your name? ",0
    welcome	 BYTE	"Welcome, ",0

    byteCount	 DWORD	?		  ;size counter


.code

    pushf						 ;store flag settings

    mov	   edx, OFFSET intro	  ;load intro
    call	   WriteString			  ;print string

GetUserName:

    mov	   edx, bufferPtr		  ;prepair buffer for input
    mov	   ecx, bufferSize		  ;set the maximum size to put in the buffer
    call	   ReadString			  ;Gets user input as a string
    mov	   byteCount, eax		  ;length of useable string

    mov	   dh, 2				  ;row 2
    mov	   dl, 18				  ;column 18
    call	   Gotoxy				  ;place the cursor one space past the question mark

	   cmp	   byteCount, 0		  ;if byteCount = empty string
	   je	   GetUserName			  ;then go back and get the users name

    mov	  dh, 2				 ;row 2
    mov	  dl, 0				 ;column 0
    call	  Gotoxy				 ;return to the row start
    call	  clearField			 ;clear the entire line

    mov	  edx, OFFSET welcome	 ;load welcome
    call	  WriteString			 ;Print welcome
    mov	  edx, [bufferPtr]		 ;move user name to print
    call	  WriteString			 ;Print user name
    call	  Crlf				 ;new line
    call	  Crlf				 ;new line
    
    popf						 ;restore flag settings

ret						  ;exit procedure
introduction ENDP

;---------------------------------------------
userInstructions PROC USES EDX
; Prints the instructions
; Receves: Nothing
; Returns: Nothing
;---------------------------------------------

.data

    instructions	BYTE    "Enter numbers 0-100 to be summed.",0Dh,0Ah
				BYTE    "Enter a negitive (-) number to stop summing numbers",0Dh,0Ah,0Dh,0Ah,0

.code

    pushf					  		 ;store flag settings

    mov	   edx, OFFSET instructions	 ;load instructions
    call	   WriteString				 ;print instructions

    popf							 ;restore flag settings

ret							 ;exit procedure
userInstructions ENDP

;---------------------------------------------
getNumber PROC USES EBX EDX ECX ESI EDI,
    counterPTR:PTR DWORD,
    summationPTR:PTR DWORD
; gets a number and adds it to the sum while incrementing the count
; Receves: nothing
; Returns: eax - 1 means end, 2 means continue
;---------------------------------------------

    UPPER_LIMIT EQU 100	   ;Maximum based on assignment specifications
    LOWER_LIMIT EQU 0	   ;Bottom based on assignment specifications

.data
    
    howMany		BYTE	   "Enter number(",0
    howMany2		BYTE	   "): ",0
    errorMessage	BYTE	   "Please enter a number 0-100 or a negitive number to quit",0

.code

    pushf					  		;store flag settings

    mov	  ecx, 1					;do until valid input

GetInput:
    mov	  edx,OFFSET howMany		;load prompt
    call	  WriteString				;write prompt
    mov	  esi, counterPTR			;load the pointer into esi
    mov	  eax, [esi]				;load the value into eax

    inc	  eax					;increment eax one to start at 1
    call	  WriteDec				;display the line number/entity number
    mov	  edx, OFFSET howMany2		;load the string
    call	  WriteString				;display the string

    call	  ReadInt					;read user input into eax

    cmp	  eax, UPPER_LIMIT			;if input > UPPER_LIMIT
    jg	  InputError				;Goto error message
    cmp	  eax, LOWER_LIMIT			;if input < LOWER_LIMIT
    jl	  QuitSum					;goto error message

    mov	  edi, summationPTR			;load the pointer into edi
    add	  eax, [edi]	   			;increase the sum by the amount entered
    mov	  [edi], eax				;load the updated results into the pointer

    mov	  eax, [esi]				;load the counter into eax
    inc	  eax					;increment the counter
    mov	  [esi], eax				;save the counter
 
    mov	  eax, 2					;return true to main to continue getting numbers
    jmp	  GoodInput				;calculations done return to main to continue

InputError:
	   mov	  edx, OFFSET errorMessage	;load error message
	   call	  WriteString				;print message
	   call	  Crlf				     ;move to the next line
	   inc	  ecx				     ;increment one to continue while loop

    Loop getInput

QuitSum:
    mov	  eax, 1					;return false to main to end and display calculations

GoodInput:

    popf							;restore flag settings

ret						   ;exit procedure
getNumber ENDP

;---------------------------------------------
displayResults PROC USES EAX ECX EDX EBX,
    counterX: DWORD,
    summationX: DWORD
; Displays the results of the summing and average
; Receves: counterX, the number of terms, summationX the sum of terms
; Returns: Nothing
;---------------------------------------------

.data

    countString	BYTE "You entered ",0
    numbers		BYTE " numbers",0

    theSum		BYTE "The sum of your numbers is ",0
    theAverage		BYTE "The average is ",0

.code

    pushf						 ;store flag settings

    call	  Crlf					;seperate the results from the input
    call	  Crlf

;display count
    mov	  edx, OFFSET countString	    ;load the first part of the string
    call	  WriteString				    ;display the string
    mov	  eax, counterX			    ;load the counter
    call	  WriteDec				    ;display the counter
    mov	  edx, OFFSET numbers		    ;load the string
    call	  WriteString				    ;display the string
    call	  Crlf					    ;new line

;display sum
    mov	  edx, OFFSET theSum		    ;load the string
    call	  WriteString				    ;print the string
    
    mov	  eax, summationX			    ;load the sum
    call	  WriteDec				    ;print the sum
    call	  Crlf					    ;new line


;dislplay average
    mov	  edx, OFFSET theAverage		    ;load the string
    call	  WriteString				    ;print the string
    INVOKE  threePrec, summationX, counterX ;call the procedure to display a three decimal precision number

    call Crlf						    ;new line to seprate from the final message

    popf						 ;restore flag settings
ret							 ;exit procedure
displayResults ENDP

;---------------------------------------------
threePrec PROC USES EDX EAX,
    firstNum: DWORD,
    secondNum: DWORD
; Displays a floating point number of a precision of 3 after deviding the two operands
; Receves: first number to be devided by the second number
; Returns: Nothing
;---------------------------------------------

.data

    point			BYTE ".",0
    threeO		BYTE "000",0

    remainderX		DWORD 0
    result		DWORD 0
    temp			DWORD 0

.code

    pushf						 ;store flag settings

    fild	  firstNum				;load first number into the FPU ST(0)
    fidiv	  secondNum				;divide the first number by the second number

    mov	  remainderX, 1000			;store 1000 for use
    fimul	  remainderX				;multiply ST(0) by 1000
    fist	  result					;store the result as a rounded integer in result
    mov	  eax, result				;mov the result to eax
    cdq							;prepair for integer divison
    div	  remainderX				;divide firstNum by 1000
    mov	  result, eax				;store the result
    mov	  remainderX, edx			;store the remainder of the divide by 1000 in remainderX
    
    call	  WriteDec				;write the integer result of the divison
    mov	  edx, OFFSET point			;load the string
    call	  WriteString				;print the decimal point
			
    
;add decimal places if less than 3
    cmp	  remainderX, 0			;If the remainder is not zero jump to adding decimal places
    jne	  addZeros
	   mov	  edx, OFFSET threeO	;load the string
	   call	  WriteString			;display 3 zeros if the remainder is zero
        call	  Crlf
	   call	  Crlf
	   jmp	  done			;jump to the end

addZeros:
    cmp	  remainderX, 10			;if the remainder is less than 10, multiply by 100 increasing precision by two
    ja	  addOneZero
	   mov	 result, 100
	   mov	 eax, remainderX		;put the remainder in eax for multiplication			
	   mul	 result				;multiply the remainder by 100
	   mov	 remainderX, eax	 	;move the results to remainderX
	   jmp	 threeGood			;jump to printing the result

addOneZero:
    cmp	  remainderX, 100			;if the remainder is less than 100, multiply by 10 increasing precision by one
    ja	  threeGood
	   mov	 result, 10
	   mov	 eax, remainderX		;put the remainder in eax for multiplication
	   mul	 result				;multiply the remainder by
	   mov	 remainderX, eax	 	;move the results to remainderX

threeGood:
    mov	  eax, remainderX			;move remainderX to display the reults
    call	  WriteDec				;display the first 3 digits of the floating point number rounded up

done:

    popf						 ;restore flag settings
ret							 ;exit procedure
threePrec ENDP

;---------------------------------------------
farewell PROC USES EDX,
    namePtr:PTR BYTE
; Prints the goodbye message
; Receves: namePtr used to display the name
; Returns: Nothing
;---------------------------------------------

.data

    goodBye	 BYTE    0Dh,0Ah,"Results certified by Jimmy ",0Dh,0Ah,"Goodbye, ",0

.code

    pushf						 ;store flag settings

    mov	  edx, OFFSET goodBye	 ;load message
    call	  WriteString			 ;write message
    mov	  edx, [namePtr]		 ;load name
    call	  WriteString			 ;print name
    call	  Crlf				 ;move to next line for next program

    popf						 ;restore flag settings

ret						  ;exit procedure
farewell ENDP

;---------------------------------------------
clearField PROC USES EAX EDX ECX
; Clears line from the column passed to the end of the row returning the cursor to the begining
; Receves: DL for start postion on the row to clear 
; Returns: Nothing
;---------------------------------------------

.data

.code

    pushf					  ;store flag settings
    mov	  eax, 80			  ;80 columns
    sub	  al, dl			  ;Subtract the starting position from total columns

    mov	  ecx, eax		  ;move the results to ecx
    mov	  al, ' '			  ;print spaces to overwrite the line
clearLine:
    call WriteChar			  ;print the char
    loop ClearLine			  ;loop to the next column

    call Gotoxy			  ;reset the cursor
    popf					  ;restore flag settings

ret					   ;exit procedure
clearField ENDP

;---------------------------------------------
consoleSetup PROC USES EAX
; sets up the console to 80x300 (default buffer) and the window title
; Receves: Nothing 
; Returns: Nothing
;---------------------------------------------

.data

    outHandle	 HANDLE  0				   ;console handle
    bufferSize	 COORD   <80,300>			   ;console buffer
    titleStr	 BYTE    "Integer Accumulator Programed by Jimmy ",0

.code
    
    pushf												   ;store flag settings
    INVOKE  SetConsoleTitle, ADDR titleStr					   ;Sets the window title
    INVOKE  GetStdHandle, STD_OUTPUT_HANDLE					   ;gets the handle
    mov	  outHandle, eax								   ;store the handle
    INVOKE  SetConsoleScreenBufferSize, outHandle, bufferSize	   ;set the console to 80x300 (default)
    popf												   ;restore flag settings

ret												    ;exit procedure
consoleSetup ENDP


END main
