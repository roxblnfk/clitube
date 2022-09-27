#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

(new SingleCommandApplication())
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $core = (new \Roxblnfk\CliTube\Core($output));
        $core->createComponent(\Roxblnfk\CliTube\Component\Scroll::class, ['content' => '123', 'overwrite' => false]);
        // $core->createComponent(\Roxblnfk\CliTube\Component\Paginator::class, [
        //     // new \Roxblnfk\CliTube\Tests\Unit\Stub\Paginator(),
        //     new \Roxblnfk\CliTube\Tests\Unit\Stub\OffsetPaginator(),
        // ]);
        $core->run();
    })
    ->run();
