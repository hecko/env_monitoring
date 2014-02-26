#!/usr/bin/perl

if (!status()) {
  print "We are NOT connected - try to connect now!";
  my $connect = `/opt/env_monitoring/external/sakis3g-source/build/sakis3gz connect APN="payandgo.o2.co.uk"`;
  print $connect;
} else {
  print "We are connected :)";
}

sub status {
  my $status  = `/opt/env_monitoring/external/sakis3g-source/build/sakis3gz status`;
  my $status_code = $? >> 8;
  if ($status_code != 0) {
    return false;
  } else {
    return true;
  }
}
