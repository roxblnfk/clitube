#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

(new SingleCommandApplication())
    // ->setName('My Super Command') // Optional
    // ->setVersion('1.0.0') // Optional
    // ->addArgument('foo', InputArgument::OPTIONAL, 'The directory')
    // ->addOption('bar', null, InputOption::VALUE_REQUIRED)
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $core = new \Roxblnfk\CliTube\Core($output);
        $core->createComponent(\Roxblnfk\CliTube\Component\Help::class);
        $core->run();
    })
    ->run();

