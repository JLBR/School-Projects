TITLE Fibonacci     (Program02.asm)

; Author: Jimmy 
; Course CS271 / Project ID  : Assignment 02               Date: 24 Jan 2013
; Description: Takes a users name and echos it
; takes an integer 1-46 and displays a list of fibonacci numbers.

INCLUDE Irvine32.inc

introduction PROTO, 
    bufferPtr:PTR BYTE,
    bufferSize:DWORD

farewell PROTO,
    namePtr:PTR BYTE

    UPPER_LIMIT EQU 46	;Maximum fib number based on DWORD
    LOWER_LIMIT EQU 1	;Bottom based on assignment specifications
				     ;(f(0), and f(1) result in 1 so 0 should be the bottom limit)

.data

    userName	 BYTE    20 DUP(0)

.code
main PROC
    
    call	  consoleSetup								;sets the console to default for correct functioning
    INVOKE  introduction, ADDR userName, SIZEOF userName	;prints the intro and gets the user's name
    call	  userInstructions							;prints instructions
    call	  getUserData								;gets the number of fib numbers to display returned using eax
    call	  displayFibs								;uses eax to determin the number of fib numbers to write
    INVOKE  farewell, ADDR userName					;says goodbye

exit												;exit program
main ENDP

;---------------------------------------------
introduction PROC USES ECX EDX EAX,
    bufferPtr:PTR BYTE,
    bufferSize:DWORD
; Prints the program title and progamers name
; Receves: bufferPtr to place the data in, and bufferSize
; Returns: esi holds the pointer to the string
;---------------------------------------------

.data

    intro		 BYTE	"Fibonacci Numbers",0Dh,0Ah,"Programed by Jimmy ",0Dh,0Ah,0Dh,0Ah
			 BYTE	"What's your name? ",0
    welcome	 BYTE	"Welcome, ",0

    byteCount	 DWORD	?		  ;size counter


.code

    mov	   edx, OFFSET intro	  ;load intro
    call	   WriteString			  ;print string

GetUserName:

    mov	   edx, bufferPtr		  ;prepair buffer for input
    mov	   ecx, bufferSize		  ;set the maximum size to put in the buffer
    call	   ReadString			  ;Gets user input as a string
    mov	   byteCount, eax		  ;length of useable string

    mov	   dh, 3				  ;row 3
    mov	   dl, 18				  ;collum 18
    call	   Gotoxy				  ;place the cursor one space past the question mark

	   cmp	   byteCount, 0		  ;if byteCount = empty string
	   je	   GetUserName			  ;then go back and get the users name

    call	   Crlf
    mov	   edx, OFFSET welcome	  ;load welcome
    call	   WriteString			  ;Print welcome
    mov	   edx, [bufferPtr]		  ;move user name to print
    call	   WriteString			  ;Print user name
    call	   Crlf
    call	   Crlf

ret						  ;exit procedure
introduction ENDP

;---------------------------------------------
userInstructions PROC USES EDX
; Prints the instructions
; Receves: Nothing
; Returns: Nothing
;---------------------------------------------

.data

    instructions	BYTE    "Enter the number of Fibonacci terms to be displayed",0Dh,0Ah
				BYTE    "Please select from 1-46",0Dh,0Ah,0Dh,0Ah,0

.code

    mov	   edx, OFFSET instructions	 ;load instructions
    call	   WriteString				 ;print instructions

ret
userInstructions ENDP

;---------------------------------------------
getUserData PROC USES EBX EDX ECX
; Gets the number of fib numbers to display
; Receves: nothing
; Returns: eax - the number of fib to display
;---------------------------------------------

.data
    
    howMany		BYTE	   "How many Fibonacci terms do you want? ",0
    errorMessage	BYTE	   "Please enter a number 1-46",0

.code

    mov	  edx,OFFSET howMany		;load prompt
    call	  WriteString				;write prompt
    mov	  ecx, 1					;do once

GetInput:
    call	  ReadDec					;read user input into eax

    mov	  dh, 10					;row 10
    mov	  dl, 0					;collum 0
    call	  Gotoxy					;goto the start of the next line
    call	  ClearField				;clear old error messages

    cmp	  eax, UPPER_LIMIT			;if input > UPPER_LIMIT
    ja	  InputError				;Goto error message
    cmp	  eax, LOWER_LIMIT			;if input < LOWER_LIMIT
    jb	  InputError				;goto error message
    jmp	  GoodInput				;else its good go to end

