<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eksplor Pariwisata Toraja</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        header {
            background-color: #8B4513;
            color: white;
            padding: 20px 0;
        }
        header h1 {
            text-align: center;
            margin-bottom: 15px;
        }
        nav {
            background-color: #A0522D;
            padding: 10px 0;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 30px;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        nav a:hover {
            background-color: #8B4513;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            min-height: 500px;
        }
        footer {
            background-color: #8B4513;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Eksplor Pariwisata Toraja</h1>
        <nav>
            <ul>
                <li><x-nav-link href="/" text="Home" /></li>
                <li><x-nav-link href="/destinasi" text="Destinasi" /></li>
                <li><x-nav-link href="/kuliner" text="Kuliner" /></li>
                <li><x-nav-link href="/galeri" text="Galeri" /></li>
                <li><x-nav-link href="/kontak" text="Kontak" /></li>
            </ul>
        </nav>
    </header>

    <div >
  @yield('content')
    </div>

    <footer>
        <p>&copy; 2024 Eksplor Pariwisata Toraja. All Rights Reserved.</p>
    </footer>
</body>
</html>