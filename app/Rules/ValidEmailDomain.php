<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 1. Verifica se o valor parece minimamente com um e-mail para não quebrar o PHP
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('O formato do e-mail fornecido é inválido.');
            return;
        }

        // 2. Separa o e-mail para pegar apenas a parte após o '@'
        $parts = explode('@', $value);
        $domain = array_pop($parts);

        // 3. A MÁGICA: Verifica se o domínio possui registros MX (Mail Exchange) ativos na internet.
        // Domínios como '999', 'ifrs' (sem .br) ou domínios falsos falharão aqui.
        if (!checkdnsrr($domain, 'MX')) {
            $fail('O domínio do e-mail (' . $domain . ') não parece possuir um servidor de e-mail válido. Verifique se digitou corretamente.');
        }
    }
}
