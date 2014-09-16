CFLAGS = -Wall -fpic -coverage -lm
GCOVFLAG = -fprofile-arcs -ftest-coverage

DOMDIR = ./
GCOVDIR = gcovResults/

rngs.o: $(DOMDIR)rngs.h $(DOMDIR)rngs.c
	@ gcc -c $(DOMDIR)rngs.c -g  $(CFLAGS)

dominion.o: $(DOMDIR)dominion.h $(DOMDIR)dominion.c rngs.o
	@ gcc -c $(DOMDIR)dominion.c -g  $(CFLAGS) $(GCOVFLAG)



testUtility.o: testUtility.c testUtility.h
	@ gcc -c testUtility.c $(CFLAGS)

conformanceTest.o: conformanceTest.h conformanceTest.c
	gcc -c conformanceTest.c $(CFLAGS)

conformanceTest: dominion.o rngs.o conformanceTest.o testUtility.o
	gcc -o conformanceTest dominion.o rngs.o conformanceTest.o testUtility.o $(CFLAGS)

systemTest.o: systemTest.c 
	gcc -c systemTest.c $(CFLAGS)

systemTest: dominion.o rngs.o systemTest.o 
	gcc -o systemTest dominion.o rngs.o systemTest.o  $(CFLAGS)



unittest1.o: unittest1.c dominion.o testUtil.o
	@ gcc -c unittest1.c $(CFLAGS)

unittest2.o: unittest2.c dominion.o testUtil.o
	@ gcc -c unittest2.c $(CFLAGS)

unittest3.o: unittest3.c dominion.o testUtil.o
	@ gcc -c unittest3.c $(CFLAGS)

unittest4.o: unittest4.c dominion.o testUtil.o
	@ gcc -c unittest4.c $(CFLAGS)

cardtest1.o: cardtest1.c dominion.o testUtil.o
	@ gcc -c cardtest1.c $(CFLAGS)

cardtest2.o: cardtest2.c dominion.o testUtil.o
	@ gcc -c cardtest2.c $(CFLAGS)

cardtest3.o: cardtest3.c dominion.o testUtil.o
	@ gcc -c cardtest3.c $(CFLAGS)

cardtest4.o: cardtest4.c dominion.o testUtil.o
	gcc -c cardtest4.c $(CFLAGS)

unittest1: unittest1.o dominion.o testUtil.o
	@gcc -o unittest1 dominion.o rngs.o unittest1.o testUtil.o $(CFLAGS)

unittest2: unittest2.o dominion.o testUtil.o
	@ gcc -o unittest2 dominion.o rngs.o unittest2.o testUtil.o $(CFLAGS)

unittest3: unittest3.o dominion.o testUtil.o
	@ gcc -o unittest3 dominion.o rngs.o unittest3.o testUtil.o $(CFLAGS)

unittest4: unittest4.o dominion.o testUtil.o
	@gcc -o unittest4 dominion.o rngs.o unittest4.o testUtil.o $(CFLAGS)

cardtest1: cardtest1.o dominion.o testUtil.o
	@gcc -o cardtest1 dominion.o rngs.o cardtest1.o testUtil.o $(CFLAGS)

cardtest2: cardtest2.o dominion.o testUtil.o
	@ gcc -o cardtest2 dominion.o rngs.o cardtest2.o testUtil.o $(CFLAGS)

cardtest3: cardtest3.o dominion.o testUtil.o
	@ gcc -o cardtest3 dominion.o rngs.o cardtest3.o testUtil.o $(CFLAGS)

cardtest4: cardtest4.o dominion.o testUtil.o
	gcc -o cardtest4 dominion.o rngs.o cardtest4.o testUtil.o $(CFLAGS)

testdominion.o: dominion.o rngs.o testdominion.c
	gcc -c testdominion.c $(CFLAGS)

testdominion: testdominion.c dominion.o testdominion.o rngs.o
	gcc -o testdominion testdominion.o rngs.o dominion.o $(CFLAGS)

