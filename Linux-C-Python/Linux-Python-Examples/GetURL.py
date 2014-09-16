import os
import getopt
import sys
import urllib2

def main():
   if len(sys.argv) < 3:
     print ("too few arguments")
     sys.exit(2)
   elif len(sys.argv) >3:
     print ("too many arguments")
     sys.exit(2)
   source = sys.argv[1]
   destination = sys.argv[2]
   print "Opening "+source
   webpage = urllib2.urlopen("http://"+source)
   fp = open(destination, "w")
   if fp == -1:
      print "file failed to open"
      sys.exit(2)
   for nextchar in webpage.read():
      bw = fp.write(nextchar) 
      if bw == -1:
         print "file failed to write"
         sys.exit(2)
   print (source+" written to "+destination)
   if(fp.close != 0):
      print "file failed to close"
      sys.exit(2)

main()
