function saveFile(filename, data) {
    var file = new File(__dirname + '/' + filename);
    file.open("w");
    file.writeln(data);
    file.close();
}

function compileC(filename) {
    var exec = require('child_process').exec, child;
    var ret = '';

child = exec('ls', ret =
    function (error, stdout, stderr) {
        //document.append('stdout: ' + stdout);
        ret += stdout;
        if (error !== null) {
             console.log('exec error: ' + error);
        }
    });
    return ret;
}