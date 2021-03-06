<?php //-->
/**
 * This file is part of the Cradle PHP Kitchen Sink Faucet Project.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\CommandLine\Index as CommandLine;

return function ($request, $response) {
    $port = 8888;

    if($request->hasStage('port')) {
        $port = $request->getStage('port');
    } else if($request->hasStage('p')) {
        $port = $request->getStage('p');
    }

    $host = '127.0.0.1';

    if($request->hasStage('host')) {
        $host = $request->getStage('host');
    } else if($request->hasStage('h')) {
        $host = $request->getStage('h');
    }

    //setup the configs
    CommandLine::system('Starting Server...');
    CommandLine::info('Listening on ' . $host . ':'.$port);
    CommandLine::info('Press Ctrl-C to quit.');

    $cwd = $request->getServer('PWD');
    system('php -S ' . $host . ':' . $port . ' -t ' . $cwd . '/public');
};
