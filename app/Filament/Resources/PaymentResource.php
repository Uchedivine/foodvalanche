<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('order.order_number')->disabled(),
                Forms\Components\TextInput::make('gateway')->disabled(),
                Forms\Components\TextInput::make('gateway_reference')->disabled(),
                Forms\Components\TextInput::make('status')->disabled(),
                Forms\Components\TextInput::make('amount')
                    ->formatStateUsing(fn ($state) => '₦' . number_format($state / 100, 2))
                    ->disabled(),
                Forms\Components\TextInput::make('expected_amount')
                    ->formatStateUsing(fn ($state) => '₦' . number_format($state / 100, 2))
                    ->disabled(),
                Forms\Components\DateTimePicker::make('initiated_at')->disabled(),
                Forms\Components\DateTimePicker::make('paid_at')->disabled(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->searchable()
                    ->label('Order'),
                Tables\Columns\TextColumn::make('gateway_reference')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger'  => ['failed', 'partial_payment'],
                        'gray'    => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($state) => '₦' . number_format($state / 100, 2)),
                Tables\Columns\TextColumn::make('expected_amount')
                    ->formatStateUsing(fn ($state) => '₦' . number_format($state / 100, 2))
                    ->label('Expected'),
                Tables\Columns\TextColumn::make('initiated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'         => 'Pending',
                        'paid'            => 'Paid',
                        'partial_payment' => 'Partial Payment',
                        'failed'          => 'Failed',
                        'refunded'        => 'Refunded',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit'   => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}