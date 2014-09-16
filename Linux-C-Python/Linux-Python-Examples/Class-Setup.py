import os
import getopt
import sys

def make_dir(rootdir, newfolder):
   tempdir=rootdir+"/"+newfolder
   if not os.path.exists(tempdir):
      os.makedirs(tempdir)
      print tempdir + " has been added"
   else:
      print tempdir + " already exist"

def make_link(slink, dlink):
   if not os.path.lexists(dlink):
      os.symlink(slink, dlink)
      print "added link to "+ slink
   else:
     print "linkt to "+slink+" already exists"

def main():
   if len(sys.argv) < 5:
     print ("too few arguments")
     sys.exit(2)
   elif len(sys.argv) >5:
     print ("too many arguments")
     sys.exit(2)

   try:
      opts, args = getopt.getopt(sys.argv[1:],"c:t:", ["class=","term="])
   except getopt.GetoptError as err:
        print str(err) 
        sys.exit(2)

   for option, argvalue in opts:
      if option in ("-c","--class"):
        classn = argvalue
      elif option in ("-t","--term"):
        term = argvalue
      else:
         print ("invalid arguments")
         sys.exit(2)

   tempdir = os.path.expanduser("~")
   make_dir(tempdir, term)
   tempdir += "/"+term
   make_dir(tempdir, classn)
   tempdir +="/"+classn

   make_dir(tempdir, "assignments")
   make_dir(tempdir, "examples")
   make_dir(tempdir, "lecture_notes")
   make_dir(tempdir, "submissions")
   make_dir(tempdir, "exams")
   
   templinkdir = "/usr/local/classes/eecs/"+term+"/"+classn
   make_link((templinkdir+"/README"),tempdir+"/README")
   make_link((templinkdir+"/src"),tempdir+"/class_src")

main()

