<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analista IA 2025 - Notícias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-bg { background: linear-gradient(to right, #141E30, #243B55); color: white; }
        .card-news { border: none; border-top: 4px solid #243B55; transition: transform 0.2s; }
        .card-news:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-light">

    <div class="header-bg py-5 mb-5 shadow">
        <div class="container text-center">
            <h1 class="display-5 fw-bold">📈 Mercado em Tempo Real</h1>
            <p class="lead">Análises financeiras geradas por Inteligência Artificial</p>
            <a href="/admin" class="btn btn-outline-light btn-sm mt-2">Área Restrita (Admin)</a>
        </div>
    </div>

    <div class="container">
        <div class="row">
            @if($conteudos->isEmpty())
                <div class="col-12 text-center text-muted">
                    <h4>Nenhuma análise publicada ainda.</h4>
                </div>
            @else
                @foreach($conteudos as $item)
                    <div class="col-md-6 mb-4">
                        <div class="card card-news shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h3 class="fw-bold text-dark mb-0">{{ $item->ticker }}</h3>
                                        <small class="text-muted">{{ $item->created_at->format('d/m/Y') }} às {{ $item->created_at->format('H:i') }}</small>
                                    </div>
                                    @if(str_contains($item->status, 'Compra'))
                                        <span class="badge bg-success fs-6">COMPRA</span>
                                    @elseif(str_contains($item->status, 'Venda'))
                                        <span class="badge bg-danger fs-6">VENDA</span>
                                    @else
                                        <span class="badge bg-secondary fs-6">NEUTRO</span>
                                    @endif
                                </div>
                                <p class="card-text text-secondary">{{ $item->conteudo }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <footer class="text-center py-5 text-muted">
        <small>&copy; 2025 Projeto Residência de Software</small>
    </footer>

</body>
</html>