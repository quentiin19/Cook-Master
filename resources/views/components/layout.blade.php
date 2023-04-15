@props(['admin', 'title', 'datatables'])

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title }}</title>
    <script src="https://kit.fontawesome.com/51d79ea4d7.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')

    {{-- Datatables CDN --}}
    @isset($datatables)
        <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
            crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    @endisset
</head>

<body>
    {{-- Navbar management --}}
    @isset($admin)
        <x-admin-navbar />
    @else
        <x-navbar />
    @endisset

    {{-- Content of the page --}}
    <div class="md:p-0 p-5">
        <x-alerts />
        {{ $slot }}
        <x-footer />
    </div>

    {{-- Datatables script --}}
    @isset($datatables)
        <script>
            $(document).ready(
                function() {
                    $('#listing-table').DataTable();
                }
            );
        </script>
    @endisset
</body>

</html>
