import sys

def primes(a):
  if a == 1:
    print "The first prime is 1"
    return()
  elif a == 2:
    print "The second prime is 2"
    return()
  elif a == 3:
    print "The third prime is 3"
    return()
  primelist = [3]
  primeindex = 5
  while (len(primelist)+2) != a:
    notprime = 0
    for prime in primelist:
      if primeindex%prime == 0:
        notprime = 1
        break
    if notprime == 0:
       primelist.append(primeindex)
    primeindex += 2
  print "The %d prime number is" %a, (primeindex-2)

def main():
   if len(sys.argv) < 2:
     print ("too few arguments")
     sys.exit(2)
   elif len(sys.argv) >2:
     print ("too many arguments")
     sys.exit(2)
   primes(int(sys.argv[1]))

main()
