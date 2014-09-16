TITLE Random Array     (Program05.asm)

; Author: Jimmy 
; Course CS271 / Project ID  : Assignment 05               Date: 3 Mar 2013
; Description: Takes integer 10-100 input and
; prints a list of sorted random numbers 100-999

INCLUDE Irvine32.inc

writeNum PROTO,
    startRow:DWORD,
    counterPTR:PTR DWORD,
    reference:DWORD,
    num:DWORD,
    numPerRow:DWORD

.data

    UPPER_LIMIT EQU 200	   ;Maximum based on assignment specifications
    LOWER_LIMIT EQU 10	   ;Bottom based on assignment specifications

    LO_RND	 EQU	100
    HI_RND	 EQU	999

    unsorted	 BYTE "The unsorted random numbers:", 0
    sorted	 BYTE "The sorted list:", 0

    request	 DWORD ?	   ;number of integers to display
    randArray	 DWORD 200 dup(?)

.code
main PROC
    
    call	  consoleSetup								    ;set the console to 80x300
    call	  introduction							 	    ;Displays the introduction

    push	  OFFSET request   							    ;pushes the address to store the number of integers
    call    getNumber							 	    ;gets the number 1-200 into eax

    push	  OFFSET randArray
    push	  request									    ;pushes randArray then request
    call	  fillArray								    ;initilizes the random array


    push	  OFFSET randArray
    push	  request
    push	  OFFSET unsorted							    ;pushes randArray, request, then unsorted
    call	  displayList	   							    ;Displays the unsorted list

    push	  OFFSET randArray
    push	  request									    ;pushes randArra, then request
    call	  sortList								    ;sorts the list

    push	  OFFSET randArray
    push	  request									    ;pushes randArray, then request
    call	  displayMedian							    ;displays the median value

    push	  OFFSET randArray
    push	  request
    push	  OFFSET sorted							    ;Pushes randArray, request, then sorted
    call	  displayList	   							    ;Displays the sorted list

    call	  farewell								    ;displays the goodbye message



exit												    ;exit program
main ENDP

;---------------------------------------------
introduction PROC USES EDX
; Prints the program title and progamers name
; Receves: Nothing
; Returns: Nothing
;---------------------------------------------

.data

    intro		 BYTE	"Sorting Random Integers Programed by Jimmy ",0Dh,0Ah,0Dh,0Ah
			 BYTE	"This program generates random numbers in the range [100-999],"
			 BYTE     " displays the original list, sorts the list and calculates the"
			 BYTE     " median value.  Finally, it displays the list sorted in desecending order.",0

.code

    pushf						 ;store flag settings

    mov	   edx, OFFSET intro	  ;load intro
    call	   WriteString			  ;print string
    call	   Crlf				  ;new line
    call	   Crlf				  ;new line
    
    popf						 ;restore flag settings

ret						  ;exit procedure
introduction ENDP

;---------------------------------------------
getNumber PROC
; gets a number in a range
; Receves: variable address to store the result
; Returns: The value the user entered to the pointer
;---------------------------------------------

returnPTR  EQU [ebp+8]

.code
    push	  ebp
    mov	  ebp, esp				;set EBP
    push	  edx
    push	  eax					;store regesters

    pushf					  		;store flag settings

.data
    
    howMany		BYTE	   "Enter the number of random integers to display [10-200]: ",0
    errorMessage	BYTE	   "Please enter a number 10-200",0

.code

GetInput:
    mov	  edx,OFFSET howMany		;load prompt
    call	  WriteString				;write prompt
    call	  ReadDec					;read user input into eax

    cmp	  eax, LOWER_LIMIT			;if input < LOWER_LIMIT
    jl	  InputError				;Goto error message
    cmp	  eax, UPPER_LIMIT			;if input > UPPER_LIMIT
    jg	  InputError				;goto error message

    jmp	  GoodInput				;return on a good input

InputError:

	   mov	 edx, OFFSET errorMessage	;load error message
	   call	 WriteString				;print message
	   mov	 dh, 10			 		;row 10
	   mov	 dl, 0		  			;column 0
	   call	 Gotoxy	   			     ;return to the row start
	   call	 clearField			     ;clear the entire line
	   jmp	 GetInput				     ;back to the top

