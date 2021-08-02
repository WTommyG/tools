#!/usr/bin/php

<?php
#Usage:
# kubectl --context YOUR_CONTEXT exec --stdin --tty POD_NAME -n NAMESPACE -- cat /proc/net/tcp | ./parse.php
sleep(1);
$states = array(
  "",
  "ESTABLISHED",
  "SYN_SENT",
  "SYN_RECV",
  "FIN_WAIT1",
  "FIN_WAIT2",
  "TIME_WAIT",
  "CLOSE",
  "CLOSE_WAIT",
  "LAST_ACK",
  "LISTEN",
  "CLOSING",
  "NEW_SYN_RECV"
);

$f = fopen( 'php://stdin', 'r' );
fgets( $f );
while( $line = fgets( $f ) ) {
  $parts = explode(' ', trim($line));
  list($lip, $lport) = explode(':', $parts[1]);
  list($rip, $rport) = explode(':', $parts[2]);
  echo $parts[0].' '.decodeIP($lip).':'.hexdec($lport).' <- -> '.decodeIP($rip).':'.hexdec($rport).' ('.$states[hexdec($parts[3])].') ';
  echo "\n";
}

fclose( $f );

function decodeIP($ip) {
  return implode(
    '.',
    array_reverse(
      explode(
        '.',
        long2ip(
          hexdec($ip)
        )
      )
    )
  );
}
