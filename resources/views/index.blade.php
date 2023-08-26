<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TestMe</title>

    <!-- Fonts -->
{{--    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">--}}

    <!-- Styles -->
{{--    <style>--}}
{{--        body {--}}
{{--            font-family: 'Nunito', sans-serif;--}}
{{--        }--}}
{{--    </style>--}}
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('js/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
{{--    Laravel v<?php echo e(Illuminate\Foundation\Application::VERSION); ?> (PHP v<?php echo e(PHP_VERSION); ?>)--}}
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
<?php /**PATH /var/www/TestMe/resources/views/welcome.blade.php ENDPATH**/ ?>