GoodInput:

    mov	 dh, 11			 		;row 10
    mov	 dl, 0		  			;column 0
    call	 Gotoxy	   			     ;return to the row start
    call	 clearField			     ;clear the entire line

    push	  ebx					;protect ebx
    mov	  ebx, returnPTR
    mov	  [ebx], eax
    pop	  ebx

    popf							;restore flag settings
    
    pop	  eax
    pop	  edx
    pop	  ebp					;restore settings

ret 4					   ;exit procedure removing 1 DWORD paramiters from the stack
getNumber ENDP

;---------------------------------------------
fillArray PROC
; Fills an array with random numbers
; Receves: an array pointer and the number of numbers to generate
; Returns: the array
;---------------------------------------------

numOfRand	  EQU [ebp+8]
arrayPTR	  EQU [ebp+12]

.code
    push	  ebp
    mov	  ebp, esp	   ;setup ebp
    push	  ecx
    push	  eax
    push	  esi		   ;store registers

.data

.code
    pushf						 ;store flag settings

    call Randomize				 ;randomize

    mov ecx, numOfRand			 ;load the number of numbers
    mov esi, arrayPTR			 ;load the array pointer

InsertRandom:
    
	   mov	  eax, HI_RND		 
	   sub	  eax, LO_RND		 ;reduce eax by the bottom
	   call	  RandomRange		 ;generate 0-high-low range 
	   add	  eax, LO_RND		 ;add lo back in to place a base of 0+low

	   mov	  [esi], eax		 ;add the value to the array
	   add	  esi, TYPE DWORD	 ;incrment the array by one

    Loop InsertRandom
    
    popf						 ;restore flag settings

    pop	  esi
    pop	  eax
    pop	  ecx
    pop	  ebp				 ;restore the registers

ret 8					 ;exit procedure removing 2 paramaters from the stack
fillArray ENDP

;---------------------------------------------
displayList PROC
; Prints the program title and progamers name
; Receves: bufferPtr to place the data in, and bufferSize
; Returns: Nothing
;---------------------------------------------

    titlePTR	 EQU [ebp+8]
    numOfRnd	 EQU [ebp+12]
    RndArrayPTR EQU [ebp+16]

    FIELD_WIDTH EQU 8
    
    counter	 EQU DWORD PTR [ebp-4]

.code
    push	  ebp
    mov	  ebp, esp	   ;setup ebp
    sub	  esp, 4		   ;make room for 1 local variable
    push	  edx
    push	  eax		   ;store regesters


.data
    stdHandle	 HANDLE  ?
    screenInfo	 CONSOLE_SCREEN_BUFFER_INFO <>
    cursorPos	 COORD <>
.code
    pushf						 ;store flag settings

    mov	  edx, [titlePTR]		 ;load the title
    call	  WriteString			 ;display the title
    call	  Crlf


    INVOKE  GetStdHandle, STD_OUTPUT_HANDLE					   ;get buffer handle
    mov	  stdHandle, eax								   ;save handle
    INVOKE  GetConsoleScreenBufferInfo, stdHandle, ADDR screenInfo  ;get screen info
    mov	  eax, screenInfo.dwCursorPosition					   ;extract cursor position
    mov	  cursorPos, eax								   ;store cursor positon

    mov	  esi, RndArrayPTR		;load the array	    
    mov	  ecx, numOfRnd		;load the number of numbers
    mov	  counter, 0;			;set counter to 0


DisplayArray:

	   INVOKE	  writeNum, cursorPos.Y, ADDR counter, 8, [esi], 10  ;write the number too the screen
	   add	  esi, TYPE DWORD							   ;move to the next number in the array

    loop DisplayArray

    call Crlf
    call Crlf 

    popf						 ;restore flag settings

    pop	  eax
    pop	  edx
    mov	  esp, ebp			 ;remove locals
    pop	  ebp				 ;restore regesters
   
ret 8					 ;exit procedure removing 3 paramater from the stack
displayList ENDP

;---------------------------------------------
displayMedian PROC
; Calculates then displays the median of an array of integers
; Receves: array pointer and number of elements in the array
; Returns: Nothing
;---------------------------------------------

    numOfRnd	 EQU [ebp+8]
    RndArrayPTR EQU [ebp+12]

.code
    push	  ebp
    mov	  ebp, esp	   ;setup ebp
    push	  edx
    push	  eax		   ;store regesters

