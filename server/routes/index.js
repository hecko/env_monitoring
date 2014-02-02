var mongoose = require( 'mongoose' );
var Point    = mongoose.model( 'Point' );

exports.index = function(req, res){
  Point.find( function (err, points, count) {
    console.log(points);
    res.render('index', { title: 'Gatherer',
                          points: points
			});
  });
};

exports.put = function ( req, res ){
  console.log(req.body);
  req.body.data.forEach( function(d) {
    var new_point = { token : req.body.token,
                      key   : d.key,
                      val   : d.val,
                      time  : d.timestamp * 1000
                    };
    console.log(new_point);
    new Point(new_point).save( function( err, point, count ){});
  });
  res.render( 'put', { "message" : req.body } );
};

exports.get = function(req, res){
  var data = [];
  Point.find({ token: /ttoken/ }, 'time key val -_id', function (err, points, count) {
    res.json(points);
  });
};

exports.scatter = function(req, res){
  var q = Point.find({ token: /ttoken/ }, 'time key val -_id').sort({'time':-1}).limit(300);
  q.exec(function (err, points, count) {
    var data = [{ key: "Temp",
                  values: [],
               }];
    data[0]['last_value'] = points[0].val;
    data[0]['last_time']  = points[0].time;
    points.forEach( function(p) {
        if (p.time && p.val) {
          data[0]['values'].push({ y:      parseFloat(p.val),
                                   x:      Date.parse(p.time),
                                   time:   p.time,
                                   shape:  "circle",
                                   size:   0.6,
                                   series: 0
                                });
        };
      });
    console.log(data);
    res.json(data);
  });
};
