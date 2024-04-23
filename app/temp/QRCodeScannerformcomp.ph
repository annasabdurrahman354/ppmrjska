<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Field;
use Filament\Notifications\Notification;

class QRCodeScanner extends Field
{
    protected string $view = 'forms.components.qr-code-scanner';

    protected function setUp(): void
    {
        parent::setUp();
 
        $this->registerListeners([
            'qr::scanned' => [
                function (Component $component, string $customValue): void {
                    Notification::make()
                        ->title($customValue)
                        ->success()
                        ->send();
                    $component->state($customValue);
                }
            ]
        ]);
    }
}
