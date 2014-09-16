#include <signal.h>
#include <stdio.h>
#include <stdlib.h> 

void handler(int sig)
{

   if(sig == SIGUSR1){
      printf("Caught SIGUSR1\n");
   }else if(sig == SIGUSR2){
      printf("Caught SIGUSR2\n");
   }else{
      printf("Caught SIGINT, exiting\n");
      exit(0);
   }
}

int main(){

   if(signal(SIGINT, handler) == SIG_ERR)
      printf("Error SIGINT handler");
   if(signal(SIGUSR1, handler) == SIG_ERR)
      printf("Error SIGUSR1 handler");
   if(signal(SIGUSR2, handler) == SIG_ERR)
      printf("Error SIGUSR2 handler");   

   raise(SIGUSR1);
   raise(SIGUSR2);
   raise(SIGINT);

  return 0;
}



