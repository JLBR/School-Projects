all: prog

prog: bst.o main.o compare.o
	gcc -g -Wall -std=c99 -o prog bst.o compare.o main.o

compare.o: compare.c
	gcc -g -Wall -std=c99 -c compare.c
		
main.o: main.c bst.h
	gcc -g -Wall -std=c99 -c main.c
	
bst.o: bst.c bst.h
	gcc -g -Wall -std=c99 -c bst.c

clean:	
	rm bst.o
	rm main.o
	rm compare.o
	rm prog
