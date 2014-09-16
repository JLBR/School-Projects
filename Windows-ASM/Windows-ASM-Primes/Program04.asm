TITLE Prime     (Program04.asm)

; Author: Jimmy 
; Course CS271 / Project ID  : Assignment 04               Date: 19 Feb 2013
; Description: Takes a users name and echos it
; takes an integer 1-200 and displays a list of prime numbers.

INCLUDE Irvine32.inc

introduction PROTO,
    bufferPtr:PTR BYTE,
    bufferSize:DWORD

getNumber PROTO,
    lowerLimit:DWORD,
    upperLimit:DWORD

writePrime PROTO,
    startRow:DWORD,
    counterPTR:PTR DWORD,
    reference:DWORD,
    prime:DWORD

farewell PROTO,
    namePtr:PTR BYTE

.data

    UPPER_LIMIT EQU 200	   ;Maximum based on assignment specifications
    LOWER_LIMIT EQU 1	   ;Bottom based on assignment specifications

    userName	 BYTE    20  DUP(0)

.code
main PROC
    
    call	  consoleSetup								    ;set the console to 80x300
    INVOKE  introduction,   ADDR userName, SIZEOF userName	    ;Displays the introduction and gets the username
    call	  userInstructions							    ;Displays the instructions
    INVOKE  getNumber,	   LOWER_LIMIT, UPPER_LIMIT		    ;gets the number 1-200 into eax
    call	  displayResults							    ;calculates and displays the results of the prime numbers
    INVOKE  farewell,	   ADDR userName				    ;displays the goodbye message



exit												    ;exit program
main ENDP

;---------------------------------------------
introduction PROC USES ECX EDX EAX,
    bufferPtr:PTR BYTE,
    bufferSize:DWORD
; Prints the program title and progamers name
; Receves: bufferPtr to place the data in, and bufferSize
; Returns: Nothing
;---------------------------------------------

.data

    intro		 BYTE	"Prime Numbers Programed by Jimmy ",0Dh,0Ah,0Dh,0Ah
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

    instructions	BYTE    "Enter the number of prime numbers you would like to see.",0Dh,0Ah
				BYTE    "I can display between 1 to 200 prime numbers.",0Dh,0Ah,0Dh,0Ah,0

.code

    pushf					  		 ;store flag settings

    mov	   edx, OFFSET instructions	 ;load instructions
    call	   WriteString				 ;print instructions

    popf							 ;restore flag settings

ret							 ;exit procedure
userInstructions ENDP

;---------------------------------------------
getNumber PROC USES EDX,
    lowerLimit:DWORD,
    upperLimit:DWORD
; gets a number in a range
; Receves: nothing
; Returns: eax the number
;---------------------------------------------



.data
    
    howMany		BYTE	   "Enter the number of primes to display [1-200]: ",0
    errorMessage	BYTE	   "Please enter a number 1-200",0

.code

    pushf					  		;store flag settings

GetInput:
    mov	  edx,OFFSET howMany		;load prompt
    call	  WriteString				;write prompt
    call	  ReadDec					;read user input into eax

    cmp	  eax, lowerLimit			;if input < LOWER_LIMIT
    jl	  InputError				;Goto error message
    cmp	  eax, upperLimit			;if input > UPPER_LIMIT
    jg	  InputError				;goto error message

    jmp	  GoodInput				;return on a good input

InputError:

	   mov	 edx, OFFSET errorMessage	;load error message
	   call	 WriteString				;print message
	   mov	 dh, 7			 		;row 7
	   mov	 dl, 0		  			;column 0
	   call	 Gotoxy	   			     ;return to the row start
	   call	 clearField			     ;clear the entire line
	   jmp	 GetInput

GoodInput:

    mov	 dh, 8			 		;row 8
    mov	 dl, 0		  			;column 0
    call	 Gotoxy	   			     ;return to the row start
    call	 clearField			     ;clear the entire line

    popf							;restore flag settings

ret						   ;exit procedure
getNumber ENDP

;---------------------------------------------
farewell PROC USES EDX,
    namePtr:PTR BYTE
; Prints the goodbye message
; Receves: namePtr used to display the name
; Returns: Nothing
;---------------------------------------------

.data

    goodBye	 BYTE    0Dh,0Ah,"Results certified by Jimmy",0Dh,0Ah,"Goodbye, ",0

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
displayResults PROC USES ECX EDX
; calculates the prime numbers
; Receves: eax for the number of primes to display
; Returns: Nothing
;---------------------------------------------

