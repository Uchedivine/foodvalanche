<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Info')->schema([
                Forms\Components\TextInput::make('order_number')
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'ready'     => 'Ready',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'unpaid'          => 'Unpaid',
                        'paid'            => 'Paid',
                        'partial_payment' => 'Partial Payment',
                        'refunded'        => 'Refunded',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('estimated_ready_at')
                    ->label('Estimated Ready At'),
                Forms\Components\Toggle::make('requires_verification')
                    ->label('Requires Verification'),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Customer Info')->schema([
                Forms\Components\TextInput::make('guest_name')->disabled(),
                Forms\Components\TextInput::make('guest_phone')->disabled(),
                Forms\Components\TextInput::make('guest_email')->disabled(),
                Forms\Components\TextInput::make('table_identifier')
                    ->label('Table')->disabled(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->default('Guest')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning'  => 'pending',
                        'primary'  => 'confirmed',
                        'info'     => 'preparing',
                        'success'  => ['ready', 'delivered'],
                        'danger'   => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger'  => 'unpaid',
                        'success' => 'paid',
                        'warning' => 'partial_payment',
                        'gray'    => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('order_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn ($state) => '₦' . number_format($state / 100, 2))
                    ->sortable(),
                Tables\Columns\IconColumn::make('requires_verification')
                    ->boolean()
                    ->label('Verify?'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'ready'     => 'Ready',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid'          => 'Unpaid',
                        'paid'            => 'Paid',
                        'partial_payment' => 'Partial Payment',
                        'refunded'        => 'Refunded',
                    ]),
                Tables\Filters\TernaryFilter::make('requires_verification')
                    ->label('Needs Verification'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}