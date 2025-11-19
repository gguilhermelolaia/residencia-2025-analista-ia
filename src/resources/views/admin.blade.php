<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Administrativa - Residência 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .card-draft { border-left: 5px solid #ffc107; background-color: #fffdf8; }
        .card-published { border-left: 5px solid #198754; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">🔒 Painel Administrativo</a>
            <a href="/" class="btn btn-outline-light btn-sm" target="_blank">Ver Site Visitante ➜</a>
        </div>
    </nav>

    <div class="container">
        
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
        @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif

        <div class="row">
            <div class="col-12 mb-3">
                <h4 class="text-muted">Gerenciamento de Análises</h4>
            </div>

            <div class="col-12">
                <div class="card mb-4 border-primary shadow-sm">
                    <div class="card-body bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold text-primary">🤖 Solicitar Nova Análise</h5>
                            <small class="text-muted">A IA irá processar o pedido automaticamente.</small>
                        </div>
                        <form action="{{ route('conteudos.solicitar') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="ticker" class="form-control" placeholder="Ex: AMER3" required style="width: 150px; text-transform: uppercase;">
                            <button type="submit" class="btn btn-primary">✨ Gerar Relatório</button>
                        </form>
                    </div>
                </div>
            </div>

            @foreach($conteudos as $item)
                <div class="col-12 mb-3">
                    <div class="card shadow-sm {{ $item->is_published ? 'card-published' : 'card-draft' }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-2">
                                        <h5 class="card-title mb-0 fw-bold">{{ $item->ticker }}</h5>
                                        @if($item->is_published)
                                            <span class="badge bg-success">Publicado</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Rascunho / Pendente</span>
                                        @endif
                                        <small class="text-muted ms-2">{{ $item->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    
                                    <p class="mt-2 text-secondary" style="white-space: pre-wrap;">{{ Str::limit($item->conteudo, 150) }}</p>
                                    
                                    <p class="mb-0">
                                        <strong>Veredito:</strong> 
                                        <span class="badge {{ str_contains($item->status, 'Compra') ? 'bg-success' : (str_contains($item->status, 'Venda') ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ $item->status }}
                                        </span>
                                    </p>
                                </div>

                                <div class="col-md-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                            ✏️ Editar
                                        </button>

                                        @if(!$item->is_published)
                                            <form action="{{ route('conteudos.aprovar', $item->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button class="btn btn-success btn-sm">✅ Aprovar</button>
                                            </form>

                                            <form action="{{ route('conteudos.reprovar', $item->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm">❌ Reprovar</button>
                                            </form>
                                        @else
                                            <form action="{{ route('conteudos.reprovar', $item->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar esta publicação?');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm">🗑️ Apagar</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('conteudos.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Análise: {{ $item->ticker }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Texto da Análise</label>
                                        <textarea name="conteudo_texto" class="form-control" rows="10">{{ $item->conteudo }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>