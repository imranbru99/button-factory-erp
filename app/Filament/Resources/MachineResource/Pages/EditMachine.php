<?php

namespace App\Filament\Resources\MachineResource\Pages;

use App\Filament\Resources\MachineResource;
use App\Services\Automation\IssueAutomationEngine;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMachine extends EditRecord
{
    protected static string $resource = MachineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('breakdown')
                ->label('Report breakdown')
                ->color('danger')
                ->form([
                    Textarea::make('reason')->required(),
                ])
                ->action(function (array $data, IssueAutomationEngine $engine) {
                    $ticket = $engine->machineDown($this->record, $data['reason']);
                    Notification::make()->title('Ticket '.$ticket->number.' opened')->success()->send();
                }),
            DeleteAction::make(),
        ];
    }
}
