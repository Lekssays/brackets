# Submit

Programming Contests Judging Tool  

# Notes  

Electron Documentation: https://github.com/electron/electron/tree/master/docs/api
  
The app uses web-terminal to access the terminal.  
To install use:
  sudo npm install web-terminal -g  
  
To open web-terminal in a port use:  
  web-terminal --port [portNumber]  
  
To manipulate the terminal check the docs: https://github.com/rabchev/web-terminal 


# TODO  
## General
- Get the data in https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js locally and change the link address(~ line 31)  
- Implement run  
- Implement submit
- Improve UI  

## Webviews  
- Change http://127.0.0.1:8088/terminal/ to the corresponding address in the server
- Change webview link to the scoreboard address

## Editor
- Make it possible to change languages in the editor
- Get data from editor (editor.getValue(); // or session.getValue)  

## Dropdown Boxes
- separate the Dropdown selectors  
 

