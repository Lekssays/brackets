/* TODO: Fix the coding style to follow:
 https://github.com/electron/electron/blob/master/docs/development/coding-style.md
*/

function getDataFromEditor () {
    return ace.edit("editor").getValue();
}

function saveFile (filename, data) {
    // TODO: Change __dirname to a more appropriate directory
    let file = new File(__dirname + '/' + filename);
    file.open("w");
    file.writeln(data);
    file.close();
}

function getCompilingCommand (language) {
    // TODO: Get the value from the json file
    // TODO: Form an appropriate command to return
    let obj = $.parseJSON();

}

function Compile (filename, language) {
    // TODO: Complete the command
    // TODO: Execute the command in another directory
    let command = getCompilingCommand(language);
    require('child_process').exec('ls',
        function (error, stdout, stderr) {
            //document.append('stdout: ' + stdout);
            if (error !== null) {
                console.log('exec error: ' + error);
            }
            let node = document.createElement("p");
            "use strict";
            for (i = 0; i < stdout.length; i++) {
                if (stdout[i] == '\n') {
                    document.getElementById("comp").appendChild(document.createElement('br'));
                    console.log("hi\n");
                } else {
                    document.getElementById("comp").appendChild(document.createTextNode(stdout[i]));
                }
            }
        });
}
