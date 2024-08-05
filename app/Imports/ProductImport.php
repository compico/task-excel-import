<?php

declare(strict_types=1);

namespace App\Imports;

use App\Helper\PriceHelper;
use App\Models\ExtraField;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow {
    public function model(array $row)
    {
        /**
         * 'Группы' => 'Одежда Collant',
         * 'UUID' => 'a217f669-aa1a-11ee-0a80-00380034176d',
         * 'Тип' => 'Товар',
         * 'Код' => 'OmJ 7100_0',
         * 'Наименование' => 'Бермуды мужские, Grigio/Verde, OMSA, 46(M), РОССИЯ',
         * 'Внешний код' => '3UHfAid1jaMiwgBuNvnsf3',
         * 'Артикул' => NULL,
         * 'Единица измерения' => 'шт',
         * 'Цена: Цена продажи' => '1320,00',
         * 'Валюта (Цена продажи)' => 'руб',
         * 'Закупочная цена' => '880,00',
         * 'Валюта (Закупочная цена)' => 'руб',
         * 'Штрихкод EAN13' => '8000000784582',
         * 'Штрихкод EAN8' => NULL,
         * 'Штрихкод Code128' => NULL,
         * 'Штрихкод UPC' => NULL,
         * 'Штрихкод GTIN' => NULL,
         * 'Описание' => 'Мужские трикотажные домашние бермуды. Легкие, мягкие, дышащие и очень удобные. Универсальная длина ниже колена, пояс на эластичной ленте и карманы создают дополнительный комфорт.Отличный вариант для дома и отдыха. Бермуды стильно смотрятся благодаря эффектному рисунку и позволяют создать эффектный мужской образ.  Производство РОССИЯ',
         * 'Признак предмета расчета' => 'Товар',
         * 'Запретить скидки при продаже в розницу' => 'нет',
         * 'Минимальная цена' => '880,00',
         * 'Валюта (Минимальная цена)' => 'руб',
         * 'Страна' => 'Россия',
         * 'НДС' => 'без НДС',
         * 'Поставщик' => 'Collant',
         * 'Архивный' => 'нет',
         * 'Вес' => '0',
         * 'Весовой товар' => 'нет',
         * 'Маркированная продукция' => 'Нет',
         * 'Объем' => '0',
         * 'Содержит акцизную марку' => 'нет',
         * 'Доп. поле: Размер' => '46(M)',
         * 'Доп. поле: Цвет' => 'Grigio/Verde',
         * 'Доп. поле: Бренд' => 'OMSA',
         * 'Доп. поле: Состав' => '100% Хлопок',
         * 'Доп. поле: Кол-во в упаковке' => NULL,
         * 'Доп. поле: Ссылка на упаковку' => 'http://catalog.collant.ru/pics/SNL-504038_p.jpg',
         * 'Доп. поле: Ссылки на фото' => 'http://catalog.collant.ru/pics/SNL-504038_m.jpg, http://catalog.collant.ru/pics/SNL-504038_b2.jpg, http://catalog.collant.ru/pics/SNL-504038_b1.jpg',
         * 'Доп. поле: seo title' => 'Купить Бермуды мужские, Grigio/Verde, OMSA, 46(M), РОССИЯ в интернет магазине creativeparadise.online',
         * 'Доп. поле: seo h1' => NULL,
         * 'Доп. поле: seo description' => 'Большой выбор Бермуды мужские, Grigio/Verde, OMSA, 46(M), РОССИЯ в интернет-магазине creativeparadise.online. Бесплатная доставка и постоянные скидки!',
         * 'Доп. поле: Вес товара(г)' => '200',
         * 'Доп. поле: Ширина(мм)' => '300',
         * 'Доп. поле: Высота(мм)' => '50',
         * 'Доп. поле: Длина(мм)' => '300',
         * 'Доп. поле: Вес упаковки(г)' => '400',
         * 'Доп. поле: Ширина упаковки(мм)' => '300',
         * 'Доп. поле: Высота упаковки(мм)' => '50',
         * 'Доп. поле: Длина упаковки(мм)' => '300',
         * 'Доп. поле: Категория товара' => 'Женщинам_Белье',
         * )
         */
        $price = PriceHelper::GetFloat($row['Цена: Цена продажи']);
        $discount = $price - PriceHelper::GetFloat($row['Минимальная цена']);

        /** @var Product $product */
        $product = Product::create([
            'external_code' => $row['Внешний код'],
            'name' => $row['Наименование'],
            'description' => $row['Описание'],
            'price' => $price,
            'discount' => $discount,
        ]);
        $productId = $product->id;

        foreach ($row as $key => $value) {
            if (
                $key === 'Доп. поле: Ссылка на упаковку'
                || $key === 'Доп. поле: Ссылки на фото'
            ) {
                continue;
            }

            if (str_contains($key, 'Доп.')) {
                ExtraField::create([
                    'key'=> str_replace('Доп. поле: ', '', $key),
                    'value' => $value ?? '',
                    'product_id' => $productId,
                ]);
            }
        }

        $photosUrl = explode(', ', $row['Доп. поле: Ссылки на фото']);
        $photosUrl[] = $row['Доп. поле: Ссылка на упаковку'];

        Log::info('new photos', ['urls' => $photosUrl]);

        foreach ($photosUrl as $photoUrl) {
            $response = Http::withHeaders([
                'accept' => 'image/avif,image/webp,image/png,image/svg+xml,*/*;q=0.8',
            ])->get($photoUrl);

            $fileName = sprintf(
                'downloads/%s.jpg',
                uniqid('photo_')
            );

            $file = Storage::drive('public')->put(
                    $fileName,
                    $response->body(),
                    'public'
            );

            $url = Storage::drive('public')->url($fileName);

            Log::debug('new file', [
                'path' => $fileName,
                'url' => $url,
            ]);

            $response->close();

            Photo::create([
                'product_id' => $productId,
                'url' => $url,
                'referer_url' => $photoUrl,
            ]);
        }
    }
}
