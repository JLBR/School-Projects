TITLE Cypher     (EC01.asm)

; Author: Jimmy 
; Course CS271 / Project ID  : Extra Credit 01               Date: 22 Mar 2013
; Description: Takes a file and encyphers or decyphers it


INCLUDE Irvine32.inc

BUFFER_SIZE=500

.data 
    
    keyFilename	BYTE  "key.txt",0 
    inputFilename	BYTE  "message.txt",0 
    outputFilename	BYTE  "output.txt",0 
    messageLength	DWORD  ? 
    keyArray		BYTE   26 DUP(?) 
    decodeArray	BYTE   26 DUP(?) 
    inputMessage	BYTE   BUFFER_SIZE DUP(?) 
    outputMessage	BYTE   BUFFER_SIZE DUP(?)
    userInput		BYTE	  ?

    doneEncode		BYTE "The Contents of your message Have been scrambled and written to output.txt",0
    decyphered		BYTE	"The Contents of your message Have been unscrambled and written to output.txt",0

.code
main PROC
    
    call	  Introduction				;displays the introduction and instructions

    push	  OFFSET userInput			
    call	  MainMenu				;display the main menu and get user input

    call	  CrLf

    push	  OFFSET messageLength
    push	  OFFSET inputMessage
    push	  OFFSET inputFilename
    push	  OFFSET keyFileName
    push	  OFFSET keyArray
    call	  ReadInputFiles			;read the files

    movzx	  eax, userInput			;jump to encypher or decypher
    cmp	  al,'1'
    je	  cypher

decypher:

    push	  OFFSET keyArray
    push	  OFFSET decodeArray
    call	  CreateDecodeArray			;load the decipher key

    push	  OFFSET decodeArray
    push	  OFFSET inputMessage
    push	  OFFSET outputMessage
    push	  messageLength
    call	  Descramble				;decypher

    push	  OFFSET outputMessage
    push	  OFFSET outputFilename
    push	  messageLength
    call	  WriteOutput				;Write the files

    mov	  edx, OFFSET decyphered		;say goodbye
    call	  WriteString
    call	  CrLf

    jmp	  doneM

cypher:

    push	  OFFSET keyArray
    push	  OFFSET inputMessage
    push	  OFFSET outputMessage
    push	  messageLength
    call	  Scramble				;encypher the message

    push	  OFFSET outputMessage
    push	  OFFSET outputFilename
    push	  messageLength
    call	  WriteOutput				;write the output file

    mov	  edx, OFFSET doneEncode		;say goodbye
    call	  WriteString
    call	  CrLf

doneM:

    exit
main ENDP

;---------------------------------------------
MainMenu PROC,; USES EDX EAX ESI
;    userInputPTR:PTR BYTE
; Receves: BYTE OFFSET userInputPTR to store the char from the menu
; Returns: '1',or '2' in userInput
;---------------------------------------------
.data
    Instruction	BYTE "Original message needs to be in message.txt, the key needs to be in key.txt ",0Dh,0Ah
		  		BYTE  "and the output will be in output.txt",0Dh,0Ah
	   			BYTE "Main Menu:",0Dh,0Ah
    				BYTE "[1]Encode Message",0Dh,0Ah
				BYTE "[2]Decode Message",0
				
    userInputPTR	EQU [ebp+8]

.code
    push	  ebp
    mov	  ebp, esp
    push	  esi
    push	  eax
    push	  edx										   ;store regesters
    pushf												   ;store flag settings

    mov	  edx, OFFSET Instruction						   ;load instructions
    call	  WriteString									   ;print instructions

    mov	  esi, userInputPTR								   ;Load the pointer for the return value

GetInput:
    xor	  eax,eax										   ;clean out eax
    call	  ReadChar									   ;get input

    cmp	  al,'1'
    je	  good
    cmp	  al, '2'
    je	  good
    jmp	  GetInput									   ;if the input is not good go back and get good input

Good:
    mov	  [esi],al									   ;store good input

    call	  CrLf

    popf												   ;restore flag settings
    pop	  edx										   ;restore registers
    pop	  eax
    pop	  esi
    pop	  ebp										   
ret 4
MainMenu ENDP

;---------------------------------------------
Introduction PROC; USES EDX
; Receves: Nothing  
; Returns: Nothing
;---------------------------------------------
.data
    intro	  BYTE "Welcome to the TSA Security Program!",0Dh,0Ah
		  BYTE "This program was written by Agent Jimmy ",0Dh,0Ah,0Dh,0Ah,0

