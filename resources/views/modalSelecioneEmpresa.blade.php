<!-- Create Modal HTML -->
<div class="modal fade" data-bs-backdrop="static" id="ModalSelecioneEmpresa" tabindex="-1" tabindex="-1" role="dialog"
    aria-labelledby="selecioneEmpresa" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #b9d6da">
                <h5 class="modal-title">Selecione a Empresa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="formCad" id="formCad" method="POST" action="{{ url('/selecioneEmpresa/{$empresa_id}')}}">
                    @csrf
                    @method('post')
                    <div class="container">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="form-group">
                                    <select class="form-control valid" name="empresa_id" id="empresa_id" required data-val="true"
                                        data-val-required="Selecione Grupo Economico">
                                        @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>