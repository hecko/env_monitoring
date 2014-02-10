var mongoose = require( 'mongoose' );
var User     = mongoose.model( 'User' );

exports.list = function(req, res){
  res.send("respond with a resource");
};

exports.profile = function(req, res) {
  console.log(req.user);
  res.send('see server console');
};
