<div class="modal fade" id="modalListaCategoria" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalHeader">Visualizar Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                @include('categoria.partials.tabela_categorias', ['categorias' => $categorias])
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-default" data-bs-dismiss="modal" value="Fechar">
            </div>
        </div>
    </div>
</div>

<style>
    .modal-footer {
        background-color: #92AFC2;
    }

    .modal-header {
        background-color: #92AFC2;
        color: white;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 10px;
    }

    th {
        background-color: #143f6b;
        color: #fff;
        text-align: left;
    }

    .level-1 {
        padding-left: 10px;
    }

    .level-2 {
        padding-left: 30px;
    }

    .level-3 {
        padding-left: 50px;
    }

    .action-icons {
        cursor: pointer;
        margin-right: 10px;
        font-size: 18px;
    }

    .action-icons.delete {
        color: red;
    }

    .action-icons.edit {
        color: orange;
    }
</style>

<script>
    function loadCreateCategoria() {
        $('#modalListaCategoria').modal('show');
    }

    function loadEditCategoria(id, nome, categoria_pai, nivel, fk_tipocategoria_id) {
        // Carrega os dados no modal de edição. Use os IDs corretos dos campos do seu modal.
        $('#editModalCategoria #edit_id').val(id);
        $('#editModalCategoria #edit_nome').val(nome);
        $('#editModalCategoria #edit_categoria_pai').val(categoria_pai);
        $('#editModalCategoria #edit_nivel').val(nivel);
        $('#editModalCategoria #edit_fk_tipocategoria_id').val(fk_tipocategoria_id);
        $('#editModalCategoria').modal('show'); // Abre o modal de edição.
    }

    function loadDeleteModal(id, nome) {
        // Carrega os dados no modal de exclusão. Use os IDs corretos do seu modal.
        $('#deleteModalCategoria #delete_id').val(id);
        $('#deleteModalCategoria #delete_nome').text(nome); // Exibe o nome no modal.
        $('#deleteModalCategoria').modal('show'); // Abre o modal de exclusão.
    }

    document.addEventListener("DOMContentLoaded", function() {
        const table = document.getElementById('categoria-table');

        table.addEventListener('click', function(e) {
            if (e.target.classList.contains('toggle-icon')) {
                const currentIcon = e.target;
                const parentId = currentIcon.dataset.id;
                const nivel = parseInt(currentIcon.closest('tr').dataset.nivel);
                const rows = table.querySelectorAll(`tr[data-parent-id='${parentId}']`);

                const isExpanded = currentIcon.classList.contains('fa-minus-circle');
                currentIcon.classList.toggle('fa-plus-circle', isExpanded);
                currentIcon.classList.toggle('fa-minus-circle', !isExpanded);

                rows.forEach(row => {
                    row.style.display = isExpanded ? 'none' : '';

                    // Colapsar níveis abaixo
                    const childId = row.dataset.id;
                    const childRows = table.querySelectorAll(`tr[data-parent-id='${childId}']`);
                    childRows.forEach(childRow => {
                        childRow.style.display = 'none';
                        const childIcon = childRow.querySelector('.toggle-icon');
                        if (childIcon) {
                            childIcon.classList.remove('fa-minus-circle');
                            childIcon.classList.add('fa-plus-circle');
                        }
                    });
                });
            }
        });
    });
</script>