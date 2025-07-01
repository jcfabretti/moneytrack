<!-- resources/views/components/view-modal.blade.php -->
<div class="modal fade" id="viewModalLancamento" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="viewModalHeader">Consultar Documento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <dl class="row">
 
     {{--     <dt class="col-sm-4">ID</dt>
          <dd class="col-sm-8" id="viewModal_id">-</dd>
     --}}
          <dt class="col-sm-4">Nº Docto</dt>
          <dd class="col-sm-8" id="viewModal_numero_docto">-</dd>
          <dt class="col-sm-4">Data Lançto</dt>
          <dd class="col-sm-8" id="viewModal_data">-</dd>
        </dl>

        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
        
        <dl class="row">
          <dt class="col-sm-4">Partida</dt>
          <dd class="col-sm-8" id="viewModal_partida">-</dd>
          <dt class="col-sm-4">Contrapartida</dt>
          <dd class="col-sm-8" id="viewModal_contrapartida">-</dd>

          <dt class="col-sm-4">Categoria</dt>
          <dd class="col-sm-8" id="viewModal_categoria">-</dd>

          <dt class="col-sm-4">Histórico</dt>
          <dd class="col-sm-8" id="viewModal_historico">-</dd>
          <dt class="col-sm-4">Unidade</dt>
          <dd class="col-sm-8" id="viewModal_unidade">-</dd>
          <dt class="col-sm-4">Quantidade</dt>
          <dd class="col-sm-8" id="viewModal_quantidade">-</dd>

          <dt class="col-sm-4">Valor</dt>
          <dd class="col-sm-8" id="viewModal_valor" style="font-weight: bold;">-</dd>

          <dt class="col-sm-4">Data Vecto</dt>
          <dd class="col-sm-8" id="viewModal_vencimento">-</dd>

          <dt class="col-sm-4">Centro de Custo</dt>
          <dd class="col-sm-8" id="viewModal_centro_de_custo">-</dd>

        </dl>
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
        <dl class="row"> 
          <dt class="col-sm-4">Data Criação</dt>
          <dd class="col-sm-8" id="viewModal_date_created">-</dd>
          <dt class="col-sm-4">Criado por</dt>
          <dd class="col-sm-8" id="viewModal_quemCriou">-</dd>
          <dt class="col-sm-4">Data Alteração</dt>
          <dd class="col-sm-8" id="viewModal_date_updated">-</dd>
          <dt class="col-sm-4">Alterado por</dt>
          <dd class="col-sm-8" id="viewModal_quemAtualizou">-</dd>
          <dt class="col-sm-4">Origem</dt>
          <dd class="col-sm-8" id="viewModal_origem">-</dd>
        </dl>
      </div>
   
      <div class="modal-footer">
         <input type="button" class="btn btn-default" data-dismiss="modal" value="Fechar">
      </div>

    </div>
  </div>
</div>
<script>
    window.addEventListener('load', function() {
        const valorElement = document.getElementById('viewModal_valor');
        let valorText = valorElement.textContent;

        // Verifica se o valor contém um sinal de negativo e o reposiciona
        if (valorText.includes('-')) {
            valorText = valorText.replace('-', ''); // Remove o sinal de negativo
            valorText = valorText + '-';             // Adiciona o sinal ao final
            valorElement.textContent = valorText;    // Atualiza o conteúdo da dd
        }
    });
</script>