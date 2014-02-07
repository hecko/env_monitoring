var mongoose = require( 'mongoose' );
var Point    = mongoose.model( 'Point' );
var User     = mongoose.model( 'User' );


exports.hc = function(req, res){
  var q = Point.find({ token: req.params.token,
                       key: req.params.key }, 'time key val -_id').sort({time:-1}).limit(500);
  q.exec(function (err, points, count) {
    var data = []; 
    points.forEach( function(p) {
        data.push([ Date.parse(p.time), parseFloat(p.val) ]);
    });
    res.json(data.reverse());
  });
};

exports.last = function(req, res){
  var out = [];
  Point.findOne({ token: req.params.token, key: req.params.key }).sort({time:-1}).exec(function(err, p, count) {
    res.json({ time: Date.parse(p.time), val: parseFloat(p.val) });
  })
};
