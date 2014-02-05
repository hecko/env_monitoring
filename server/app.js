
/**
 * Module dependencies.
 */

require('./db');
var passport = require('passport');
var express = require('express');
var routes = require('./routes');
var user = require('./routes/user');
var http = require('http');
var path = require('path');

var app = express();

// all environments
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.bodyParser());
app.set('port', process.env.PORT || 3000);
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');
app.use(express.favicon());
app.use(express.logger('dev'));
app.use(express.json());
app.use(express.urlencoded());
app.use(express.methodOverride());
app.use(express.cookieParser());
app.use(express.session({ secret: 'mynicesecret' }));
app.use(passport.initialize());
app.use(passport.session());
app.use(app.router);


var passport      = require('passport'),
    LocalStrategy = require('passport-local').Strategy;

var mongoose = require( 'mongoose' );
var User = mongoose.model( 'User' );

passport.serializeUser(function(user, done) {
  console.log('serialize: ' + user);
  done(null, user.id);
});

passport.deserializeUser(function(id, done) {
  console.log('de-serialize: ' + id);
  User.findById(id, function(err, user) {
    done(err, user);
  });
});

passport.use(new LocalStrategy(
  function(username, password, done) {
    User.findOne({ username: username }, function (err, user) {
      if (err) { 
        console.log('User auth query error');
        console.log(user);
        return done(err); 
      }
      if (!user) {
        console.log('Incorrect username');
        console.log(user);
        return done(null, false, { message: 'Incorrect username.' });
      }
      if (user.password != password) {
        console.log('Incorrect password');
        console.log(user.password + " is not " + password);
        return done(null, false, { message: 'Incorrect password.' });
      }
      return done(null, user);
    });
  }
));

// development only
if ('development' == app.get('env')) {
  app.use(express.errorHandler());
}

app.get('/dashboard', routes.dashboard);
app.get('/',          routes.index);
app.get('/g/temp',    routes.temp);
app.get('/g/wind',    routes.wind);
app.get('/g/light',   routes.light);
app.get('/users',     user.list);
app.get('/user',      user.profile);
app.get('/get/:token/:key',     routes.get);
app.get('/scatter/:token/:key', routes.scatter);
app.get('/create_user',         routes.create_user);

app.get('/login', passport.authenticate('local', { successRedirect: '/dashboard',
                                                   failureRedirect: '/' }));
app.get('/logout', function(req, res){
  req.logout();
  res.redirect('/login');
});

app.post('/put',  routes.put);

http.createServer(app).listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('port'));
});
