<?php

/**
 * usage
 * php register-mysql-server.php --manage-api-url="https://syrup.keboola.com/provisioning/manage" \
 *      --manage-token="TOKEN" \
 *      --user=provisioning \
 *      --local-root-password=password \
 *      --database=provisioning \
 *      --host=db-server.keboola.com \
 *      --type=transformations \
 *      --mode=active \
 *      --register=Yes
 */

$shortopts = "a:m:p:u:d:h:t:m:r:";
$longopts = array("manage-api-url:", "manage-token:", "local-root-password:", "user:", "database:", "host:", "type:", "mode:", "register:");

$options = getopt($shortopts, $longopts);

$command = "mkpasswd -l 16";
$password = exec($command);

$command = "mysql -u root -p" . escapeshellarg($options["local-root-password"]) . " -e \"GRANT ALL PRIVILEGES ON *.* TO " .  escapeshellarg($options["user"]) . "@'%' IDENTIFIED BY " .  escapeshellarg($password) . " WITH GRANT OPTION;\"";
$statusCode = 0;
$output = null;
$response = exec($command, $output, $statusCode);
if ($statusCode != 0) {
    print $response;
    exit(1);
}

$command = "mysql -u root -p" . escapeshellarg($options["local-root-password"]) . " -e \"CREATE DATABASE " . $options["database"] . " DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;\"";
$statusCode = 0;
$output = null;
$response = exec($command, $output, $statusCode);
if ($statusCode != 0) {
    print $response;
    exit(1);
}

if ($options["register"] != "Yes") {
    print "Skipping registration to Provisioning API";
    exit(0);
}

print "Registering server to Provisioning API " . $options["manage-api-url"] . "\n";

$command = 'curl -s -X "POST" "' . $options["manage-api-url"] . '/server/mysql" ';
$command .= '-H "X-KBC-ManageApiToken: ' . $options["manage-token"] . '" ';
$command .= '-H "Content-Type: application/x-www-form-urlencoded" ';
$command .= '--data-urlencode "password=' . $password . '" ';
$command .= '--data-urlencode "user=' . $options["user"] . '" ';
$command .= '--data-urlencode "host=' . $options["host"] . '" ';
$command .= '--data-urlencode "type=' . strtolower($options["type"]) . '" ';
$command .= '--data-urlencode "mode=' . strtolower($options["mode"]) . '" ';
$command .= '--data-urlencode "database=' . $options["database"] . '"';

$statusCode = 0;
$output = null;
$responseString = exec($command, $output, $statusCode);
if ($statusCode != 0) {
    print $responseString;
    exit(1);
}
if (!$responseString) {
    print $command;
    exit(1);
}
$response = json_decode($responseString, true);
if (!$response) {
    print $respons;
    exit(1);
}
if (!isset($response["id"])) {
    print $responseString;
    exit(1);
}
if (!isset($response["isAlive"]) || !$response["isAlive"]) {
    print $responseString;
    exit(1);
}

$command = 'curl -s -X "POST" "' . $options["manage-api-url"] . '/server/mysql/' . $response["id"] . '/activate" ';
$command .= '-H "X-KBC-ManageApiToken: ' . $options["manage-token"] . '" ';
$command .= '-H "Content-Type: application/x-www-form-urlencoded" ';

$statusCode = 0;
$output = null;
$responseString = exec($command, $output, $statusCode);
if ($statusCode != 0) {
    print $responseString;
    exit(1);
}
if (!$responseString) {
    exit(1);
}
$response = json_decode($responseString, true);
if (!$response) {
    print $respons;
    exit(1);
}
if (!isset($response["id"])) {
    print $responseString;
    exit(1);
}
if (!isset($response["isAlive"]) || !$response["isAlive"]) {
    print $responseString;
    exit(1);
}
