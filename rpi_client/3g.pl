#!/usr/bin/perl

if (status() == 0) {
  print "We are NOT connected - try to connect now!";
  my $connect = `/opt/env_monitoring/external/sakis3g-source/build/sakis3gz connect APN="payandgo.o2.co.uk"`;
  print $connect;
} else {
  print "We are connected :)";
}

sub status {
  my $status  = `/opt/env_monitoring/external/sakis3g-source/build/sakis3gz status`;
  my $status_code = $? >> 8;
  print "Status code: " . $status_code . "\n";
  if ($status_code != 0) {
    print "Not connected!";
    return 0;
  } else {
    print "Yes, connected!";
    return 1;
  }
}
