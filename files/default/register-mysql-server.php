<?php

/**
 * usage
 * php register-mysql-server.php --manage-api-url="https://syrup.keboola.com/provisioning/manage" \
 *      --manage-token="TOKEN" \
 *      --user=provisioning \
 *      --password=provisioning \
 *      --database=provisioning \
 *      --host=db-server.keboola.com \
 *      --type=transformations \
 *      --mode=active
 */

$shortopts = "a:m:u:p:d:h:t:m:";
$longopts = array("manage-api-url:", "manage-token:", "user:", "password:", "database:", "host:", "type:", "mode:");

$options = getopt($shortopts, $longopts);

$command = 'curl -s -X "POST" "' . $options["manage-api-url"] . '/server/mysql" ';
$command .= '-H "X-KBC-ManageApiToken: ' . $options["manage-token"] . '" ';
$command .= '-H "Content-Type: application/x-www-form-urlencoded" ';
$command .= '--data-urlencode "password=' . $options["password"] . '" ';
$command .= '--data-urlencode "user=' . $options["user"] . '" ';
$command .= '--data-urlencode "host=' . $options["host"] . '" ';
$command .= '--data-urlencode "type=' . strtolower($options["type"]) . '" ';
$command .= '--data-urlencode "mode=' . strtolower($options["mode"]) . '" ';
$command .= '--data-urlencode "database=' . $options["database"] . '"';

$responseString = exec($command);
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

$command = 'curl -s -X "POST" "' . $options["manage-api-url"] . '/server/mysql/' . $response["id"] . '/activate" ';
$command .= '-H "X-KBC-ManageApiToken: ' . $options["manage-token"] . '" ';
$command .= '-H "Content-Type: application/x-www-form-urlencoded" ';

$responseString = exec($command);
$responseString = exec($command);
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
