#!/usr/bin/env php
<?php

$file = dirname(__FILE__) . '/users.db';
$destination = dirname(dirname(__FILE__)) . '/vendor/clearcode/eh-library-auth/cache/users.db';

if (copy($file, $destination)) {
    echo "Users loaded.\n";
} else {
    echo "Failed to load users from file $file...\n";
}
