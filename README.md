# Webshell
Server-side secure webshell 

Connection authentication must be done with htaccess file

Compile exec_cmd.c with `gcc -Wall -Wextra exec_cmd.c -o exec_cmd`
The binary user must be Root and the group must the same as your web server `chown root:www-data exec_cmd`
Then setuid it `chmod 4010`