const express = require('express');
const { createServer } = require('node:http');
const { Server } = require('socket.io');
const cors = require("cors");
const app = express();
const mysql = require('mysql');
let { SmartAPI, WebSocketClient, WebSocketV2, WebSocket, WSOrderUpdates } = require('smartapi-javascript');
// var WebSocketClient = require('websocket').client;

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
const axios = require('axios');
const { setInterval } = require('node:timers');
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
        origin: "*",
        methods: ["GET", "POST"]
    }
});
const itemsPerPage = 10;
let prevPage = -1;
let fetchingData = false;
let currentPage = 0;
let totalRecords = 0;
function getData(page) {
    if (fetchingData) {
        // console.log("Already fetching data, skipping this call.");
        return;
    }

    fetchingData = true;
    let list = [
        {
            exchanger: "BSE",
            script: ["99919000"]
        },
        {
            exchanger: "NSE",
            script: ["26000"]
        }
    ];
    if (prevPage === page) {
        setTimeout(() => {
            // console.log("called in same page");
            getData(page);
        }, 500);
        return;
    }
    prevPage = page;

    connection.query('SELECT * FROM kite_tokens LIMIT 1', (err, results) => {
        if (err) throw err;

        const token = JSON.parse(results[0].access_token);
        const offset = page * itemsPerPage;

        connection.query(`
            SELECT COUNT(DISTINCT expiry_date_id) AS total FROM watchlists

        `, (err, results) => {
            if (err) throw err;
            res = JSON.parse(JSON.stringify(results));

            totalRecords = res[0].total;

        });

        connection.query(`
            WITH RankedWatchlists AS ( 
            SELECT wl.id, wl.expiry_date_id, wl.segment_id, s.exchange, e.token AS expiry_token, 
            ROW_NUMBER() OVER 
            (PARTITION BY wl.expiry_date_id ORDER BY wl.id)
             AS rn FROM watchlists wl JOIN segments s ON wl.segment_id = s.id JOIN expiry_date e ON wl.expiry_date_id = e.id WHERE wl.deleted_at IS NULL ) 
             SELECT id, expiry_date_id, segment_id, exchange, expiry_token 
             FROM RankedWatchlists WHERE rn = 1
             LIMIT ${itemsPerPage} OFFSET ${offset};
            `, (err, results) => {
            if (err) throw err;

            // console.log("results = = ", results.length);
            results.forEach(item => {
                let exchanger = item.exchange;
                let scriptToken = item.expiry_token;

                let existing = list.find(el => el.exchanger === exchanger);

                if (existing) {
                    existing.script.push(scriptToken);
                } else {
                    list.push({
                        exchanger: exchanger,
                        script: [scriptToken]
                    });
                }
            });

            const data = {
                "mode": "FULL",
                exchangeTokens: []
            };
            list.forEach((res) => {
                data.exchangeTokens[res.exchanger] = res.script
            });

            data.exchangeTokens = Object.assign({}, data.exchangeTokens);

            fetchData(data, token);
            if (((page + 1) * itemsPerPage) < totalRecords) {
                currentPage = page + 1;
                // console.log("got it ======------------>", countUser++);
                setTimeout(() => {
                    // console.log("calling get data from got it");
                    getData(currentPage);
                    fetchingData = false;
                }, 500);
            } else {
                // console.log("reset=-=-=-=-=->", countUser);
                countUser = 0;
                currentPage = 0;
                // console.log("calling get data from reset");
                getData(0);
                fetchingData = false;
            }
        });
    });
}

function fetchData(data, token) {
    var config = {
        method: 'post',
        url: 'https://apiconnect.angelbroking.com//rest/secure/angelbroking/market/v1/quote',
        headers: {
            'X-PrivateKey': 'EmlDRPXu',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token["auth_token"],
            'Content-Type': 'application/json',
            'X-ClientLocalIP': '157.245.107.204',
            'X-ClientPublicIP': '157.245.107.204',
        },
        data: data
    };
    // console.log('connect to api', config)
    axios(config)
        .then(function (response) {
            // console.log(`axios response received for page ${currentPage} at ${new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()}`);
            // console.log("response = ", response);
            if (response.status == 200) {
                if (response.data.data) {
                    // console.log("time - ", `${new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()}`)
                    io.emit('response', JSON.stringify(response.data.data));
                }
            } else {
            }
            stoploss(response.data.data.fetched);
        })
        .catch(function (error) {
            console.log(error, "error");
        });
}


io.on('connection', async (socket) => {
    console.log("user connected");
});

