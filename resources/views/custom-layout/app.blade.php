<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    /*
    @vite(['resources/css/app.css','resources/js/app.js'])
    */
    ?>

    <link rel="stylesheet" href="{{ asset('public/build/assets/app-D8xU5fWe.css') }}">
    <style>

        
        .payslipBorder
        {
            border: 1px solid #808080;
            padding : 8px;
            margin-top : 8px;
            border-radius: 6px;
        }

        @media (prefers-color-scheme: dark) {
            .payslipTable {
                background-color: #111827;
                color: #ffffff;
            }

            .payslipBorder
            {
                border: 1px solid #ffffff;
                padding : 8px;
                margin-top : 8px;
                border-radius: 6px;
            }
        }


       
    </style>
    <script src="{{ asset('public/build/assets/app-DxvN5yQS.js') }}"></script>

</head>
<body class="dark:bg-gray-900">
    @livewire('navbar')

    <div style="margin: 1rem;">
        {{ $slot }}
    </div>

</body>
</html>