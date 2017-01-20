const {app, BrowserWindow} = require('electron')
const exec = require('child_process').exec;
global.sharedObj = {exec};

app.commandLine.appendSwitch('')

app.on('ready', function() {
    let mainWindow = new BrowserWindow({
        minWidth: 1250,
        minHeight: 820
    })
    mainWindow.loadURL('file://' + __dirname + '/index.html')
    mainWindow.openDevTools()
})