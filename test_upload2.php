<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if (route:listuser) {
    echo "No user found\n";
    exit;
}

// simulate a fake image
$file = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');

$request = Illuminate\Http\Request::create('/items', 'POST', [
    'name' => 'Buku Cetak',
    'description' => 'Ini adalah buku teks yang sangat bagus sekali.',
    'category' => 'buku',
    'type' => 'gift',
    'condition' => 'baik',
    'legacy_message' => 'Pesan legacy yang panjang bangetttt',
    '_token' => csrf_token(),
], [], ['images' => [$file]]);
$request->setUserResolver(function() use ($user) { return $user; });

$controller = app()->make(\App\Http\Controllers\User\ItemController::class);

try {
    $itemRequest = \App\Http\Requests\ItemRequest::createFrom($request);
    $itemRequest->setContainer(app());
    $itemRequest->validateResolved();

    $response = $controller->store($itemRequest);
    echo "Response status: " . $response->getStatusCode() . "\n";
    if ($response->isRedirect()) {
        echo "Redirecting to: " . $response->getTargetUrl() . "\n";
    }
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation failed:\n";
    print_r($e->errors());
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