InputError:
	   mov	  edx, OFFSET errorMessage	;load error message
	   call	  WriteString				;print message
	   mov	  dh, 9					;row 9
	   mov	  dl, 38					;collum 38
	   call	  Gotoxy					;place the cursor one space past the question mark
	   call	  ClearField				;Clears to the end of the line and returns the cursor to the original spot
	   
	   inc	  ecx				     ;increment one to continue while loop

GoodInput:

    Loop getInput


    call	  Crlf

ret
getUserData ENDP

;---------------------------------------------
displayFibs PROC USES EAX ECX EDX EBX
; Displays the fib numbers in 5 columns per row
; Receves: eax - the number of fibs to display
; Returns: Nothing
;---------------------------------------------

.data
    
    LEFT_BUFFER EQU	5		  ;space buffer from the left side of the screen

    row	  BYTE	11		  ;starting row
    coll	  BYTE	LEFT_BUFFER ;starting coll

    spacing		BYTE   15	  ;with 80 colls -5 buffer = 75/5 = 15
    firstFib		DWORD   1	  ;fib f(0)
    secondFib		DWORD   0	  ;fib f(1)

.code

    mov ecx, eax		;move the number of fibs to ecx to loop

RowsLoop:
    
    cmp	  ecx, 5	    ;if ecx <= 5	 
    jbe	  LessThan5   ;then go to LessThan5
    sub	  ecx, 5	    ;else ecx = ecx-5 (5 loops per row)
    push	  ecx	    ;store the outer loop counter
    mov	  ecx, 5	    ;set the inner loop counter to 5
    jmp	  MoreThan5   ;skip the less than 5 section

    LessThan5:
	   mov	  eax, ecx   ;store inner counter in eax
	   mov	  ecx, 0	   ;set outer counter to 0
	   push	  ecx	   ;store outer counter
	   mov	  ecx, eax   ;move inner counter back to ecx

MoreThan5:

CollsLoop:
    
	   mov	  dh, row			 ;print row
	   mov	  dl, coll		 ;print coll
	   call	  Gotoxy			 ;move cursor to print location

	   mov	  eax, firstFib	 ;move the number to be printed
	   call	  WriteDec		 ;print the number

	   add	  dl, spacing		 ;add 15 to colls
	   mov	  coll, dl		 ;store the new print field location

	   add	  eax, secondFib	 ;f(n+1)
	   mov	  ebx, firstFib	 ;move f(n) to ebx
	   mov	  secondFib, ebx	 ;move f(n) to secondFib
	   mov	  firstFib, eax	 ;move f(n+1) to firstFib

	   loop	 CollsLoop		 ;loop until done with row

    pop	  ecx			  ;load the outer loop counter
    inc	  ecx			  ;increment one to corect for the outer loop
    
    inc	  row			  ;move down a row
    mov	  coll, LEFT_BUFFER	  ;reset coll

    Loop RowsLoop			  ;loop untill done  

    call Crlf
ret
displayFibs ENDP

;---------------------------------------------
farewell PROC USES EDX,
    namePtr:PTR BYTE
; Prints the goodbye message
; Receves: Nothing
; Returns: Nothing
;---------------------------------------------

.data

    goodBye	 BYTE    0Dh,0Ah,"Results certified by Jimmy ",0Dh,0Ah,"Goodbye, ",0

.code
    
    mov	  edx, OFFSET goodBye	 ;load message
    call	  WriteString			 ;write message
    mov	  edx, [namePtr]		 ;load name
    call	  WriteString			 ;print name
    call	  Crlf				 ;move to next line for next program

ret
farewell ENDP

;---------------------------------------------
clearField PROC USES EAX EDX ECX
; Clears line from the column passed to the end of the row returning the cursor to the begining
; Receves: DL for start postion on the row to clear 
; Returns: Nothing
;---------------------------------------------

.data

.code

    mov	  eax, 80			  ;80 columns
    sub	  al, dl			  ;Subtract the starting position from total columns

    mov	  ecx, eax		  ;move the results to ecx
    mov	  al, ' '			  ;print spaces to overwrite the line
clearLine:
    call WriteChar			  ;print the char
    loop ClearLine			  ;loop to the next column

    call Gotoxy			  ;reset the cursor

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
    titleStr	 BYTE    "Fibonacci Numbers Programed by Jimmy ",0

.code

    INVOKE  SetConsoleTitle, ADDR titleStr					   ;Sets the window title
    INVOKE  GetStdHandle, STD_OUTPUT_HANDLE					   ;gets the handle
    mov	  outHandle, eax								   ;store the handle
    INVOKE  SetConsoleScreenBufferSize, outHandle, bufferSize	   ;set the console to 80x300 (default)

ret
consoleSetup ENDP


END main
