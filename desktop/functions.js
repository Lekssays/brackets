/* TODO: Fix the coding style to follow:
 https://github.com/electron/electron/blob/master/docs/development/coding-style.md
*/

function getDataFromEditor () {
    return ace.edit("editor").getValue();
}

function saveFile (filename) {
    // TODO: Change dirpath to a more appropriate directory
    let dirpath = __dirname;
    let data = getDataFromEditor();
    let file = new File(dirpath + '/' + filename);
    file.open("w");
    file.writeln(data);
    file.close();
}

function getCompilingCommand (language) {
    // TODO: Get the value from the json file
    // TODO: Form an appropriate command to return
    //let obj = $.parseJSON();
}

function Compile (filename, language, exec) {
    // TODO: Complete the command
    // TODO: Execute the command in another directory
    //let command = getCompilingCommand(language);
    
    const child = exec('ls',
        function (error, stdout, stderr) {
            //document.append('stdout: ' + stdout);
            if (error !== null) {
                console.log('exec error: ' + error);
            }
            let node = document.createElement("p");
            "use strict";
            for (i = 0; i < stdout.length; i++) {
                if (stdout[i] == '\n') {
                    document.getElementById("comp").appendChild(
                                        document.createElement('br'));
                } else {
                    document.getElementById("comp").appendChild(
                                        document.createTextNode(stdout[i]));
                }
            }
        });
}
