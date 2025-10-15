<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/README', function () {
    $path = base_path('README.md');

    if (!\Illuminate\Support\Facades\File::exists($path)) {
        abort(404, 'README.md não encontrado.');
    }

    if (!File::exists($path)) {
        abort(404, 'README.md não encontrado.');
    }

    if (class_exists(\League\CommonMark\CommonMarkConverter::class)) {
        try {
            $markdown = File::get($path);
            $converter = new \League\CommonMark\CommonMarkConverter();
            $html = $converter->convertToHtml($markdown);

            return response(
                '<!doctype html><html lang="pt-BR">
                    <head>
                        <meta charset="utf-8">
                        <meta name="viewport" content="width=device-width,initial-scale=1">
                        <title>README</title>
                        <style>
                            body{font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial;padding:24px;line-height:1.6;color:#111;background:#fff;max-width:900px;margin:0 auto}
                            pre{background:#f6f8fa;padding:12px;border-radius:6px;overflow:auto}
                            code{background:#f6f8fa;padding:3px 6px;border-radius:4px;}
                            h1,h2,h3{border-bottom:1px solid #eee;padding-bottom:4px;}
                        </style>
                    </head>
                    <body>' . $html . '</body>
                </html>'
            , 200)->header('Content-Type', 'text/html; charset=utf-8');
        } catch (\Throwable $e) {
            // fallback para texto simples
        }
    }

    return response(File::get($path), 200)
        ->header('Content-Type', 'text/plain; charset=utf-8');
})->name('readme');


Route::get('/postman', function () {
    $path = public_path('docs/Postman.pdf');

    if (!File::exists($path)) {
        abort(404, 'Arquivo Postman.pdf não encontrado em public/docs.');
    }

    if (request()->query('dl') === '1') {
        return response()->download($path, 'Postman.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    return Response::file($path, [
        'Content-Type' => 'application/pdf',
    ]);
})->name('postman.pdf');
