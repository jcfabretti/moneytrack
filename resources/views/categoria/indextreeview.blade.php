{{-- resources/views/categoria/indexTreeView.blade.php (SOMENTE AS LINHAS DA TABELA) --}}

@forelse ($categorias as $categoria)
    @if ($categoria->nivel == 1)
        <tr data-id="{{ $categoria->id }}" data-nivel="1" data-parent-id="0">
            <td class="level-1">
                <i class="fas fa-plus-circle toggle-icon" data-id="{{ $categoria->id }}"></i>
                <strong>{{formatarNumeroCategoria($categoria->numero_categoria) }} - {{ $categoria->nome }}</strong>
            </td>
            <td>{{ optional($categoria->updated_at)->format('d-m-Y') ?? 'N/A' }}
                {!! updateMessage($categoria->updated_at) !!}
            <td>
                <a href="" class="edit"
                    onclick="loadEditCategoria({{ $categoria->id }}, `{{ $categoria->nome }}`, `{{ $categoria->categoria_pai }}`,
               `{{ $categoria->nivel }}`, `{{ $categoria->fk_tipocategoria_id }}`)"
                    data-toggle="modal">
                    <span class="material-icons action-icons edit" title="Alterar">&#xE254;</span>
                </a>
                <div class="btn-group btn-group-sm">
                    <a href="" class="delete"
                        onclick="loadDeleteModal({{ $categoria->id }}, `{{ $categoria->nome }}`)"
                        data-toggle="modal">
                        <span class="material-icons action-icons delete" title="Apagar">&#xE872;</span>
                    </a>
                </div>
            </td>
        </tr>
        <!-- NIVEL 2 -->
        @foreach ($categoria->children as $child1)
            <tr data-id="{{ $child1->id }}" data-nivel="2" data-parent-id="{{ $categoria->id }}"
                style="display:none;">
                <td class="level-2">
                    <i class="fas fa-plus-circle toggle-icon" data-id="{{ $child1->id }}"></i>
                    <strong>{{ formatarNumeroCategoria($child1->numero_categoria) }} - {{ $child1->nome }}</strong>
                </td>
                <td>{{ optional($child1->updated_at)->format('d-m-Y') ?? 'N/A' }}
                    {!! updateMessage($child1->updated_at) !!}
                <td>
                    <a href="" class="edit"
                        onclick="loadEditCategoria({{ $child1->id }}, `{{ $child1->nome }}`, `{{ $child1->categoria_pai }}`,
                   `{{ $child1->nivel }}`, `{{ $child1->fk_tipocategoria_id }}`)"
                        data-toggle="modal">
                        <span class="material-icons action-icons edit" title="Alterar">&#xE254;</span>
                    </a>
                    <div class="btn-group btn-group-sm">
                        <a href="" class="delete"
                            onclick="loadDeleteModal({{ $child1->id }}, `{{ $child1->nome }}`)"
                            data-toggle="modal">
                            <span class="material-icons action-icons delete" title="Apagar">&#xE872;</span>
                        </a>
                    </div>
                </td>
            </tr>
            @foreach ($child1->children as $child2)
                <tr data-id="{{ $child2->id }}" data-nivel="3" data-parent-id="{{ $child1->id }}"
                    style="display:none;">
                    <td class="level-3">
                        <i data-id="{{ $child2->id }}"></i>
                        <li>{{ formatarNumeroCategoria($child2->numero_categoria) }} - {{ $child2->nome }}</li>
                    </td>
                    <td>{{ optional($child2->updated_at)->format('d-m-Y') ?? 'N/A' }}
                        {!! updateMessage($child2->updated_at) !!}
                    <td>
                        <a href="" class="edit"
                            onclick="loadEditCategoria({{ $child2->id }}, `{{ $child2->nome }}`, `{{ $child2->categoria_pai }}`,
                       `{{ $child2->nivel }}`, `{{ $child2->fk_tipocategoria_id }}`)"
                            data-toggle="modal">
                            <span class="material-icons action-icons edit" title="Alterar">&#xE254;</span>
                        </a>
                        <div class="btn-group btn-group-sm">
                            <a href="" class="delete"
                                onclick="loadDeleteModal({{ $child2->id }}, `{{ $child2->nome }}`)"
                                data-toggle="modal">
                                <span class="material-icons action-icons delete"
                                    title="Apagar">&#xE872;</span>
                            </a>
                        </div>
                    </div>
                </tr>
            @endforeach
        @endforeach
    @endif
@empty
    {{-- Isso será exibido se não houver categorias --}}
    <tr>
        <td colspan="3" class="text-center py-4">Nenhuma categoria encontrada para este tipo selecionado.</td>
    </tr>
@endforelse