.code
    push	  ebp
    mov	  ebp, esp
    push	  edx										   ;store regesters
    pushf												   ;store flag settings

    mov	  edx, OFFSET intro								   ;load the intro
    call	  WriteString									   ;write the intro

    popf												   ;restore flag settings
    pop	  edx										   ;restore registers
    pop	  ebp										   
ret
Introduction ENDP

;---------------------------------------------
ReadKeyFile PROC; USES EAX,ECX,EDX, ESI, EDI
; Receves:  BYTE OFFSET keyArrayPTR
;		  BYTE OFFSET keyFileNamePTR  
; Returns:  keyArrayPTR the key that was read.
;---------------------------------------------
.data
    BadKey		BYTE "Bad key file",0
    keyBuffer		BYTE BUFFER_SIZE DUP(?)
    keyFileHandle	HANDLE ?

    keyArrayPTR	EQU [ebp+8]
    keyFileNamePTR	EQU [ebp+12]

    readingFile	BYTE "Reading key file",0

.code
    push	  ebp
    mov	  ebp, esp
    push	  eax
    push	  ecx
    push	  esi
    push	  edi
    push	  edx										   ;store regesters
    pushf												   ;store flag settings

    mov	  edx, OFFSET readingFile						   ;let the user know the key is being read
    call	  WriteString
    call	  CrLf

    mov	  edx, keyFileNamePTR
    call	  OpenInputFile								   ;open the file for reading

    cmp	  eax, INVALID_HANDLE_VALUE
    jne	  goodKeyFile									   ;quit if the file cant be opened
    mov	  edx, OFFSET BadKey
    call	  WriteString									   ;print error message
    call	  Crlf
	   exit

goodKeyFile:
    mov	  keyFileHandle, eax
    mov	  edx, OFFSET keyBuffer
    mov	  ecx, BUFFER_SIZE
    call	  ReadFromFile									   ;load the buffer with the file
    jnc	  goodRead
    mov	  edx, OFFSET BadKey
    call	  WriteString									   ;print error message if the file could not be read from
    call	  CrLf
    jmp	  badKeyClose

GoodRead:
    cmp	  eax, BUFFER_SIZE
    jb	  goodBuffer
    mov	  edx, OFFSET BadKey
    call	  WriteString									   ;print error message if the buffer is too small
    call	  CrLf
    jmp	  badKeyClose

GoodBuffer:
    mov	  keyBuffer[eax],0								   ;terminate the string

    mov	  edi, keyArrayPTR
    mov	  esi, OFFSET keyBuffer							   

    mov	  ecx, 26
StoreKey:
    LODSB   
    STOSB												   ;store the key
    loop	  storeKey

    popf												   ;restore flag settings
    pop	  edx										   ;restore registers
    pop	  edi
    pop	  esi
    pop	  ecx
    pop	  eax
    pop	  ebp		
    
    mov	  eax, keyFileHandle
    call	  CloseFile

 ret  8 
    
    BadKeyClose:
    mov	  eax, keyFileHandle
    call	  CloseFile
    exit

ReadKeyFile ENDP

;---------------------------------------------
ReadMessageFile PROC; USES EAX,ECX,EDX, ESI, EDI
; Receves:  BYTE OFFSET keyArrayPTR
;		  BYTE OFFSET keyFileNamePTR  
; Returns:  keyArrayPTR the key that was read.
;---------------------------------------------
.data
    BadMessage	 		   BYTE "Bad Message file",0
    MessageBuffer		   BYTE BUFFER_SIZE DUP(?)
    MessageFileHandle	   HANDLE ?

    messagePTR		     EQU [ebp+8]
    messageFileNamePTR	EQU [ebp+12]
    messageLngPTR	     EQU [ebp+16]

    readMessageFileS     BYTE "Reading message file",0

.code
    push	  ebp
    mov	  ebp, esp
    push	  eax
    push	  ecx
    push	  esi
    push	  edi
    push	  edx										   ;store regesters
    pushf												   ;store flag settings

    mov	  edx, OFFSET readMessageFileS
    call	  WriteString									   ;let the user know the file is being read
    call	  CrLf

    mov	  edx, messageFileNamePTR
    call	  OpenInputFile								   ;open the file

    cmp	  eax, INVALID_HANDLE_VALUE
    jne	  goodMessageFile
    mov	  edx, OFFSET BadMessage
    call	  WriteString									   ;let the user know the file is bad or non existant
    call	  Crlf
	   exit

