<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutionResource\Pages;
use App\Filament\Resources\InstitutionResource\RelationManagers;
use App\Models\Institution;
use App\Models\InstitutionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'خوێندنگاکان';

    protected static ?string $modelLabel = 'خوێندنگا';

    protected static ?string $pluralModelLabel = 'خوێندنگاکان';

    protected static ?string $navigationGroup = 'سەرەکی';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nku';

    public static function getNavigationBadge(): ?string
    {
        $pending = static::getModel()::where('approved', false)->count();
        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ─── وێنە و زانیاری سەرەکی ───
                Forms\Components\Section::make('زانیاری سەرەکی')
                    ->description('زانیاریە گرنگەکان دابنێ')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('وێنەی دامەزراوە')
                            ->image()
                            ->directory('logos')
                            ->disk('public')
                            ->imagePreviewHeight('200')
                            ->maxSize(20000)
                            
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('nku')
                            ->label('ناوی کوردی')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('ناوی خوێندنگاکە بە کوردی'),
                        Forms\Components\Select::make('type')
                            ->label('جۆر')
                            ->required()
                            ->native(false)
                            ->live()
                            ->options(fn () => InstitutionType::active()->ordered()
                                ->get()
                                ->mapWithKeys(fn ($t) => [$t->key => ($t->emoji ? $t->emoji . ' ' : '') . $t->name])
                                ->toArray()
                            ),
                        Forms\Components\Toggle::make('approved')
                            ->label('پەسەندکراو')
                            ->default(false)
                            ->helperText('ئەگەر چالاک بکەیت، لە ئەپەکەدا دەردەکەوێت')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('country')
                            ->label('وڵات / هەرێم')
                            ->options([
                                'کوردستان' => '🏔️ کوردستان',
                                'عێراق'    => '🇮🇶 عێراق',
                            ])
                            ->default('کوردستان')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('city')
                            ->label('شار')
                            ->datalist([
                                'هەولێر', 'سلێمانی', 'دهۆک', 'زاخۆ', 'ئامێدی', 'سیمێل', 'شێخان', 'دیانا', 'چۆمان', 'سۆران',
                                'کەرکووک', 'هەڵەبجە', 'رانیە', 'کەلار', 'قلادزێ', 'دوکان', 'دەربەندیخان', 'کفری', 'چەمچەماڵ',
                                'شارەزووری', 'پێنجوێن', 'سەید سادق', 'دوزەخوڕماتو', 'بەغداد', 'مووسڵ', 'بەسرە', 'نەجەف',
                                'کەربەلا', 'حیللە', 'سامەراء', 'تکریت', 'رمادی', 'فەللووجە', 'نەسیریە', 'عەماره', 'کووت',
                                'دیوانیە', 'بعقووبە', 'سینجار', 'تەلاعەفەر'
                            ])
                            ->placeholder('شار هەڵبژێرە یان بنووسە...')
                            ->required(),
                        Forms\Components\TextInput::make('addr')
                            ->label('ناونیشان')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-map-pin')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('lat')
                            ->label('Latitude')
                            ->numeric()
                            ->placeholder('36.191200'),
                        Forms\Components\TextInput::make('lng')
                            ->label('Longitude')
                            ->numeric()
                            ->placeholder('44.009400'),
                    ])->columns(2),

                // ─── دامەزران و ئامار ───
                Forms\Components\Section::make('ئامارەکان')
                    ->description('ساڵی دامەزران و ژمارەی قوتابییان')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\TextInput::make('founded_year')
                            ->label('ساڵی دامەزران')
                            ->numeric()
                            ->minValue(1800)
                            ->maxValue(date('Y'))
                            ->placeholder('1990')
                            ->prefixIcon('heroicon-o-calendar'),
                        Forms\Components\TextInput::make('students_count')
                            ->label('ژمارەی قوتابی')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('500')
                            ->prefixIcon('heroicon-o-user-group'),
                    ])->columns(2)->collapsible(),

                // ─── ١. دەربارە ───
                Forms\Components\Section::make('دەربارە')
                    ->description('زانیاری دەربارەی دامەزراوەکە')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\TextInput::make('nen')
                            ->label('ناوی ئینگلیزی')
                            ->maxLength(255)
                            ->placeholder('بۆ نموونە: University of Kurdistan'),
                        Forms\Components\TextInput::make('nar')
                            ->label('ناوی عەرەبی')
                            ->maxLength(255)
                            ->placeholder('اسم المؤسسة بالعربية'),
                        Forms\Components\Textarea::make('desc')
                            ->label('وەسف (کوردی)')
                            ->rows(3)
                            ->placeholder('کورتەیەک لەسەر دامەزراوەکە...')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('desc_en')
                            ->label('وەسف (ئینگلیزی)')
                            ->rows(3)
                            ->placeholder('A short description in English...')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('desc_ar')
                            ->label('وەسف (عەرەبی)')
                            ->rows(3)
                            ->placeholder('وصف موجز بالعربية...')
                            ->columnSpanFull(),
                    ])->columns(2)->collapsible(),

                // ─── ٢. بەشەکان ───
                Forms\Components\Section::make('بەشەکان و پارەدان')
                    ->description('بەشەکان و لقەکانی دامەزراوەکە')
                    ->icon('heroicon-o-building-library')
                    ->schema([
                        Forms\Components\Repeater::make('colleges_data')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('ناوی بەش')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-building-library'),
                                Forms\Components\TextInput::make('fee')
                                    ->label('پارەدان (کرێ)')
                                    ->placeholder('بۆ نموونە: 1,500,000 IQD')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-banknotes')
                                    ->visible(fn (Forms\Get $get): bool => in_array($get('../../type'), ['priv', 'eve_uni', 'eve_inst'])),
                                Forms\Components\Repeater::make('departments')
                                    ->label('لقەکان')
                                    ->visible(fn (Forms\Get $get): bool => $get('../../type') !== 'inst2')
                                    ->simple(
                                        Forms\Components\TextInput::make('dept_name')
                                            ->placeholder('ناوی لق...')
                                            ->required()
                                            ->maxLength(255),
                                    )
                                    ->addActionLabel('زیادکردنی لق')
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                            ])
                            ->columns(1)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('زیادکردنی بەش')
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Forms\Get $get): bool => !in_array($get('type'), ['school', 'kg', 'dc']))
                    ->collapsible(),

                // ─── خەرجی و خزمەتگوزاری ───
                Forms\Components\Section::make('خەرجی و خزمەتگوزاری')
                    ->description('زانیاری خەرجی و خزمەتگوزاریەکان')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\TextInput::make('level')
                            ->label('ئاستی خوێندن')
                            ->maxLength(255)
                            ->placeholder('بۆ نموونە: بەکالۆریۆس، دیپلۆم...'),
                        Forms\Components\TextInput::make('fee')
                            ->label('خەرجی')
                            ->maxLength(255)
                            ->placeholder('بۆ نموونە: ١٥٠٠ دۆلار ساڵانە'),
                        Forms\Components\Textarea::make('tuition_plans')
                            ->label('پلانی خەرجی')
                            ->rows(2)
                            ->placeholder('وردەکاری پلانەکانی پارەدان...')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('meal')
                            ->label('خواردن')
                            ->maxLength(255)
                            ->placeholder('بۆ نموونە: خواردن دابین دەکرێت'),
                        Forms\Components\TextInput::make('uniform')
                            ->label('جلوبەرگ')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('books')
                            ->label('پەرتووک')
                            ->maxLength(255),
                    ])->columns(2)->collapsible(),

                // ─── ٣. KG/DC ───
                Forms\Components\Section::make('زانیاری باخچەی منداڵان')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\TextInput::make('kg_age')
                            ->label('تەمەنی وەرگرتن')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kg_hours')
                            ->label('کاتی کار')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kg_fee')
                            ->label('خەرجی')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kg_meal')
                            ->label('خواردن')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('kg_uniform')
                            ->label('جلوبەرگ')
                            ->maxLength(255),
                    ])->columns(2)
                    ->visible(fn (Forms\Get $get): bool => in_array($get('type'), ['kg', 'dc']))
                    ->collapsible(),

                // ─── ٥. پەیوەندی ───
                Forms\Components\Section::make('پەیوەندی')
                    ->description('زانیاری پەیوەندی')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('ژمارەی مۆبایل')
                            ->tel()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-phone'),
                        Forms\Components\TextInput::make('email')
                            ->label('ئیمەیڵ')
                            ->email()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-envelope'),
                        Forms\Components\TextInput::make('web')
                            ->label('وێبسایت')
                            ->url()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-globe-alt'),
                    ])->columns(2)->collapsible(),

                // ─── ٦. سۆشیال ───
                Forms\Components\Section::make('سۆشیال')
                    ->description('هەژمارەکانی تۆڕە کۆمەڵایەتییەکان')
                    ->icon('heroicon-o-share')
                    ->schema([
                        Forms\Components\TextInput::make('fb')
                            ->label('فەیسبووک')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('wa')
                            ->label('واتسئاپ')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('ig')
                            ->label('ئینستاگرام')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('tg')
                            ->label('تێلێگرام')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('yt')
                            ->label('یوتیوب')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                        Forms\Components\TextInput::make('tk')
                            ->label('تیک تۆک')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-link'),
                    ])->columns(2)->collapsible(),

                // ─── ٧. ڤیدیۆ ───
                Forms\Components\Section::make('ڤیدیۆ')
                    ->description('لینکی ڤیدیۆ لە یوتیوب یان هەر سەکۆیەک')
                    ->icon('heroicon-o-play-circle')
                    ->schema([
                        Forms\Components\TextInput::make('video')
                            ->label('لینکی ڤیدیۆ')
                            ->url()
                            ->maxLength(500)
                            ->prefixIcon('heroicon-o-play-circle')
                            ->placeholder('https://youtube.com/watch?v=...')
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('لۆگۆ')
                    ->disk('public')
                    ->state(fn ($record) => $record->logo ? str_replace('/storage/', '', $record->logo) : null)
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(fn () => null),
                Tables\Columns\TextColumn::make('nku')
                    ->label('ناو')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30)
                    ->description(fn ($record) => $record->addr ? \Illuminate\Support\Str::limit($record->addr, 30) : null)
                    ->tooltip(fn ($record) => $record->nku),
                Tables\Columns\TextColumn::make('type')
                    ->label('جۆر')
                    ->badge()
                    ->formatStateUsing(function (string $state): string {
                        $type = InstitutionType::where('key', $state)->first();
                        return $type ? $type->name : $state;
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'gov' => 'primary',
                        'priv' => 'success',
                        'inst5', 'inst2' => 'info',
                        'school' => 'warning',
                        'kg' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('approved')
                    ->label('پەسەند')
                    ->onColor('success')
                    ->offColor('danger')
                    ->sortable()
                    ->afterStateUpdated(function (bool $state): void {
                        \Filament\Notifications\Notification::make()
                            ->title($state ? 'خوێندنگاکە پەسەندکرا ✅' : 'خوێندنگاکە ڕەتکرایەوە')
                            ->success()
                            ->send();
                    }),

                Tables\Columns\TextColumn::make('phone')
                    ->label('مۆبایل')
                    ->searchable()
                    ->icon('heroicon-o-phone')
                    ->copyable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('بەروار')
                    ->since()
                    ->sortable()
                    ->color('gray')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('جۆر')
                    ->multiple()
                    ->options(fn () => InstitutionType::ordered()
                        ->pluck('name', 'key')
                        ->toArray()
                    ),
                Tables\Filters\TernaryFilter::make('approved')
                    ->label('بارودۆخ')
                    ->trueLabel('پەسەندکراو')
                    ->falseLabel('چاوەڕوان')
                    ->placeholder('هەمووی'),
                Tables\Filters\SelectFilter::make('city')
                    ->label('شار')
                    ->options(fn () => Institution::query()
                        ->whereNotNull('city')
                        ->where('city', '!=', '')
                        ->distinct()
                        ->pluck('city', 'city')
                        ->toArray()
                    )
                    ->searchable(),
            ])
            ->filtersFormColumns(3)
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->label('بینین'),
                    Tables\Actions\EditAction::make()->label('دەستکاری'),
                    Tables\Actions\DeleteAction::make()->label('سڕینەوە'),
                ])->tooltip('کردارەکان'),
            ])
            ->bulkActions([])
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstitutions::route('/'),
            'create' => Pages\CreateInstitution::route('/create'),
            'view' => Pages\ViewInstitution::route('/{record}'),
            'edit' => Pages\EditInstitution::route('/{record}/edit'),
        ];
    }
}
