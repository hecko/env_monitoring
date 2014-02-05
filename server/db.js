var mongoose = require( 'mongoose' );
var Schema   = mongoose.Schema;
 
var Point = new Schema({
    token         : String,
    key           : String,
    val           : String,
    time          : Date,
    time_received : Date,
});
 
var User = new Schema({
    username         : String,
    password         : String,
});

mongoose.model( 'Point', Point );
mongoose.model( 'User',  User );

mongoose.connect( 'mongodb://localhost/express-gather' );
