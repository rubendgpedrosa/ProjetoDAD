/*jshint esversion: 6 */

var app = require('http').createServer();

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base)

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor,
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require('socket.io')(app);

app.listen(8080, function(){
    console.log('listening on *:8080');
});

// ------------------------
// Estrutura dados - server
// ------------------------

// loggedUsers = the list (map) of logged users.
// Each list element has the information about the user and the socket id
// Check loggedusers.js file

let loggedUsers = [];
let nodemailer = require('nodemailer');

let transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: 'ewallet.dad@gmail.com',
        pass: 'P@sswordDAD2019'
    }
});

io.on('connection', function (socket) {
    console.log('Unauthenticated user has connected (socket ID = '+socket.id+')' );

    socket.on('login', (email)=>{
        let room = email;
        socket.join(room);
        if(!loggedUsers.filter(user => user.email === email).length > 0){
            loggedUsers.push({email: email, socketid: socket.id});
        }else{
            loggedUsers = loggedUsers.filter(user => user.email !== email);
            loggedUsers.push({email: email, socketid: socket.id});
        }
    });
    socket.on('logout', (email)=>{
        socket.leave(email);
        loggedUsers = loggedUsers.filter(user => user.email !== email);
    });
    socket.on('user_created', (email)=>{
        var mailOptions = {
            from: 'E-Wallet <ewallet.dad@gmail.com>',
            to: email,
            subject: 'Account created!',
            html:'<div><h2 class="display-4">New Account Created</h2><p class="lead">Your new account is ready to be used!</p><a href="http://178.62.92.102">Click here</a> to go to the website</div>',
        };

        transporter.sendMail(mailOptions, function(error, info){
            if (error) {
                console.log(error);
            } else {
                console.log('Email sent: ' + info.response);
            }
        });
    });
    socket.on('wallet_movements', (email_income)=>{
        let email = email_income;
        let filteredUser = loggedUsers.find(user => user.email === email);
        var mailOptions = {
            from: 'E-Wallet <e_wallet@projetodad.com>',
            to: email_income,
            subject: 'Wallet Deposit',
            html:'<div><h2 class="display-4">Deposit Transfer received</h2><p class="lead">A deposit has been made to your account!</p><a href="http://178.62.92.102">Click here</a> to go to the website</div>',
        };

        if(filteredUser !== undefined){
            if(io.sockets.sockets[filteredUser.socketid] !== undefined){
                socket.to(email).emit('wallet_movements_response');
            }else{
                transporter.sendMail(mailOptions, function(error, info){
                    if (error) {
                        console.log(error);
                    } else {
                        console.log('Email sent: ' + info.response);
                    }
                });
            }
        }else{
            transporter.sendMail(mailOptions, function(error, info){
                if (error) {
                    console.log(error);
                } else {
                    console.log('Email sent: ' + info.response);
                }
            });
        }
    });
});
