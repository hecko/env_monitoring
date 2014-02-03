
/**
 * Module dependencies.
 */

require('./db');
var express = require('express');
var routes = require('./routes');
var user = require('./routes/user');
var http = require('http');
var path = require('path');

var app = express();

// all environments
app.use(express.bodyParser());
app.set('port', process.env.PORT || 3000);
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');
app.use(express.favicon());
app.use(express.logger('dev'));
app.use(express.json());
app.use(express.urlencoded());
app.use(express.methodOverride());
app.use(app.router);
app.use(express.static(path.join(__dirname, 'public')));

// development only
if ('development' == app.get('env')) {
  app.use(express.errorHandler());
}

app.get('/',        routes.index);
app.get('/g/temp',        routes.temp);
app.get('/g/wind',        routes.wind);
app.get('/users',   user.list);
app.get('/get/:token/:key',     routes.get);
app.get('/scatter/:token/:key', routes.scatter);

app.post('/put',  routes.put);

http.createServer(app).listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('port'));
});
