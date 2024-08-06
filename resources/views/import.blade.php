@include('header', [
    'scripts' => ['resources/css/import.css', 'resources/js/app.js', 'resources/js/file_import.js'],
    'title' => 'Product Import',
])
@include('navbar')
        <main class="container">
            <div class="import container">
                <h4>Import product list</h4>
                <input type="file" accept=".xlsx" id="file_loader">
                <input type="button" id="send_button" value="Send" disabled>
            </div>
        </main>
@include('footer')