.data				   ;added after setting up ebp for ease of calculation
    medSt	  BYTE "The median is ", 0
    tempX	  DWORD ?
.code
    pushf						 ;store flag settings

    mov	  esi, RndArrayPTR		 ;load the array

    mov	  eax, numOfRnd		 ;load the number of elements
    sub	  eax, 1				 ;subtract 1 to adjust for the array range 0-x
    cdq
    mov	  ebx, 2
    div	  ebx				 ;divide by 2 to find the mid point

    mov	  tempX, edx			 ;store the remainder

    mov	  ebx, TYPE DWORD		 ;load the size of an element
    mul	  ebx				 ;multiply the mid point element to find the corrected mid point
    add	  esi, eax			 ;mov to the midpoint
    mov	  eax, [esi]			 ;load the mid point

    cmp	  tempX, 0			 
    je	  oneMed				 ;if the remainder is 1 then there is only one mid point

	   add esi, TYPE DWORD
	   add eax, [esi]			 ;load the second integer to average for the median with 2 mid points
	   cdq
	   mov ebx, 2
	   div ebx

oneMed:
    mov	  edx, OFFSET medSt		 ;load the string
    call	  WriteString
    call	  WriteDec			 ;write the median stored in eax
    call	  Crlf
    call	  Crlf

    
    popf						 ;restore flag settings

    pop	  eax
    pop	  edx
    pop	  ebp				 ;restore regesters

ret 8					 ;exit procedure removing 2 paramater from the stack
displayMedian ENDP

;---------------------------------------------
sortList PROC
; Uses a selection sort to sort a list, then flips the list backwards
; Receves: array pointer, number of elements in the array
; Returns: Sorted array
;---------------------------------------------

    numOfRnd	 EQU [ebp+8]
    RndArrayPTR EQU [ebp+12]

.code
    push	  ebp
    mov	  ebp, esp	   ;setup ebp
    push	  edx
    push	  eax		   ;store regesters

.data
    index		 DWORD 0	   ;i
    index2	 DWORD ?	   ;j
    index3	 DWORD ?	   ;k

.code
    pushf						 ;store flag settings

OuterLoop:

    mov	  esi, RndArrayPTR		 ;load array pointer array[0]

    mov	  eax, index			 
    mov	  index2, eax
    mov	  index3, eax			 ;load indexes
    inc	  index2				 ;index2 = index +1
    
    mov	 ebx, TYPE DWORD
    mul	 ebx					 ;eax = index *4
    add	 esi, eax				 ;move esi to index*4
    mov	 eax, [esi]			 ;eax = array[index]

InnerLoop:

	   add	  esi, TYPE DWORD	 ;array[index2+1]
	   cmp	  eax, [esi]		 ;compare array[index]<array[index2]
	   jl	  Inner2			 ;jump if <

		  mov	eax, [esi]  
		  mov	ebx, index2	 ;set index 3 = 2
		  mov	index3, ebx

Inner2:
	   inc	  index2			 ;index2++
	   mov	  ebx, numOfRnd
	   cmp	  index2, ebx
	   jb	  InnerLoop		 ;jump if index2<request

	   mov	  eax, index2		 ;reset eax

	   push	 RndArrayPTR
	   push	 index3
	   push	 index
	   call	 exchange			 ;exchange index 3 and 1 on RndArrayPTR

    inc	  index				 ;index++
    mov	  eax, numOfRnd
    sub	  eax, 1
    cmp	  index, eax
    jb	  OuterLoop			 ;jump if index<request
   
    mov	  eax, numOfRnd
    cdq
    mov	  ebx, 2
    div	  ebx				 ;calculate 50% of the array to loop though

    mov	  ecx, eax

    mov	  index, 0			 ;set the index to the beginning
    mov	  eax, numOfRnd
    mov	  index2, eax
    dec	  index2				 ;set index2 to the end-1

ReverseLoop:
	   push	 RndArrayPTR
	   push	 index2
	   push	 index
	   call	 exchange			 ;swap begining with last

	   dec	 index2
	   inc	 index
	   loop	 ReverseLoop

    popf						 ;restore flag settings

    pop	  eax
    pop	  edx
    pop	  ebp				 ;restore regesters

ret 8					 ;exit procedure removing 2 paramater from the stack
sortList ENDP

