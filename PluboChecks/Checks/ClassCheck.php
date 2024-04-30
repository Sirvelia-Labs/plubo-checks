<?php

namespace PluboChecks\Checks;

class ClassCheck extends Check
{
    public function perform_check()
    {
        return class_exists( $this->value );
    }
}