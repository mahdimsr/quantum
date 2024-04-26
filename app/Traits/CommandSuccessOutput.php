<?php

namespace App\Traits;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

trait CommandSuccessOutput
{
    public function success(string $message, $verbosity = null): void
    {
        if (! $this->output->getFormatter()->hasStyle('success')) {
            $style = new OutputFormatterStyle('cyan');

            $this->output->getFormatter()->setStyle('success', $style);
        }

        $this->line($message, 'success', $verbosity);
    }
}