let countUser = 0;
setInterval(() => {
    getData(currentPage);
}, 500);
function receiveTick(data) {
    console.log("receiveTick:::::", data);
    io.emit('response', JSON.stringify(data));
}


function stoploss(data) {
    data.forEach(item => {
        let symbolToken = item.symbolToken;
        let currentPrice = 0;
        let sellPrice = item.depth.buy[0].price;
        let buyPrice = item.depth.sell[0].price;
        const status = 'open';
        let userSql = `SELECT * FROM users WHERE id = ?`;
        let positionSql = 'SELECT * FROM positions WHERE token = ? AND status = ?';
        connection.query(positionSql, [symbolToken, status], function (err, result) {
            if (err) {
                console.error('Error fetching position data:', err);
                return;
            }

            if (result.length > 0) {
                result.forEach(position => {

                    connection.query(userSql, [position.user_id], function (err, userResult) {
                        if (err) throw err;

                        let auth_user = userResult[0];
                        let qty = position.quantity;
                        let stopLoss = position.stopLoss;
                        let buyRate = 0;
                        let sellRate = 0;
                        let mtm = 0;
                        let message = "";
                        const currentTime = new Date();
                        const marketCloseTime = new Date();
                        marketCloseTime.setHours(15, 30, 0, 0);
                        let bal = ((100 - auth_user.auto_close_limit) / 100) * auth_user.initial_balance;

                        if (position.type === 'BUY') {
                            currentPrice = sellPrice;
                            buyRate = position.price;
                            mtm = (currentPrice - buyRate) * qty;
                        } else if (position.type === 'SELL') {
                            currentPrice = buyPrice;
                            sellRate = position.price;
                            mtm = (sellRate - currentPrice) * qty;
                        }
                        if (position.product == "SL") {
                            if (stopLoss <= currentPrice) {
                                checkStopLoss(position, mtm, currentPrice);
                            }
                        }
                        if (auth_user.balance <= bal) {
                            notificationBalance(auth_user.id);
                            checkStopLoss(position, mtm, currentPrice);
                        }

                    });
                });
            } else {
                console.log(`No matching positions found for symbolToken ${symbolToken}`);
            }
        });

    });
}


function checkStopLoss(position, mtm, currentPrice) {
    let positionId = position.id;
    let positionSql = `SELECT * FROM positions WHERE id = ?`;
    let portfolioSql = `SELECT * FROM tradings WHERE position_id = ?`;
    let walletSql = `SELECT * FROM wallets WHERE trade_id = ? AND status = 'processing'`;



    connection.query(portfolioSql, [position.id], function (err, portfolioResult) {
        if (err) throw err;

        let portfolio = portfolioResult[0];
        if (!portfolio) throw new Error('Portfolio not found');

        connection.query(walletSql, [portfolio.id], function (err, walletResult) {
            if (err) throw err;

            let walletProcessing = walletResult[0];

            let updatePositionSql = `UPDATE positions SET status = 'close', close_at = NOW() WHERE id = ?`;
            let updatePortfolioSql = `UPDATE tradings SET portfolio_status = 'close', close_at = NOW() WHERE id = ?`;

            connection.query(updatePositionSql, [position.id], function (err) {
                if (err) throw err;

                connection.query(updatePortfolioSql, [portfolio.id], function (err) {
                    if (err) throw err;

                    if (portfolio.action === 'SELL') {
                        let isIntraday = true;

                        let tradeType = isIntraday ? 'intraday' : 'holding';
                        let current_price = 0;
                        if (position.product == "SL") {
                            current_price = portfolio.stoploss;
                        }
                        else {
                            current_price = currentPrice;
                        }
                        let updateTradingSql = `
                                UPDATE tradings 
                                SET action = 'BUY', buy_rate = ?, trade_type = ?, buy_at = NOW() 
                                WHERE id = ?`;

                        connection.query(updateTradingSql, [current_price, tradeType, portfolio.id], function (err) {
                            if (err) throw err;

                            updateWalletAndUser(portfolio.user_id, portfolio, mtm, currentPrice, 'BUY', walletProcessing);
                        });
                    }
                    else if (portfolio.action === 'BUY') {
                        // let isIntraday = checkIfIntraday(portfolio.created_at, new Date());
                        let isIntraday = true;

                        let tradeType = isIntraday ? 'intraday' : 'holding';
                        let current_price = 0;

                        if (position.product == "SL") {
                            current_price = portfolio.stoploss;
                        }
                        else {
                            current_price = currentPrice;
                        }
                        let updateTradingSql = `
                                UPDATE tradings 
                                SET action = 'SELL', sell_rate = ?, trade_type = ?, sell_at = NOW() 
                                WHERE id = ?`;

                        connection.query(updateTradingSql, [current_price, tradeType, portfolio.id], function (err) {
                            if (err) throw err;

                            updateWalletAndUser(portfolio.user_id, portfolio, mtm, currentPrice, 'SELL', walletProcessing);
                        });
                    }

                    let updateWalletProcessing = `UPDATE wallets SET status = 'success', profit_loss = ? WHERE id = ?`;
                    connection.query(updateWalletProcessing, [mtm, walletProcessing.id], function (err) {
                        if (err) throw err;

                        brokerage(portfolio, mtm, portfolio.user_id);

                    });
                });
            });
        });
    });
}