unittestresults.out: unittest1.c unittest2.c unittest3.c unittest4.c cardtest1.c cardtest2.c cardtest3.c cardtest4.c testUtil.c dominion.o
	@ make clean
	@ rm -f unittestresults.out

	@ make unittest1
	@ echo Running Test 1
	@./unittest1>>unittestresults.out
	@ echo Test 1 complete
	@ gcov dominion.o>>unittestresults.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.u1
	@ make clean

	@ make unittest2
	@ echo Running Test 2
	@./unittest2>>unittestresults.out
	@ echo Test 2 complete
	@ gcov dominion.o>>unittestresults.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.u2
	@ make clean

	@ make unittest3
	@ echo Running Test 3
	@./unittest3>>unittestresults.out
	@ echo Test 3 complete
	@ gcov dominion.o>>unittestresults.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.u3
	@ make clean

	@ make unittest4
	@ echo Running Test 4
	@./unittest4>>unittestresults.out
	@ echo Test 4 complete
	@ gcov dominion.o>>unittestresults.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.u4
	@ make clean

	@ make cardtest1
	@ echo Running Test 5
	@./cardtest1>>unittestresults.out
	@ echo Test 5 complete
	@ gcov dominion.o>>unittestresults.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.c1
	@ make clean

	@ make cardtest2
	@ echo Running Test 6
	@./cardtest2>>unittestresults.out
	@ echo Test 6 complete
	@ gcov dominion.o>>unittestresults.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.c2
	@ make clean

	@ make cardtest3
	@ echo Running Test 7
	@./cardtest3>>unittestresults.out
	@ echo Test 7 complete
	@ gcov dominion.o>>unittestresults.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.c3
	@ make clean

	@ make cardtest4
	@ echo Running Test 8
	@./cardtest4>>unittestresults.out
	@ echo Test 8 complete
	@ gcov dominion.o>>unittestresults.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.c4
	@ make clean

randomtestcard.o: randomtestcard.c dominion.o testUtil.o
	gcc -c randomtestcard.c $(CFLAGS)
	
randomtestcard: randomtestcard.o dominion.o testUtil.o
	gcc -o randomtestcard dominion.o rngs.o randomtestcard.o testUtil.o $(CFLAGS)

randomtestadventurer.o: randomtestadventurer.c dominion.o testUtil.o
	gcc -c randomtestadventurer.c $(CFLAGS)
	
randomtestadventurer: randomtestadventurer.o dominion.o testUtil.o
	gcc -o randomtestadventurer dominion.o rngs.o randomtestadventurer.o testUtil.o $(CFLAGS)

randomtestcard.out: randomtestcard.c dominion.o testUtil.o
	@ make randomtestcard
	@ echo Running randomtestcard
	@./randomtestcard>>randomtestcard.out
	@ echo Test 8 complete
	@ gcov dominion.o>>randomtestcard.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.rt
	@ make clean

randomtestadventurer.out: randomtestadventurer.c dominion.o testUtil.o
	@ make randomtestadventurer
	@ echo Running randomtestadventurer
	@./randomtestadventurer>>randomtestadventurer.out
	@ echo Test 8 complete
	@ gcov dominion.o>>randomtestadventurer.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.ra.txt
	@ make clean

testdominion.out: testdominion.c dominion.o testUtil.o
	@ make testdominion
	@ echo Running testdominion
	@./testdominion>>testdominion.out
	@ gcov dominion.o>>testdominion.out
	@ cp dominion.c.gcov $(GCOVDIR)dominion.c.gcov.domTest.txt
	@ make clean

testdominion2.out: testdominion.c dominion.o rngs.o
	echo "Random tester that plays complete games of dominion:" > testdominion.out
	echo "testdominion.c:" >> testdominion.out
	gcc -o testdominion -g testdominion.c dominion.o rngs.o $(CFLAGS)
	./testdominion >> testdominion.out
	gcov dominion.c >> testdominion.out
	cp dominion.c.gcov dominion.c.gcov.11

all: 

clean:
	rm -f *.o unittest1 unittest2 unittest3 unittest4 cardtest1 cardtest2 cardtest3 cardtest4 testSuite randomtestcard randomtestadventurer *.gcov *.gcda *.gcno *.so *.*~ *~
