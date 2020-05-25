<?php

namespace App\Services\Csp;

use Spatie\Csp\Keyword;
use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Policies\Policy;

class PlatformPolicy extends Policy
{
    public function configure()
    {
        // $this
        //     ->addDirective(Directive::BASE, "none")
        //     ->addDirective(Directive::SCRIPT, "'self' 'unsafe-eval' use.fontawesome.com;");
            
    }
}