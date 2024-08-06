@include('header', [
    'scripts' => ['resources/css/app.css', 'resources/js/app.js', 'resources/js/product.js', 'resources/css/product.css'],
    'title' => $extra_fields['seo title'],
    'description' => $extra_fields['seo description'],
])
@include('navbar')
<main class="container-fluid">
    <h4>{{ $product->name }}</h4>
    <hr>
    <div class="grid">
        <div class="photos">
            @foreach($product->photos as $photo)
                <div><img src="{{ $photo->url }}" alt="{{ $product->name }}"></div>
            @endforeach
        </div>
        <div>
            <div>
                <h5>Описание:</h5>
                <p>{{ $product->description }}</p>
            </div>
        </div>
        <div>
            <div>
                <h5>Цена:</h5>
                <p>
                    <del>{{ $product->price }}</del>
                    <strong>{{ $product->price - $product->discount }} Рублей</strong>
                </p>
            </div>
            <div>
                <h5>Характеристики:</h5>
                <ul>
                    <li>Размер: {{ $extra_fields['Размер']}}</li>
                    <li>Цвет: {{ $extra_fields['Цвет'] }}</li>
                    <li>Бренд: {{ $extra_fields['Бренд'] }}</li>
                    <li>Состав: {{ $extra_fields['Состав'] }}</li>
                    <li>Кол-во в упаковке:{{ $extra_fields['Кол-во в упаковке'] }}</li>
                    <li>Вес товара(г): {{ $extra_fields['Вес товара(г)'] }}</li>
                    <li>Ширина(мм): {{ $extra_fields['Ширина(мм)'] }}</li>
                    <li>Высота(мм): {{ $extra_fields['Высота(мм)'] }}</li>
                    <li>Длина(мм): {{ $extra_fields['Длина(мм)'] }}</li>
                    <li>Вес упаковки(г): {{ $extra_fields['Вес упаковки(г)'] }}</li>
                    <li>Ширина упаковки(мм): {{ $extra_fields['Ширина упаковки(мм)'] }}</li>
                    <li>Высота упаковки(мм): {{ $extra_fields['Высота упаковки(мм)'] }}</li>
                    <li>Длина упаковки(мм): {{ $extra_fields['Длина упаковки(мм)'] }}</li>
                </ul>
            </div>
        </div>
    </div>
</main>
@include('footer')
