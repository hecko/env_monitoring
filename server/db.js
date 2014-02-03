var mongoose = require( 'mongoose' );
var Schema   = mongoose.Schema;
 
var Point = new Schema({
    token         : String,
    key           : String,
    val           : String,
    time          : Date,
    time_received : Date,
});
 
mongoose.model( 'Point', Point );
mongoose.connect( 'mongodb://localhost/express-gather' );
