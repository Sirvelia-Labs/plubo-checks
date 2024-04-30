<?php

namespace PluboChecks\Checks;

class FunctionCheck extends Check
{
    public function perform_check()
    {
        return is_callable( $this->value );
    }
}