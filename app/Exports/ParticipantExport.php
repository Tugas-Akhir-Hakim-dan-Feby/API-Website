<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ParticipantExport implements FromView
{
    protected $participants;

    public function __construct($participants)
    {
        $this->participants = $participants;
    }

    public function view(): View
    {
        return view('exports.participant', [
            "participants" => $this->participants
        ]);
    }
}
