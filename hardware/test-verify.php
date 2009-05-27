<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */


$to_sign = "the quick brown fox jumped over the lazy dog";

$fp = fopen("/var/www/ds-dev/charliehorse/ds-dev-private.key", "r");
$priv_key = fread($fp, 8192);
fclose($fp);
$pkeyid = openssl_get_privatekey($priv_key);

$sig="";
if (!openssl_sign($to_sign, $sig, $pkeyid)) {
    print "signing failed!\n";
}


$fp = fopen("/var/www/ds-dev/charliehorse/ds-dev-public.key", "r");
$public_key = fread($fp, 65536);
fclose($fp);
$pkeyid = openssl_get_publickey($public_key);

print "verifying unaltered message...\n";
$result = openssl_verify($to_sign, $sig, $pkeyid);

if ($result == 1) {
    print "signature OK\n";
} else if ($result == 0) {
    print "signature bad!\n";
} else {
    print "failed to verify!\n";
}

print "trying altered message...\n";
// change the message around a bit so it won't verify
$to_sign = "the quick brown fox jumped over the lazy dogs";
$result = openssl_verify($to_sign, $sig, $pkeyid);

if ($result == 1) {
    print "signature OK\n";
} else if ($result == 0) {
    print "signature bad!\n";
} else {
    print "failed to verify!\n";
}

?>
