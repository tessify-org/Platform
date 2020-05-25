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
        $this
            // ->addDirective(Directive::BASE, Keyword::SELF)
            // ->addDirective(Directive::CONNECT, Keyword::SELF)
            // ->addDirective(Directive::DEFAULT, Keyword::SELF)
            // ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            // ->addDirective(Directive::IMG, Keyword::SELF)
            // ->addDirective(Directive::MEDIA, Keyword::SELF)
            // ->addDirective(Directive::OBJECT, Keyword::NONE)
            // ->addDirective(Directive::SCRIPT, Keyword::SELF)
            // ->addDirective(Directive::STYLE, Keyword::SELF)
            // ->addNonceForDirective(Directive::SCRIPT)
            // ->addNonceForDirective(Directive::STYLE)
            ->addDirective(Directive::BASE, "none")
            ->addDirective(Directive::SCRIPT, "'self' 'unsafe-eval' use.fontawesome.com;")
            ;
            
    }
}