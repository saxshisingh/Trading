const express = require('express');
const { createServer } = require('node:http');
const { Server } = require('socket.io');
const cors = require("cors");
const app = express();
const mysql = require('mysql');
app.use('/', (req, res) => {
    res.json({ data: 'hello' });
});
const connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: '2023@Bajajsys',
    database: 'w3gold'
});

connection.connect(function (err) {
    if (err) {
        console.error('Error connecting to the database:', err);
        return;
    }
    console.log('Connected to the database');
});

const server = createServer(app);
var fs = require('fs');
const axios = require('axios')
require('dotenv').config()

//load credentials from .env file
loginId = process.env.loginId
product = process.env.product
apikey = process.env.apikey

app.use(cors());
app.use(express.json());
const storedData = [];
const io = new Server(server, {
    cors: {
        origin: "https://whitegoldtrades.com",
        methods: ["GET", "POST"]
    }
});

io.on('connection', async (socket) => {
    io.emit('oldData', storedData)
    main(socket, JSON.parse(socket.handshake.query.data))
});

async function main(socketio, stockNames) {

    loginId = 'DC-MOHD8340'; // use your login ID.
    product = 'DIRECTRTLITE';
    apikey = '05546177F7514194AC29'; // use your API Key

    const authEndPoint = `http://s3.vbiz.in/directrt/gettoken?loginid=${loginId}&product=${product}&apikey=${apikey}`

    axios
        .get(authEndPoint)
        .then(function (res) {
            if (res.status == 200) {
                if (res.data.hasOwnProperty('Status') == false) {
                    //                console.log('authentication status not returned in payload. exiting')
                    return
                } else {
                    // console.log(`Auth Response ${res.data}`);
                }

                if (res.data.hasOwnProperty('AccessToken') == false) {
                    //                  console.log('access token not returned in payload. exiting')
                    return
                }

                var max_symbol = res.data['MaxSymbol']
                var access_token = res.data['AccessToken']
                var is_authenticated = res.data['Status']
                if (is_authenticated == false) {
                    console.log('authentication NOT successful,exiting')
                    return
                }

                var wsEndPoint = `116.202.165.216:992/directrt/?loginid=${loginId}&accesstoken=${access_token}&product=${product}`

                const socketClusterClient = require('socketcluster-client')
                socket = socketClusterClient.create({
                    hostname: wsEndPoint,
                    path: '',
                    port: 80
                });

                //get the CSV header details
                // subscribe_to_events(socket, 'getcsvdataheader')
                // socket.transmit('getcsvdataheader', '')


                //set a timeout, to let us know when the websocket connection is open
                var myInterval = setInterval(function () {
                    //        console.log('websocket connection state: ', socket.state);
                    if (socket.state == 'open') {
                        //console.log(socket)
                        //          console.log('websocket connection is open')

                        //cancel interval
                        clearInterval(myInterval);

                        // DIRECTRT PRIME USERS NEED TO SUBSCRIBE TO TICKDATA. ALL MARKET UPDATES ARE SENT TO THIS EVENT
                        // .json - to receive 1 min data in JSON format.
                        // .csv - to receive 1 min data in CSV format.
                        // .tick - to receive 1 sec data in CSV format. This is volume adjusted for each second.
                        // .raw - to received 1 sec data in RAW Exchange format. This will not have Volume per second.
                        // subscribe_to_channel(socket, 'NSE_FUTIDX_NIFTY_26MAY2022.csv')
                        // subscribe_to_channel(socket, 'NSE_FUTIDX_NIFTY_26MAY2022.json')
                        //        console.log('Received stock names:', stockNames);

                        subscribe_to_channel(socket, stockNames);

                    } else if (socket.state == 'closed') {
                        //      console.log(socket);
                        //    console.log('websocket connection is closed. exiting');
                        clearInterval(myInterval);
                        // socket.disconnect();
                        return
                    }
                }, 1000)

            } else {
                //error occured getting access token
                // console.log(`server-side error occurred when getting access token,status code returned was ${res.status}\r\nResponse : ${json.stringify(res)}`);
                return
            }
        })
        .catch(error => {
            // console.error(`Exception occured: ${error}`);
            return
        })
}

