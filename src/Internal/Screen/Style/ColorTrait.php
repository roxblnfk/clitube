<?php

declare(strict_types=1);

namespace CliTube\Internal\Screen\Style;

trait ColorTrait
{
    public function string(Style ...$with): string
    {
        $values = [$this->value, ...\array_map(static fn (Style $enum) => $enum->value, $with)];
        return \sprintf('[%sm', \implode(';', $values));
    }
}
