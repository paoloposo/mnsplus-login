<?php

include 'MNSPlusAuth.php';


$mnspauth = new MNSPlusAuth();

$username = 'testuser00';
$password = 'secret';

$credentialsValid = $mnspauth->verifyIdentity($username, $password);

if ($credentialsValid) {
	print('Username and password are correct.');
}
else {
	print('Username and password are incorrect!');
}
