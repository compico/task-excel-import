@include('header',[
        'scripts' => ['resources/css/app.css', 'resources/js/app.js'],
        'title' => 'Product list',
])
@include('navbar')
<main class="container">
    <table>
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Название</th>
            <th scope="col">Описание</th>
            <th scope="col">Цена</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <th scope="row"><a href="{{url("/product/" . $product->id)}}">Открыть</a></th>
                <th>{{ \Illuminate\Support\Str::limit($product->name, 20, $end='...') }}</th>
                <td>{{ \Illuminate\Support\Str::limit($product->description, 50, $end='...') }}</td>
                <td>{{ $product->price }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="breadcrumb">
        <ul>
            @isset($previous_page)
                <li><a href="{{ $previous_page }}">Previous Page</a></li>
            @endisset
            @isset($previous_page, $next_page)

            @endisset
            @isset($next_page)
                <li><a href="{{ $next_page }}">Next Page</a></li>
            @endisset
        </ul>
    </nav>
</main>
@include('footer')
