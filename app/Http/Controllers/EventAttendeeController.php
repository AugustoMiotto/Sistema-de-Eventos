<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventAttendeeController extends Controller
{
    // 1. Exibe a lista na tela
    public function index(Event $event)
    {
        // Proteção: Apenas o dono do evento pode ver a lista
        if ($event->promoter_id !== auth()->id()) {
            abort(403, 'Acesso negado. Apenas o organizador pode ver os inscritos.');
        }

        // Puxa os inscritos fazendo um JOIN com a tabela de usuários
        $attendees = DB::table('registrations')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->where('registrations.event_id', $event->id)
            ->select(
                'users.name',
                'users.email',
                'registrations.status',
                'registrations.attended',
                'registrations.created_at',
                'registrations.deleted_at'
            )
            ->orderBy('registrations.created_at', 'desc')
            ->get();

        return view('events.attendees', compact('event', 'attendees'));
    }

    // 2. Gera e baixa o arquivo CSV
    public function exportCsv(Event $event)
    {
        if ($event->promoter_id !== auth()->id()) {
            abort(403);
        }

        $attendees = DB::table('registrations')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->where('registrations.event_id', $event->id)
            ->select('users.name', 'users.email', 'registrations.status', 'registrations.attended', 'registrations.created_at')
            ->orderBy('registrations.created_at', 'desc')
            ->get();

        $fileName = 'inscritos_' . \Illuminate\Support\Str::slug($event->name) . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Callback que monta o arquivo linha por linha na memória
        $callback = function() use($attendees) {
            $file = fopen('php://output', 'w');

            // Adiciona o BOM do UTF-8 para o Excel reconhecer os acentos (ã, é, í) perfeitamente
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeçalhos das Colunas (Usando ; para abrir perfeitamente no Excel brasileiro)
            fputcsv($file, ['Nome do Participante', 'E-mail', 'Data da Inscrição', 'Situação', 'Presença'], ';');

            foreach ($attendees as $user) {
                $statusStr = $user->status === 'Registered' ? 'Confirmado' : 'Cancelado';
                $attendedStr = $user->attended ? 'Presente' : 'Ausente';
                $dateStr = \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i');

                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $dateStr,
                    $statusStr,
                    $attendedStr
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
