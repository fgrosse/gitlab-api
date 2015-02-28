<?php
##############################################################
# This file is merely a small helper for the other examples. #
# There is nothing special here for you to see.              #
##############################################################

function getParameter($name, array $argv) {
    $envName = 'GITLAB_' . preg_replace('/\s|-/', '_', strtoupper($name));
    if (isset($_ENV[$envName])) {
        return $_ENV[$envName];
    }

    $pregName = preg_quote($name);
    array_shift($argv);
    foreach ($argv as $argument) {
        if (preg_match("/^--$pregName=(.+)/", $argument, $matches)) {
            return $matches[1];
        }
    }

    printError("ERROR: Please provide the parameter --$name or set the environment variable $envName");
    exit(1);
}

function printError($text) {
    fwrite(STDERR, $text . PHP_EOL);
}
