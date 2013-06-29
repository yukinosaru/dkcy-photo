<?php
#------------------------------------------------------------------------------
#************************* USEFUL FUNCTIONS FOR MYSQL *************************
#------------------------------------------------------------------------------

# Commented out for use on local host, swap before going live!
function ServerOpen (&$linkdb, $hostname, $username, $password) {
	$linkdb = mysql_pconnect($hostname, $username, $password);
	if ($linkdb != false) {
		return true;
	} else {
		return false;
	}
}

# Opens a persistent connection to the database and provides the connection ID - returns false if failed

#function ServerOpen (&$linkdb, $hostname, $username, $password) {
#	$linkdb = mysql_pconnect($hostname, $username);
#	if ($linkdb != false) {
#		return true;
#	} else {
#		return false;
#	}
#}

# Opens the appropriate database - returns false if failed

function DatabaseOpen ($linkdb, $database, $flags = '') {
	if (mysql_select_db($database, $linkdb) != false) {
		return true;
	} else {
		return false;
	}
}


# Runs a query ($querytext) on a database ($linkdb) and outputs the results as a
# requested variable ($queryresult) or returns false if failed

function ExecuteQuery ($linkdb, &$queryresult, $querytext) {
	$queryresult = mysql_query($querytext, $linkdb);
 	if (($queryresult != false) and (mysql_errno() == 0)) {
		return true;
	} else {
		return false;
	}
}

# Returns an array with the next row of the query results ($querresu)

function NextRow (&$querresu) {
	return mysql_fetch_array($querresu);
}

function ClearQuery ($querresu) {
	mysql_free_result($querresu);
}

# Sets up database name, username and password

#------------------------------------------------------------------------------
	$Databasename = 'vj99nksxsqxu';
	$Username =	'vj99nksxsqxu';
	$Password = 'JB48X20k0T59A5';
#------------------------------------------------------------------------------
	error_reporting(63);
	set_time_limit(3600); # 1 hour

# Sets host name
	$Hostname = 'localhost';

# Opens the server and database or returns an error message

	if (ServerOpen($linkdb, $Hostname, $Username, $Password) == false) {
		print('Unable to connect to the server at this time.<br>');
		die;
	} elseif (DatabaseOpen($linkdb, $Databasename) == false) {
		print('Unable to connect to the database '.$Databasename.' at this time.<br>');
		die;
	}
?>