goodMessageFile:
    mov	  MessageFileHandle, eax
    mov	  edx, OFFSET MessageBuffer
    mov	  ecx, BUFFER_SIZE
    call	  ReadFromFile									   ;load the buffer
    jnc	  goodMessageRead
    mov	  edx, OFFSET BadMessage
    call	  WriteString									   ;print an error message if teh read failed
    call	  CrLf
    jmp	  badMessageClose

GoodMessageRead:
    mov	  edi, messageLngPTR
    mov	  [edi], eax
    cmp	  eax, BUFFER_SIZE								   ;check the buffer is not overflowing 
    jb	  goodMessageBuffer
    mov	  edx, OFFSET BadMessage
    call	  WriteString									   ;write an error and quit
    call	  CrLf
    jmp	  badMessageClose

GoodMessageBuffer:
    mov	  MessageBuffer[eax],0							   ;terminate the string
    mov	  edi, messagePTR
    mov	  esi, OFFSET MessageBuffer						   

    mov	  ecx, eax
StoreMessage:
    LODSB   
    STOSB												   ;save the file data
    loop	  storeMessage		  

    mov	  eax, MessageFileHandle
    call	  CloseFile

    popf												   ;restore flag settings
    pop	  edx										   ;restore registers
    pop	  edi
    pop	  esi
    pop	  ecx
    pop	  eax
    pop	  ebp		
 
 ret  12 
    
    BadMessageClose:
    mov	  eax, MessageFileHandle
    call	  CloseFile
    exit

ReadMessageFile ENDP

;---------------------------------------------
ReadInputFiles PROC; USES 
; Receves:  keyArray1PTR		        EQU [ebp+8]
;		  keyFileName1PTR		   EQU [ebp+12]
;		  messageFileName1PTR	   EQU [ebp+16]
;		  message1PTR			   EQU [ebp+20]
;		  messageLng1PTR		   EQU [ebp+24]
; Returns:  KeyArray
;		  Message 
;		  MessageLength
;---------------------------------------------
.data
    keyArray1PTR		   EQU [ebp+8]
    keyFileName1PTR		   EQU [ebp+12]
    messageFileName1PTR	   EQU [ebp+16]
    message1PTR		   EQU [ebp+20]
    messageLng1PTR		   EQU [ebp+24]

.code
    push	  ebp
    mov	  ebp, esp
    pushf												   ;store flag settings

    push	  keyFileName1PTR
    push	  keyArray1PTR
    call	  ReadKeyFile									   ;read the key

    push	  messageLng1PTR
    push	  messageFileName1PTR
    push	  message1PTR
    call	  ReadMessageFile								   ;read the message

    popf												   ;restore flag settings
    pop	  ebp										   
ret 20
ReadInputFiles ENDP

;---------------------------------------------
CreateDecodeArray PROC; USES EAX EBX ECX ESI EDI
; Receves:      decodeArray1PTR		   EQU [ebp+8]
;			 keyArray2PTR			   EQU [ebp+12]  
; Returns: decodeArray
;---------------------------------------------
.data

    decodeArray1PTR		   EQU [ebp+8]
    keyArray2PTR		   EQU [ebp+12]

.code

    push	  ebp
    mov	  ebp, esp
    push	  eax
    push	  ebx
    push	  ecx
    push	  esi
    push	  edi										   ;store regesters
    pushf												   ;store flag settings

    xor	  ebx, ebx
    xor	  eax, eax
    mov	  ebx, 97										   ;97 is lowercase a to be inserted into the first converted index position
    mov	  ecx, 26										   ;26 values in the key

    
    mov	  esi, keyArray2PTR
    mov	  edi, decodeArray1PTR
    
 nextCharr:
    LODSB   
    sub	  al, 97										   ;subtract 97 to get the index for the decode array
    add	  edi, eax									   ;move to the index
    mov	  [edi], bl									   ;load the decipher value
    sub	  edi, eax									   ;move the index back


    inc	  ebx										   ;move to the next letter
    loop	  nextCharr

    popf												   ;restore flag settings
    pop	  edi										   ;restore registers
    pop	  esi
    pop	  ecx
    pop	  ebx
    pop	  eax
    pop	  ebp

ret 8
CreateDecodeArray ENDP