function store_data_into_db(data) {
    // console.log("data = ", data);
    Object.entries(data).forEach(([stock, response]) => {
        const checkIfExistsSql = 'SELECT COUNT(*) AS count FROM watchlist_logs WHERE stock = ?';
        connection.query(checkIfExistsSql, [stock], function (err, result) {
            if (err) {
                //           console.error('Error checking existence of stock:', err);
                return;
            }

            if (result[0].count > 0) {
                const updateSql = 'UPDATE watchlist_logs SET response = ? WHERE stock = ?';
                connection.query(updateSql, [response, stock], function (err, result) {
                    if (err) {
                        //                 console.error('Error updating data:', err);
                        return;
                    }
                    //           console.log(`Updated stock ${stock} with new response ${response}`);
                    updateWatchlistLogId(stock);
                });
            } else {
                const insertSql = 'INSERT INTO watchlist_logs (stock, response) VALUES (?, ?)';
                connection.query(insertSql, [stock, response], function (err, result) {
                    if (err) {
                        //             console.error('Error inserting data:', err);
                        return;
                    }
                    //       console.log(`Inserted new stock ${stock} with response ${response}`);
                    updateWatchlistLogId(stock);
                });
            }
        });
    });
}

function updateWatchlistLogId(stock) {
    // Find the Watchlist entry with the given stock
    const watchlistSql = 'SELECT * FROM watchlists WHERE stock = ?';
    connection.query(watchlistSql, [stock], function (err, result) {
        if (err) {
            // console.error('Error retrieving Watchlist entry:', err);
            return;
        }

        if (result.length > 0) {
            const watchlist = result[0];
            // Find the corresponding log entry
            const logSql = 'SELECT id FROM watchlist_logs WHERE stock = ?';
            connection.query(logSql, [stock], function (err, logResult) {
                if (err) {
                    //       console.error('Error retrieving log entry:', err);
                    return;
                }

                if (logResult.length > 0) {
                    const logId = logResult[0].id;
                    // Update log_id in Watchlist table
                    const updateLogIdSql = 'UPDATE watchlists SET logs_id = ? WHERE id = ?';
                    connection.query(updateLogIdSql, [logId, watchlist.id], function (err, updateResult) {
                        if (err) {
                            //             console.error('Error updating log_id:', err);
                            return;
                        }
                        //       console.log(`Updated log_id for Watchlist entry with stock ${stock}`);
                    });
                }
            });
        }
    });
}

function subscribe_to_channel(socket, ticker) {
    (async () => {

        // Subscribe to a channel.
        const array = [];
        ticker.forEach(stockName => {
            array.push(stockName + '.json');
        });
        const channel_name = `${ticker}`
        // console.log(array);
        // console.log(`subscribing to channel ${channel_name}`)

        for (const channel of array) {
            let myChannel = socket.subscribe(channel);
            // console.log(myChannel);

            await myChannel.listener('subscribe').once();
            (async () => {
                for await (let data of myChannel) {
                    // console.log(data);
                    // log channel name, and the data to console
                    const stockName = channel.replace('.json', '');
                    storedData[stockName] = data;
                    io.emit('response', data)
                    // store_data_into_db(storedData);
                    console.log(`channel data received: ${data} - from channel ${ticker}.json`);
                    // console.log(storedData);
                    // handle_message("SUBSCRIPTION-" + channel_name, data)
                }
            })();
        }

        // myChannel.state is now 'subscribed'.
        // console.log(`successfully subscribed to channel ${JSON.stringify(myChannel)}`);


        //now, i need listener for the channel i subscribed to.
        //
    })();
}

server.listen(3030, () => {
    console.log('server running at localhost');
});

process.on('SIGINT', () => {
    connection.end((err) => {
        if (err) {
            console.error('Error closing the database connection:', err);
        } else {
            console.log('Database connection closed');
        }
        process.exit();
    });
});
