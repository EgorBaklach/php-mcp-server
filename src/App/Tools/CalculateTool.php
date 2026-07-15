<?php namespace App\Tools;

use Symfony\Component\Process\Process;

final class CalculateTool
{
    public function calculate(string $expression): string
    {
        if (!preg_match('/^[\d\s\+\-\*\/\(\)\.]+$/', $expression)) {
            throw new \InvalidArgumentException(
                'Invalid expression: only numbers and operators +, -, *, /, () are allowed.'
            );
        }

        $process = new Process(['php', '-r', "echo ({$expression});"], timeout: 5);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Calculation failed: ' . $process->getErrorOutput());
        }

        return trim($process->getOutput());
    }
}
