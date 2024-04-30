<?php

namespace PluboChecks\Checks;

class EnvCheck extends Check
{
    public function perform_check()
    {
        return getenv( $this->value );
    }
}