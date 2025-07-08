      {{-- ############################################################################################# --}}
        <!-- Create Modal HTML -->
        <div class="modal fade" id="addCategoria" name="addCategoria" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form name="addTreeView" id="addtree" method="POST" action="{{url('/home/add-category')}}">
                        @method('post')
                        @csrf

                        <h3>Add New Category</h3>

                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                        @endif

                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">

                            <div class="form-outline w-25">
                                <label for="title">Title:</label>
                                <input type="text" name='title' id="title" class="form-control" old="nome"
                                    style="text-transform:uppercase" /> <br>
                            </div>
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('level') ? 'has-error' : '' }}">

                            <div class="form-outline w-25">
                                <label for="title">level:</label>
                                <input type="text" name='level' id="level" class="form-control" old="level"
                                    style="text-transform:uppercase" /> <br>
                            </div>
                            <span class="text-danger">{{ $errors->first('level') }}</span>
                        </div>

                        <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                            <select class="form-control valid" name="parent_id" id='parent_id' required
                                data-val="true" data-val-required="Selecione Categoria">
                          
                                <option value="0" selected>RAIZ</option>
                                @foreach ($allCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success">Add New</button>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                            <input type="submit" class="btn btn-success" value="Gravar">

                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- ############################################################################################# --}}