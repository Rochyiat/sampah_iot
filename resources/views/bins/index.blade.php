<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Tempat Sampah</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
            background: #1ABC9C;
        }

        main {
            flex: 1;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        .copyright {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="/">Monitoring Tempat Sampah</a>
        </div>
    </nav>

    <main class="container mt-5 pt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4 text-center">Status Tempat Sampah</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Persentase Keterisian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $bin)
                            <tr>
                                <td>{{ $bin->id }}</td>
                                <td class="{{ $bin->status == 'penuh' ? 'table-danger' : 'table-success' }}">
                                    {{ $bin->status == 'penuh' ? 'Penuh' : 'Belum Penuh' }}
                                </td>
                                <td class="{{ $bin->fill_percentage >= 80 ? 'table-danger' : 'table-success' }}">
                                    {{ $bin->fill_percentage ?? 0 }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </main>

    <footer class="copyright py-4 text-center">
        <div class="container">
            <small>Kelompok 7 IoT</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
