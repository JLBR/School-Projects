import subprocess
import getopt
import sys

def main():
   try:
      opts, args = getopt.getopt(sys.argv[1:],"hlsufoV")
   except getopt.GetoptError as err:
        print str(err) 
        sys.exit(2)
   cmd = ["w"]
   for option, argvalue in opts:
      if option == "-h":
        cmd.append("-h")
      elif option == "-l":
        cmd.append("-l")
      elif option == "-s":
        cmd.append("-s")
      elif option == "-u":
        cmd.append("-u")
      elif option == "-f":
        cmd.append("-f")
      elif option == "-o":
        cmd.append("-o")
      elif option == "-V":
        cmd.append("-V")
      else:
         print ("invalid arguments")
         sys.exit(2)
   w = subprocess.Popen(cmd, stdout=subprocess.PIPE)
   w_out = w.stdout.readlines()
   for line in w_out:
      print line,

main()
