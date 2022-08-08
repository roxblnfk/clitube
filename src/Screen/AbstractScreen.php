<?php

declare(strict_types=1);

namespace Roxblnfk\CliTube\Screen;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Terminal;

class AbstractScreen
{
    /**
     * Overwrite last output
     */
    public bool $overwrite = true;

    protected array $buffer = [''];

    protected Terminal $terminal;

    protected bool $firstDraw = true;
    protected int $redrawLines = 0;

    public function __construct(
        private readonly OutputInterface $output,
    ) {
        $this->terminal = new Terminal();

        if (!$this->output->isDecorated()) {
            // disable overwrite when output does not support ANSI codes.
            $this->overwrite = false;
        }
    }

    public function clear(bool $cleanInputLine = true): void
    {
        if ($this->overwrite) {
            if (!$this->firstDraw) {
                $this->removeLines($this->redrawLines, $cleanInputLine);
            }
        } else {
            $this->output->writeln('');
        }
    }

    public function redraw(bool $cleanInputLine = false): void
    {
        $this->clear($cleanInputLine);
        $frame = $this->prepareFrame();
        $this->firstDraw = false;
        $this->redrawLines = \count($frame);

        $this->drawFrame($frame);
    }

    /**
     * @return string[]
     */
    protected function prepareFrame(): array
    {
        return $this->buffer;
    }

    /**
     * @param string[] $frame
     */
    protected function drawFrame(array $frame): void
    {
        $i = 0;
        foreach ($frame as $line) {
            ++$i;
            $isLast = $i === \count($frame);
            $this->output->write($line, !$isLast, OutputInterface::OUTPUT_RAW);
        }
    }

    public function removeLines(int $lines = 1, bool $cleanInputLine = true): void
    {
        if ($lines < 0) {
            return;
        }
        // $this->output->write(\str_repeat("\x1B[1A\x1B[2K", $lines), options: OutputInterface::OUTPUT_RAW);
        $cursor = new \Symfony\Component\Console\Cursor($this->output);
        if ($cleanInputLine) {
            $cursor->clearLine();
        }
        for ($i =0; $i < $lines; ++$i) {
            $cursor->moveUp();
            $cursor->clearLine();
        }
        $cursor->moveToColumn(0);

        // # Move the cursor to the beginning of the line
        // $this->output->write("\x0D", options: OutputInterface::OUTPUT_RAW);
        // # Erase the line
        // $this->output->write("\x1B[2K", options: OutputInterface::OUTPUT_RAW);
        // # Erase previous lines
        // $this->output->write(\str_repeat("\x1B[1A\x1B[2K", $lines), options: OutputInterface::OUTPUT_RAW);
        // // for ($i = 0; $i < $lines; ++$i)
        // //     $this->output->write("\x1B[1A\x1B[2K");
        // }
    }

    public function getWindowWidth(): int
    {
        return $this->terminal->getWidth();
    }

    public function getWindowHeight(): int
    {
        return $this->terminal->getHeight();
    }

    /**
     * @param string[]|string $message
     */
    public function writeln(iterable|string $message): void
    {
        $this->write($message, true);
    }

    /**
     * @param string[]|string $message
     */
    public function write(iterable|string $message, bool $newline = false): void
    {
        $messages = \is_string($message) ? [$message] : $message;
        foreach ($messages as $msg) {
            $same = true;
            foreach (\explode(PHP_EOL, $msg) as $line) {
                if ($same) {
                    $this->buffer[\array_key_last($this->buffer)] .= $line;
                    $same = false;
                } else {
                    $this->buffer[] = $line;
                }
            }
        }
        if ($newline) {
            $this->buffer[] = '';
        }
    }
}
