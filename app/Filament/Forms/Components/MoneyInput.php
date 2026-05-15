<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class MoneyInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->prefix('₫')
            ->placeholder('0')
            ->inputMode('numeric')
            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
            ->stripCharacters('.')
            ->minValue(0)
            ->dehydrateStateUsing(fn ($state) => static::normalizeMoney($state))
            ->formatStateUsing(fn ($state) => static::formatMoney($state));
    }

    public function zeroWhenEmpty(): static
    {
        $this->dehydrateStateUsing(
            fn ($state) => static::normalizeMoney($state) ?? 0
        );

        return $this;
    }

    protected static function normalizeMoney($state): ?int
    {
        if (! filled($state)) {
            return null;
        }

        $state = (string) $state;

        $state = str_replace(['.', ',', '₫', ' '], '', $state);

        return (int) $state;
    }

    protected static function formatMoney($state): ?string
    {
        if (! filled($state)) {
            return null;
        }

        return number_format((float) $state, 0, ',', '.');
    }
}