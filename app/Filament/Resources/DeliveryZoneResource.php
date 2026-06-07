<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryZoneResource\Pages;
use App\Models\DeliveryZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DeliveryZoneResource extends Resource
{
    protected static ?string $model = DeliveryZone::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TagsInput::make('areas')
                    ->required()
                    ->helperText('Press Enter after each area name')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('delivery_fee')
                    ->required()
                    ->numeric()
                    ->prefix('₦')
                    ->helperText('Enter in kobo. e.g. 50000 = ₦500'),

                Forms\Components\TextInput::make('estimated_minutes')
                    ->numeric()
                    ->suffix('mins'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('areas')
                    ->formatStateUsing(fn ($state) => implode(', ', $state))
                    ->wrap(),
                Tables\Columns\TextColumn::make('delivery_fee')
                    ->formatStateUsing(fn ($state) => '₦' . number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_minutes')
                    ->suffix(' mins')
                    ->label('Est. Time'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDeliveryZones::route('/'),
            'create' => Pages\CreateDeliveryZone::route('/create'),
            'edit'   => Pages\EditDeliveryZone::route('/{record}/edit'),
        ];
    }
}