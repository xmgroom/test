let app = require('http').createServer((req, res) => {
    res.writeHead(200);
    res.end('');
});
let io = require('socket.io')(app);

let Redis = require('ioredis');
let redis = new Redis();

var userList = [];

app.listen(6001, () => console.log('Server is running!'));


io.on('connection', (socket) => {

    socket.on('login', (user) => {

        if (userList[user.channel] === undefined) userList[user.channel] = [];

        userList[user.channel].push(user.openid);

        io.emit(user.channel + ':UserChange', userList[user.channel].length);

        let userCount = countUser();

        io.emit('user.count', userCount);

        console.log('[L] openid:' + user.openid + ' Login!');

    });


    socket.on('disconnect', (res) => {

        let user = cookieToObject(socket.request.headers.cookie.split('; '));

        console.log(user);

        let index = userList[user['channel']].indexOf(user['openid'] + "");

        userList[user['channel']].splice(index, 1);

        io.emit(user.channel + ':UserChange', userList[user.channel].length);

        let userCount = countUser();

        io.emit('user.count', userCount);

        console.log('[L] openid:' + user['openid'] + ' Logout!');

    });

    socket.on('user.count', () => {

        let userCount = countUser();

        io.emit('user.count', userCount);

    });

});


redis.psubscribe('*', (err, count) => {

});

redis.on('pmessage', (subscrbed, channel, message) => {
    message = JSON.parse(message);
    console.log('[M]'.channel + ' Message :' + message.event, message.data);
    io.emit(channel + ':' + message.event, message.data);
});


function countUser() {

    let userCount = [];

    for (var i in userList) {

        userCount[i] = userList[i].length;

    }

    return userCount;
}

function cookieToObject(data) {

    let new_Object = [];

    for (let i in data) {

        data[i] = data[i].split('=');

        new_Object[data[i][0]] = data[i][1];

    }

    return new_Object;

}