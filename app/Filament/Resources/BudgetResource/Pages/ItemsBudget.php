<?php

namespace App\Filament\Resources\BudgetResource\Pages;

use App\Filament\Resources\BudgetResource;
use App\Http\Controllers\Dashboard\Print\BudgetController;
use App\Models\Budget;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class ItemsBudget extends Page
{
    use InteractsWithForms;

    public $record = null;

    protected static ?string $model = Budget::class;
    protected static string $resource = BudgetResource::class;
    protected static string $view = 'filament.resources.budget-resource.pages.items-budget';

    public Budget $budget;
    public function mount($record): void
    {
        $this->record = $record;
        $this->budget = Budget::query()->findOrFail($this->record);
    }

       protected function getHeaderActions(): array
    {
        return [

            Action::make(__('Send Email'))
                ->icon('heroicon-o-envelope')
                ->color('warning')
                ->action(function (array $data) {

                    $record = $this->budget->id;

                    $sendMail = new BudgetController;

                    $send = $sendMail->sendEmail($record, $data);

                    if($send == true){

                        Notification::make()
                            ->title('Email sent successfully!')
                            ->success()
                            ->send();
                    }

                })
                ->form(function () {
                    $record = $this->budget;

                    // Carregar os dados do cliente e orçamento para preencher os campos
                    $customer = $record->customer; // Relacionamento para cliente (ajuste o nome do relacionamento se necessário)

                    return [
                        TextInput::make('budget.name')
                            ->label('Subject')
                            ->default($record->name ?? '') // Nome do orçamento
                            ->translateLabel()
                            ->required(),

                        TextInput::make('customer.name')
                            ->label('Customer')
                            ->default($customer->name ?? '') // Nome do cliente
                            ->translateLabel()
                            ->required(),

                        TextInput::make('recipient_email')
                            ->label('Recipient Email')
                            ->default($customer->email ?? '') // Email do cliente
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('additional_emails')
                            ->label('Additional Emails')
                            ->maxLength(255),

                        RichEditor::make('message')
                            ->label('Message')
                            ->columnSpanFull(),
                        ];
                })
                ->modalHeading('Send Email')
                ->modalButton('Send')
                ->modalWidth('lg'),

            Action::make('Descargar PDF')
            ->url(route('budget.pdf', $this->record))
                ->icon('heroicon-o-document-text')
                ->color('success'),

            Action::make(__('Previsualizar'))
                ->url(route('budget.print', $this->record))
                ->icon('heroicon-o-eye')
                ->color('danger')
                ->openUrlInNewTab(),

            Action::make(__('Back'))
                ->url('/dashboard/budgets')
                ->icon('heroicon-o-arrow-uturn-left'),

        ];
    }

}

