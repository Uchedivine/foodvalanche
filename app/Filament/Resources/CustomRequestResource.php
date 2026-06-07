<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomRequestResource\Pages;
use App\Models\CustomRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomRequestResource extends Resource
{
    protected static ?string $model = CustomRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Customer Details')->schema([
                Forms\Components\TextInput::make('name')->disabled(),
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('phone')->disabled(),
                Forms\Components\TextInput::make('occasion')->disabled(),
            ])->columns(2),

            Forms\Components\Section::make('Request Details')->schema([
                Forms\Components\Textarea::make('request_details')
                    ->disabled()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('quantity_estimate')->disabled(),
                Forms\Components\TextInput::make('budget')
                    ->formatStateUsing(fn ($state) => $state ? '₦' . number_format($state / 100, 2) : null)
                    ->disabled(),
                Forms\Components\DatePicker::make('preferred_date')->disabled(),
            ])->columns(2),

            Forms\Components\Section::make('Admin Response')->schema([
                Forms\Components\Select::make('status')
                    ->options([
                        'new'       => 'New',
                        'reviewing' => 'Reviewing',
                        'quoted'    => 'Quoted',
                        'accepted'  => 'Accepted',
                        'declined'  => 'Declined',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('quoted_amount')
                    ->numeric()
                    ->helperText('Enter in kobo'),
                Forms\Components\Textarea::make('admin_note')
                    ->label('Internal Note (customer never sees this)')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('admin_response')
                    ->label('Response to Customer')
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('occasion'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray'    => 'new',
                        'warning' => 'reviewing',
                        'info'    => 'quoted',
                        'success' => 'accepted',
                        'danger'  => 'declined',
                    ]),
                Tables\Columns\TextColumn::make('preferred_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new'       => 'New',
                        'reviewing' => 'Reviewing',
                        'quoted'    => 'Quoted',
                        'accepted'  => 'Accepted',
                        'declined'  => 'Declined',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCustomRequests::route('/'),
            'create' => Pages\CreateCustomRequest::route('/create'),
            'edit'   => Pages\EditCustomRequest::route('/{record}/edit'),
        ];
    }
}