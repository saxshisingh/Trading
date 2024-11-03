var mysql = require('mysql');
var connection = mysql.createConnection({
    host     : '127.0.0.1',
    user     : 'root',
    password : '',
    database : 'w3gold'
});

connection.connect(function(err) {
    if (err) throw err;
    console.log("connected");
});

module.exports = connection;