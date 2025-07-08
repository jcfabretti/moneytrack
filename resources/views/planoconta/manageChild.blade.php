<!--  planoconta.manageChild -->
<ul name="childrenRow" id="{{$categChildren }}" style="display:none;">
    @foreach($childs as $child)
    <li style="margin-left: 70px;" name="childrenRow" id="children-{{$child->id}}">
        @if ($child->level<"3") @endif 
        <span class="custom-checkbox">
            <input type="checkbox" id="checkbox-{{ $child->id}}" onchange="uncheckOthers(this)" value="1">
            <label for="checkbox-{{ $child->id }}"></label>
        </span>
        {{ formatarNumeroCategoria($child->id) . '-' . $child->nome }}
        

        <!-- ACTIONS - EDIT / DELETE -->

            <span class="material-icons md-45" style="cursor: pointer;"
                onclick="loadEditCategoria({{ $child->id }})" title="Editar">
                edit
            </span>
            <span class="material-icons md-45" style="cursor: pointer;"
                onclick="loadDeleteCategoria({{ $child->id }})" title="Excluir">
                delete
            </span>

        <!-- End of ACTIONS -->


        @if(count($child->children))        @include('planoconta.manageChild',['childs' => $child->children])
        @endif
    </li>
    @endforeach
</ul>  
<!--  eof planoconta.manageChild -->

