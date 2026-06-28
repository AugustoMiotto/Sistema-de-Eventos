<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function store(Event $event)
    {
        $user = Auth::user();

        // 1. Olhar direto na tabela pivot usando o Query Builder para capturar até os Soft Deletes
        $existingRegistration = DB::table('registrations')
            ->where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            // Se a inscrição existe e está ativa (não sofreu soft delete)
            if (is_null($existingRegistration->deleted_at) && $existingRegistration->status === 'Registered') {
                return redirect()->back()->with('error', 'Você já está inscrito neste evento!');
            }

            // Se ela foi cancelada (via Soft Delete ou status Canceled), vamos reativá-la!
            // Isso evita o erro de "Duplicate entry" por causa da chave UNIQUE do seu banco.
            if (!is_null($existingRegistration->deleted_at) || $existingRegistration->status === 'Canceled') {

                // Verifica se ainda há vagas antes de reativar
                if ($event->users()->count() >= $event->max_slots) {
                    return redirect()->back()->with('error', 'Infelizmente as vagas para este evento já esgotaram.');
                }

                DB::table('registrations')
                    ->where('user_id', $user->id)
                    ->where('event_id', $event->id)
                    ->update([
                        'status' => 'Registered',
                        'deleted_at' => null, // Restaura o Soft Delete
                        'updated_at' => now()
                    ]);

                return redirect()->back()->with('success', 'Sua inscrição foi reativada com sucesso! Vaga garantida.');
            }
        }

        // 2. Se a inscrição nunca existiu antes, faz o fluxo normal:

        // Verifica limite de vagas
        if ($event->users()->count() >= $event->max_slots) {
            return redirect()->back()->with('error', 'Infelizmente as vagas para este evento já esgotaram.');
        }

        // Realiza a inscrição usando os ENUMS e campos corretos da sua migration
        $event->users()->attach($user->id, [
            'status' => 'Registered',
            'attended' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Sua inscrição foi realizada com sucesso! Vaga garantida.');
    }
    public function destroy(Event $event)
    {
        $user = Auth::user();

        // 1. Verifica se a inscrição ativa realmente existe
        $registration = DB::table('registrations')
            ->where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->whereNull('deleted_at')
            ->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'Inscrição não encontrada ou já cancelada.');
        }

        // 2. Aplica o cancelamento mudando o status e ativando o Soft Delete
        DB::table('registrations')
            ->where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->update([
                'status' => 'Canceled',
                'deleted_at' => now(),
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Sua inscrição foi cancelada com sucesso. Vaga liberada.');
    }
}
