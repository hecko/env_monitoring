var mongoose = require( 'mongoose' );
var Point    = mongoose.model( 'Point' );
var User     = mongoose.model( 'User' );

var title = 'Gust';

exports.dashboard = function(req, res){
    res.render('dashboard', { title:  title, 
                              token:  req.params.token,
                              user:   req.user
			    });
};

exports.index = function(req, res){
    res.render('dashboard', { title:  title, 
                              token:  'hacklab'
                            });
};

exports.temp_compare = function(req, res){
    res.render('temp_compare', { title:  title, 
                                 token:  'hacklab'
                               });
};

exports.wind = function(req, res){
  Point.find( function (err, points, count) {
    res.render('wind', { title: title,
                         points: points
			});
  });
};

exports.hc = function(req, res){
  res.render('hc', { title: title });
};

exports.light = function(req, res){
  Point.find( function (err, points, count) {
    res.render('light', { title: title,
                         points: points
			});
  });
};

exports.temp = function(req, res){
  Point.find( function (err, points, count) {
    res.render('temp', { title: title,
                          points: points
			});
  });
};

exports.put = function ( req, res ){
  req.body.data.forEach( function(d) {
    var new_point = { token         : req.body.token,
                      key           : d.key,
                      val           : d.val,
                      time          : d.timestamp * 1000,
                      time_received : new Date(),
                    };
    new Point(new_point).save( function( err, point, count ){});
  });
  res.render( 'put', { "message" : req.body } );
};

exports.create_user = function ( req, res ){
  console.log(req.params);
  var new_user = { username: req.query.username,
                   password: req.query.password
                 };
  new User(new_user).save( function( err, user, count ){});
  console.log(new_user);
  res.render( 'put', { "message" : new_user } );
};

exports.get = function(req, res){
  Point.find({ token: req.params.token, key: req.params.key }, 'time key val -_id', function (err, points, count) {
    res.json(points);
  });
};

exports.scatter = function(req, res){
  var q = Point.find({ token: req.params.token, key: req.params.key }, 'time key val -_id').sort({'time':-1}).limit(1000);
  q.exec(function (err, points, count) {
    var data = [{ key: req.params.key,
                  values: [],
               }];
    if (req.params.key == "wind_freq") {
        data[0]['last_value'] = (points[0].val * 1.212 + 0.252) * 2.2369362920544;
        data[0]['orig_last_value'] = Number(points[0].val);
    } else {
        data[0]['last_value']  = points[0].val;
    }
    data[0]['last_time']  = points[0].time;
    
    points.forEach( function(p) {
        if (p.time && p.val) {
          var val;
          if (req.params.key == "wind_freq") {
              val = (p.val * 1.212 + 0.252) * 2.2369362920544; //frequency to miles per hour
          } else {
              val = p.val;
          };
          data[0]['values'].push({ y:        parseFloat(val),
                                   x:        Date.parse(p.time),
                                   orig_val: p.val,
                                   time:     p.time,
                                   shape:    "circle",
                                   size:     0.6,
                                   series:   0
                                });
        };
      });
    res.json(data);
  });
};
