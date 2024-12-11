<?php

declare(strict_types=1);

if (! function_exists('instruction_key')) {
    function instruction_key(string $instructionId): string
    {
        return 'instruction-'.$instructionId;
    }
}

if (! function_exists('set_session_instruction')) {
    function set_session_instruction(array $instruction): void
    {
        $instructionSessionKey = instruction_key($instruction['guid']);

        session()->put($instructionSessionKey, $instruction);
    }
}

if (! function_exists('get_session_instruction')) {
    function get_session_instruction(string $instructionId): array|false
    {
        $instructionSessionKey = instruction_key($instructionId);

        if (! session()->has($instructionSessionKey)) {
            return false;
        }

        return session()->get($instructionSessionKey);
    }
}