function brokerage(position, price, user_id) {
    let userSql = `SELECT * FROM users WHERE id = ?`;
    let segmentSql = `SELECT name FROM segments WHERE id = ?`;
    let scriptSql = `SELECT name FROM scripts WHERE id = ?`;
    let expirySql = `SELECT expiry_date FROM expiry_date WHERE id = ?`;

    // Fetch user data
    connection.query(userSql, [user_id], function (err, userResult) {
        if (err) throw err;

        let user = userResult[0];

        // Fetch segment data
        connection.query(segmentSql, [position.segment_id], function (err, segmentResult) {
            if (err) throw err;

            let segment = segmentResult[0] || {};
            let segmentName = segment.name;

            // Fetch script data
            connection.query(scriptSql, [position.script_id], function (err, scriptResult) {
                if (err) throw err;

                let script = scriptResult[0] || {};
                let scriptName = script.name;

                // Fetch expiry data
                connection.query(expirySql, [position.expiry_date_id], function (err, expiryResult) {
                    if (err) throw err;

                    let expiry = expiryResult[0] || {};
                    let expiryDate = expiry.expiry_date;

                    let user = userResult[0];
                    let tradeAmount = price;
                    let lotSize = position.lot;
                    let brokerage = 0;
                    let brokerageRate;

                    if (segmentName === 'MCX') {
                        if (user.mcx_broker_type === '0') {
                            brokerageRate = user.charge_per_crore_mcx;

                            getTotalTransactionAmountSinceLastBrokerage(user.id, segmentName, function (err, totalTransactionAmount) {
                                if (err) throw err;

                                if (totalTransactionAmount >= 10000000) {
                                    brokerage = Math.floor(totalTransactionAmount / 10000000) * brokerageRate;
                                }

                                deductBrokerage(user, brokerage, position);
                            });
                        } else {
                            brokerageRate = user.charge_per_lot_mcx;
                            brokerage = lotSize * brokerageRate;
                            deductBrokerage(user, brokerage, position);
                        }
                    } else if (segmentName === 'NSE') {
                        if (user.nse_broker_type === '0') {
                            brokerageRate = user.charge_per_crore;

                            getTotalTransactionAmountSinceLastBrokerage(user.id, segmentName, function (err, totalTransactionAmount) {
                                if (err) throw err;

                                if (totalTransactionAmount >= 10000000) {
                                    brokerage = (totalTransactionAmount / 10000000) * brokerageRate;
                                }

                                deductBrokerage(user, brokerage, position);
                            });
                        } else {
                            brokerageRate = user.charge_per_lot;
                            brokerage = lotSize * brokerageRate;
                            deductBrokerage(user, brokerage, position);
                        }
                    }
                });
            });
        });
    });
}