;---------------------------------------------
Scramble PROC; USES EAX, EBX, ECX, EDI, ESI
; Receves:  keyArray4PTR		  EQU [ebp+20]
;		  inputMessage4PTR    EQU [ebp+16]
;		  outputMessage4PTR   EQU [ebp+12]
;		  messageLengthVal    EQU [ebp+8] 
; Returns: outputMessage
;---------------------------------------------
.data
    keyArray4PTR	    EQU [ebp+20]
    inputMessage4PTR    EQU [ebp+16]
    outputMessage4PTR   EQU [ebp+12]
    messageLengthVal    EQU [ebp+8]

.code
    push	  ebp
    mov	  ebp, esp
    push	  eax
    push	  ebx
    push	  ecx
    push	  esi
    push	  edi										   ;store regesters
    pushf												   ;store flag settings

    mov	  ecx, messageLengthVal
    xor	  eax, eax
    mov	  esi, inputMessage4PTR
    mov	  edi, outputMessage4PTR

cypher1:

    LODSB												   ;load the next char in the message
    cmp	  al, 97										   
    jb	  notACharr
    cmp	  al, 122
    ja	  notACharr									   ;if not a-z skip

    sub	  al, 97										   ;subtract 97 to get the key index

    push	  edi
    mov	  edi, keyArray4PTR								   ;load the key
    add	  edi, eax									   ;move to the cypher index

    mov	  al, [edi]									   ;substitue the cypher for the original

    pop	  edi

notAcharr:
    STOSB												   ;store the message
    loop cypher1


    popf												   ;restore flag settings
    pop	  edi										   ;restore registers
    pop	  esi
    pop	  ecx
    pop	  ebx
    pop	  eax
    pop	  ebp
ret 16
Scramble ENDP

;---------------------------------------------
Descramble PROC; USES
; Receves: BYTE OFFSET titleString  
; Returns: Nothing
;---------------------------------------------
.data
        decodeArray5PTR	   EQU [ebp+20]
	   inputMessage5PTR    EQU [ebp+16]
	   outputMessage5PTR   EQU [ebp+12]
	   messageLength5Val   EQU [ebp+8]
.code

    push	  ebp
    mov	  ebp, esp
    push	  eax
    push	  ebx
    push	  ecx
    push	  esi
    push	  edi										   ;store regesters
    pushf												   ;store flag settings

    mov	  ecx, messageLength5Val
    xor	  eax, eax
    mov	  esi, inputMessage5PTR
    mov	  edi, outputMessage5PTR

decypher1:

    LODSB												   ;load the next char in the message
    cmp	  al, 97										   
    jb	  notACharr2
    cmp	  al, 122
    ja	  notACharr2									   ;if not a-z skip

    sub	  al, 97										   ;subtract 97 to get the key index

    push	  edi
    mov	  edi, decodeArray5PTR							   ;load the key
    add	  edi, eax									   ;move to the cypher index

    mov	  al, [edi]									   ;substitue the cypher for the original

    pop	  edi

notAcharr2:
    STOSB												   ;store the message
    loop decypher1


    popf												   ;restore flag settings
    pop	  edi										   ;restore registers
    pop	  esi
    pop	  ecx
    pop	  ebx
    pop	  eax
    pop	  ebp

ret 16
Descramble ENDP

;---------------------------------------------
WriteOutput PROC;USES EAX, ECX, EDX
; Receves: BYTE OFFSET titleString  
; Returns: Nothing
;---------------------------------------------
.data
    outPutMessage6PTR   EQU [ebp+16]
    outputFileNamePTR   EQU [ebp+12]
    messageLenght6VAL   EQU [ebp+8]

    outputFileH	HANDLE ?
    notcreate		BYTE "Unable to create output file",0

.code


    push	  ebp
    mov	  ebp, esp
    push	  eax
    push	  ecx
    push	  edx										   ;store regesters
    pushf												   ;store flag settings

    mov	  edx, outputFileNamePTR
    call	  CreateOutputFile								   ;open the file for writing
    mov	  outputFileH, eax

    cmp	  eax, INVALID_HANDLE_VALUE
    jne	  opened										   
    mov	  edx, OFFSET notcreate
    call	  WriteString									   ;write a message and quit if it did not open
    call	  CrLf
    exit

Opened:

    mov	  edx, outPutMessage6PTR
    mov	  ecx, messageLenght6VAL
    call	  WriteToFile									   ;write the file
    
    call	  CloseFile									   ;close the file

    popf												   ;restore flag settings
    pop	  edx										   ;restore registers
    pop	  ecx
    pop	  edx
    pop	  ebp

ret 12
WriteOutput ENDP

END main
