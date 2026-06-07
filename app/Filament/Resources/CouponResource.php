<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                Forms\Components\Textarea::make('description'),

                Forms\Components\Select::make('type')
                    ->options([
                        'percentage' => 'Percentage (%)',
                        'fixed'      => 'Fixed Amount (₦)',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->helperText('For fixed type, enter in kobo. e.g. 100000 = ₦1,000'),

                Forms\Components\TextInput::make('min_order_amount')
                    ->numeric()
                    ->default(0)
                    ->helperText('Minimum order amount in kobo'),

                Forms\Components\TextInput::make('max_uses')
                    ->numeric()
                    ->nullable(),

                Forms\Components\DateTimePicker::make('starts_at'),
                Forms\Components\DateTimePicker::make('expires_at'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\BadgeColumn::make('type'),
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(fn ($record) => $record->type === 'percentage'
                        ? $record->value . '%'
                        : '₦' . number_format($record->value / 100, 2)),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Used'),
                Tables\Columns\TextColumn::make('max_uses')
                    ->label('Max'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }
}