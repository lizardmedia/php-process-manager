<?php
namespace Phppm;

interface ProcessInterface
{

    public function exec();

    public function open();

    public function close();

    public function terminate();

    public function status();
}