;---------------------------------------------
exchange PROC
; Swaps 2 elements of an array
; Receves: array pointer, and 2 values
; Returns: array
;---------------------------------------------

    k		 EQU [ebp+8]
    i		 EQU [ebp+12]
    RndArrayPTR EQU [ebp+16]

.code
    push	  ebp
    mov	  ebp, esp	   ;setup ebp
    push	  edx
    push	  eax		   ;store regesters

.data
    firstVal	 DWORD   ?
    secondVal	 DWORD   ?
.code
    pushf						 ;store flag settings

    mov	  esi, RndArrayPTR		 ;load array

    mov	  eax, k				 
    mov	  ebx, TYPE DWORD
    mul	  ebx
    add	  esi, eax			 ;mov to the point in the array to swap

    mov	  eax, [esi]
    mov	  firstVal, eax		 ;store the value before overwriting

    mov	  esi, RndArrayPTR

    mov	  eax, i
    mov	  ebx, TYPE DWORD
    mul	  ebx
    add	  esi, eax			 ;move to the second point in the array

    mov	  eax, [esi]
    mov	  secondVal, eax		 ;store the value

    mov	  eax, firstVal
    mov	  [esi], eax			 ;overwrite the second value with the first

    mov	  esi, RndArrayPTR

    mov	  eax, k
    mov	  ebx, TYPE DWORD
    mul	  ebx
    add	  esi, eax			 ;go back to the first location
    
    mov	  eax, secondVal
    mov	  [esi], eax			 ;overwite it with the second value

    popf						 ;restore flag settings

    pop	  eax
    pop	  edx
    pop	  ebp				 ;restore flags

ret 12					 ;exit procedure removing 3 paramater from the stack
exchange ENDP

;---------------------------------------------
temp PROC
; Template unsed
; Receves: Nothing
; Returns: Nothing
;---------------------------------------------


.code
    push	  ebp
    mov	  ebp, esp
    push	  edx
    push	  eax

.data

.code
    pushf						 ;store flag settings


    
    popf						 ;restore flag settings

    pop	  eax
    pop	  edx
    pop	  ebp

ret 4					 ;exit procedure removing 1 paramater from the stack
temp ENDP

;---------------------------------------------
farewell PROC USES EDX
; Prints the goodbye message
; Receves: namePtr used to display the name
; Returns: Nothing
;---------------------------------------------

.data

    goodBye	 BYTE    0Dh,0Ah,"Results certified by Jimmy ",0

.code

    pushf						 ;store flag settings

    mov	  edx, OFFSET goodBye	 ;load message
    call	  WriteString			 ;write message
    call	  Crlf				 ;move to next line for next program

    popf						 ;restore flag settings

ret						  ;exit procedure
farewell ENDP

;---------------------------------------------
writeNum PROC USES EAX EBX ECX EDX ESI,
    startRow:DWORD,
    counterPTR:PTR DWORD,
    reference:DWORD,
    num:DWORD,
    numPerRow:DWORD
; Writes one number to the calculated field based on the absolute row, a counter, and field width
; Receves: No registers
;	    : startRow, the absolute value of the starting row
;	    : counterPTR a pointer to the counter
;	    : reference the field width
;	    : numPerRow the number to display per row
;	    : num the number to print
; Returns: Nothing
;---------------------------------------------

.data

.code

    pushf					  ;store flag settings

    mov	  esi, counterPTR	  ;move the counter address to esi
    mov	  eax, [esi]		  ;move the value of counter to eax
    cdq					  ;extend the sign
    div	  numPerRow		  ;divde by refrence to identify the field start and row start
    add	  eax, startRow	  ;Add the start row to the counter/reference to get the current row
    mov	  ebx, eax		  ;store the result in ebx

    mov	  eax, reference	  ;move the reference
    mul	  edx			  ;multiply the remainder to get the current column (field start)
    
    mov	  dh, bl			  ;load the row
    mov	  dl, al			  ;load the column
    call	  GotoXY			  ;move the cursor to the correct field
    
    mov	  eax, num		  ;load the number
    call	  WriteDec		  ;display the number

    mov	  eax, [esi]		  ;move the counter to eax
    inc	  eax			  ;increment the counter
    mov	  [esi], eax		  ;store the counter

    popf					  ;restore flag settings

ret
writeNum ENDP

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
    titleStr	 BYTE    "Sorting Random Integers Programed by Jimmy ",0

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
