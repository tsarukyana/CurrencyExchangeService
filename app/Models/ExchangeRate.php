<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'valute_id',
        'num_code',
        'char_code',
        'nominal',
        'name',
        'value',
        'vunit_rate',
        'exchange_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'exchange_date' => 'date',
        ];
    }

    /**
     * Prepare a date for array / JSON serialization.
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    /**
     * Set the value attribute by converting it to an integer.
     *
     * @param string $value The value to be set.
     * @throws \Exception If the value conversion fails.
     */
    public function setValueAttribute(string $value): void
    {
        $this->attributes['value'] = (int)(10_000 * (float)str_replace(',', '.', $value));
    }

    /**
     * Set the vunit_rate attribute by converting the given string value to an integer.
     *
     * @param string $value The value to be converted and set.
     * @return void
     */
    public function setVunitRateAttribute(string $value): void
    {
        $this->attributes['vunit_rate'] = (int)(1_000_000 * (float)str_replace(',', '.', $value));
    }

    /**
     * Get the value attribute.
     *
     * @param int $value The value to be divided.
     * @return float|int The value divided by 10,000.
     */
    public function getValueAttribute(int $value): float|int
    {
        return $value / 10_000;
    }

    /**
     * Get the vunit rate attribute.
     *
     * @param int $value The value to be divided.
     * @return float|int The value divided by 1,000,000.
     */
    public function getVunitRateAttribute(int $value): float|int
    {
        return $value / 1_000_000;
    }
}