.data

    currentTest	DWORD 5
    divisor		DWORD 3
    row	  		DWORD 9
    remainer		BYTE "remainder : ",0
    counter		DWORD 0
    fieldWidth		DWORD 9

.code

    pushf						 ;store flag settings

    mov	  ecx, eax								;set up the counter with the number to display

    INVOKE  WritePrime, row, ADDR counter, fieldWidth, 2	;write the number based on a base row, and a counter
    dec	  ecx									;decrement ecx because no calculation is done for 2 (due to incrmenting by 2)

    cmp	  ecx, 0									;check that the user did not enter 1 for number of primes
    je	  AllDone									;quit if they did

    INVOKE  WritePrime, row, ADDR counter, fieldWidth, 3	;write the number based on a base row, and a counter
    dec	  ecx									;decrement ecx because no calculation is done for 3 (due to incrmenting by 2)

    cmp	  ecx, 0									;check that the user did not enter 2
    je	  AllDone									;quit if they did

TestCandidate:
    mov	  eax, currentTest		 ;load the current value to be tested
    cdq						 ;extend the sign
    div	  divisor				 ;divide it by the current divisor

    cmp	  edx, 0				 ;compare the remainder to zero 
    je	  NotPrime			 ;if it is zero then the number is not prim

    inc	  divisor				 ;Incrment by 2
    inc	  divisor				 ;

    mov	  eax, currentTest		 ;load the current value to be tested
    cmp	  divisor, eax		  	 ;compare the divisor to the tested value
    jae	  PrintResults			 ;if it is equal or greater it is prime
    jmp	  TestCandidate		 ;otherwise try again

NotPrime:
    
    mov	  eax, currentTest		 ;move the current number to eax
    inc	  eax				 ;increase it by 2
    inc	  eax				 ;
    mov	  currentTest, eax		 ;store the updated current test number
    mov	  divisor, 3			 ;reset the divisor to 3
    jmp	  TestCandidate		 ;back to the top

PrintResults:					 
    
    INVOKE  WritePrime, row, ADDR counter, fieldWidth, currentTest

    mov	  divisor, 3			 ;reset the divisor to 3
    mov	  eax, currentTest		 ;move the current number to eax
    inc	  eax				 ;increase it by 2
    inc	  eax				 ;
    mov	  currentTest, eax		 ;store the updated current test number
    
    loop	  TestCandidate		 ;back to the top

AllDone:

    popf						 ;restore flag settings

ret
displayResults ENDP

;---------------------------------------------
writePrime PROC USES EAX EBX ECX EDX ESI,
    startRow:DWORD,
    counterPTR:PTR DWORD,
    reference:DWORD,
    prime:DWORD
; Writes one number to the calculated field based on the absolute row, a counter, and field width
; Receves: No registers
;	    : startRow, the absolute value of the starting row
;	    : counterPTR a pointer to the counter
;	    : reference the field width
;	    : prime the number to print
; Returns: Nothing
;---------------------------------------------

.data

.code

    pushf					  ;store flag settings

    mov	  esi, counterPTR	  ;move the counter address to esi
    mov	  eax, [esi]		  ;move the value of counter to eax
    cdq					  ;extend the sign
    div	  reference		  ;divde by refrence to identify the field start and row start
    add	  eax, startRow	  ;Add the start row to the counter/reference to get the current row
    mov	  ebx, eax		  ;store the result in ebx

    mov	  eax, reference	  ;move the reference
    mul	  edx			  ;multiply the remainder to get the current column (field start)
    
    mov	  dh, bl			  ;load the row
    mov	  dl, al			  ;load the column
    call	  GotoXY			  ;move the cursor to the correct field
    
    mov	  eax, prime		  ;load the prime number
    call	  WriteDec		  ;display the number

    mov	  eax, [esi]		  ;move the counter to eax
    inc	  eax			  ;increment the counter
    mov	  [esi], eax		  ;store the counter

    popf					  ;restore flag settings

ret
writePrime ENDP

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

ret
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
    titleStr	 BYTE    "Prime Numbers Programed by Jimmy",0

.code
    
    pushf												   ;store flag settings
    INVOKE  SetConsoleTitle, ADDR titleStr					   ;Sets the window title
    INVOKE  GetStdHandle, STD_OUTPUT_HANDLE					   ;gets the handle
    mov	  outHandle, eax								   ;store the handle
    INVOKE  SetConsoleScreenBufferSize, outHandle, bufferSize	   ;set the console to 80x300 (default)
    popf												   ;restore flag settings

ret
consoleSetup ENDP


END main
