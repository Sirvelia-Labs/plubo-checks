<?php

namespace PluboChecks\Checks;

class ConstantCheck extends Check
{
    public function perform_check()
    {
        return constant( $this->value );
    }
}