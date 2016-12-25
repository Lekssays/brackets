const {app, BrowserWindow} = require('electron')
app.commandLine.appendSwitch('')

 //var http        = require("http"),
   //     terminal    = require("web-terminal");

app.on('ready', function() {
    let mainWindow = new BrowserWindow({
        minWidth: 1250,
        minHeight: 800
    })
    mainWindow.loadURL('file://' + __dirname + '/index.html')
    mainWindow.openDevTools()
})