function notificationBalance(userId) {
    let notification = {
        title: 'Low Balance',
        message: `Your all open positions are automatically closed due to low balance. Please recharge again.`,
        send_at: new Date()
    };

    let insertNotificationSql = `INSERT INTO notifications SET ?`;
    let insertNotificationLogSql = `INSERT INTO notification_logs (user_id, notification_id) VALUES (?, ?)`;

    connection.query(insertNotificationSql, notification, function (err, notificationResult) {
        if (err) throw err;

        let notificationId = notificationResult.insertId;

        connection.query(insertNotificationLogSql, [userId, notificationId], function (err) {
            if (err) throw err;
        });
    });

}
function deductBrokerage(user, brokerage, position) {
    if (brokerage > 0) {
        user.balance -= brokerage;
        user.debit += brokerage;

        let wallet = {
            user_id: user.id,
            category: 'Brokerage Fees',
            remarks: 'Brokerage Fees',
            amount: brokerage,
            profit_loss: brokerage,
            type: 'debit',
            status: 'success',
            action: 'brokerage',
            trade_id: position.id
        };

        let notification = {
            title: 'Brokerage Fees',
            message: `${brokerage} Brokerage Fees is deducted successfully`,
            send_at: new Date()
        };

        let insertWalletSql = `INSERT INTO wallets SET ?`;
        let updateUserSql = `UPDATE users SET balance = ?, debit = ? WHERE id = ?`;
        let insertNotificationSql = `INSERT INTO notifications SET ?`;
        let insertNotificationLogSql = `INSERT INTO notification_logs (user_id, notification_id) VALUES (?, ?)`;

        connection.query(insertWalletSql, wallet, function (err) {
            if (err) throw err;

            connection.query(updateUserSql, [user.balance, user.debit, user.id], function (err) {
                if (err) throw err;

                connection.query(insertNotificationSql, notification, function (err, notificationResult) {
                    if (err) throw err;

                    let notificationId = notificationResult.insertId;

                    connection.query(insertNotificationLogSql, [user.id, notificationId], function (err) {
                        if (err) throw err;
                    });
                });
            });
        });
    } else {
        console.log('No brokerage fee applied.');
    }
}
function getTotalTransactionAmountSinceLastBrokerage(userId, segmentName, callback) {
    let lastBrokerageSql = `
        SELECT created_at 
        FROM wallets 
        WHERE action = 'brokerage' 
        AND user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 1
    `;

    connection.query(lastBrokerageSql, [userId], function (err, brokerageResult) {
        if (err) return callback(err, null);

        let lastBrokerageDate = brokerageResult.length > 0 ? brokerageResult[0].created_at : '1970-01-01 00:00:00';

        let totalTransactionSql = `
            SELECT SUM(amount) as total 
            FROM wallets 
            WHERE user_id = ? 
            AND action = 'market' 
            AND created_at > ?
        `;

        connection.query(totalTransactionSql, [userId, lastBrokerageDate], function (err, transactionResult) {
            if (err) return callback(err, null);

            let totalTransactionAmount = transactionResult[0].total || 0;
            callback(null, totalTransactionAmount);
        });
    });
}

function updateWalletAndUser(user_id, portfolio, mtm, currentPrice, actionType, walletProcessing) {
    let userSql = `SELECT * FROM users WHERE id = ?`;
    let segmentSql = `SELECT name FROM segments WHERE id = ?`;
    let scriptSql = `SELECT name FROM scripts WHERE id = ?`;
    let expirySql = `SELECT expiry_date FROM expiry_date WHERE id = ?`;

    // Fetch user data
    connection.query(userSql, [user_id], function (err, userResult) {
        if (err) throw err;

        let user = userResult[0];

        // Fetch segment data
        connection.query(segmentSql, [portfolio.segment_id], function (err, segmentResult) {
            if (err) throw err;

            let segment = segmentResult[0] || {};
            let segmentName = segment.name;

            // Fetch script data
            connection.query(scriptSql, [portfolio.script_id], function (err, scriptResult) {
                if (err) throw err;

                let script = scriptResult[0] || {};
                let scriptName = script.name;

                // Fetch expiry data
                connection.query(expirySql, [portfolio.expiry_date_id], function (err, expiryResult) {
                    if (err) throw err;

                    let expiry = expiryResult[0] || {};
                    let expiryDate = expiry.expiry_date;

                    let wallet = {
                        user_id: portfolio.user_id,
                        trade_id: portfolio.id,
                        category: `${actionType} ${scriptName} ${expiryDate}`,
                        remarks: `${actionType} ${scriptName} ${expiryDate} Successfully`,
                        action: 'trade',
                        status: 'success',
                        amount: currentPrice * portfolio.qty,
                        profit_loss: mtm < 0 ? -mtm : mtm,
                        type: mtm < 0 ? 'debit' : 'credit'
                    };

                    // Ensure all properties are of the correct type
                    wallet.amount = parseFloat(wallet.amount);
                    wallet.profit_loss = parseFloat(wallet.profit_loss);

                    // Ensure mtm is a number
                    mtm = parseFloat(mtm);

                    if (mtm < 0) {
                        user.debit += mtm;
                    } else {
                        user.credit += mtm;
                    }

                    user.balance += mtm;

                    let insertWalletSql = `INSERT INTO wallets SET ?`;
                    let updateUserSql = `UPDATE users SET balance = ?, credit = ?, debit = ? WHERE id = ?`;

                    // Insert wallet entry
                    connection.query(insertWalletSql, wallet, function (err) {
                        if (err) throw err;

                        // Update user record
                        connection.query(updateUserSql, [user.balance, user.credit, user.debit, user.id], function (err) {
                            if (err) throw err;
                        });
                    });
                });
            });
        });
    });
}


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
