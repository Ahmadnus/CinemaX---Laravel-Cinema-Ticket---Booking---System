<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ app()->getLocale() == 'ar' ? 'قائمة الأفلام' : 'Movies List' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #222;
        }

        .language-switcher {
            text-align: center;
            margin-bottom: 30px;
        }

        .language-switcher a {
            text-decoration: none;
            color: #007bff;
            margin: 0 10px;
            font-weight: 600;
        }

        .language-switcher a:hover {
            text-decoration: underline;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            padding: 20px;
            box-sizing: border-box;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 20px;
            color: #007bff;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .card-text {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
        }

        .card-text strong {
            color: #333;
        }

        @media (max-width: 768px) {
            .card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ app()->getLocale() == 'ar' ? 'قائمة الأفلام' : 'Movies List' }}</h2>

        <div class="language-switcher">
            <a href="{{ route('movies.index', ['locale' => 'ar']) }}">العربية</a> |
            <a href="{{ route('movies.index', ['locale' => 'en']) }}">English</a>
        </div>

        <div class="row">
            @foreach($movies as $movie)
                <div class="card">
                    <div class="card-title">
                        {{ $movie->getTranslation('title', 'en') }} / {{ $movie->getTranslation('title', 'ar') }}
                    </div>
                    <div class="card-text">
                        <p><strong>{{ app()->getLocale() == 'ar' ? 'النوع' : 'Genre' }}:</strong> {{ $movie->genre ?? '-' }}</p>
                        <p><strong>{{ app()->getLocale() == 'ar' ? 'السنة' : 'Year' }}:</strong> {{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</p>
                        <p><strong>{{ app()->getLocale() == 'ar' ? 'المدة' : 'Duration' }}:</strong> {{ $movie->duration_min ? $movie->duration_min . ' mins' : '-' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
