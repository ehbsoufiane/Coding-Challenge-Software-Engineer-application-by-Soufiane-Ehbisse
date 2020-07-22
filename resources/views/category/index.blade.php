@extends('layouts.app')

@section('stylesheets')
@endsection


@section('content')
<section>
    <div class="row">
        <div class="col-md-6">
            <form method="POST" action="{{ route('categories.store') }}">

                @csrf

                <fieldset>
                    <legend>Add Category</legend>

                    <div
                        class="form-group {{ $errors->has('name')?'has-danger':'' }}">
                        <label for="name">Name</label>
                        <input type="text"
                            class="form-control {{ $errors->has('name')?'is-invalid':'' }}"
                            id="name" name="name" aria-describedby="Category Name" required
                            placeholder="Enter category name">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <fieldset class="form-group">
                        <legend>Parent Category</legend>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="has_parent_category"
                                    @if(!count($categories)) disabled @endif v-model="has_parent_category">
                                Link to existing parent category?
                            </label>
                        </div>
                    </fieldset>

                    <div class="form-group" v-if="has_parent_category">
                        <label for="parent_id">Select Parent Category</label>
                        <select class="form-control" name="parent_id" id="parent_id">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Category</button>
                </fieldset>
            </form>
        </div>

        <div class="col-md-6">
            <div class="page-header">
                <h1 id="tables">Categories List</h1>
            </div>

            <ul>
                @foreach($categories as $category)
                    <li class="list-group-item"><strong>{{ $category->name }}</strong> <button
                            class="btn btn-danger btn-sm btn-delete" data-id="{{ $category->id }}">X</button></li>
                    @if(count($category->subcategory))
                        @foreach($category->subcategory as $subcategory)
                            <ul>
                                <li class="list-group-item">{{ $subcategory->name }} <button
                                        class="btn btn-danger btn-sm btn-delete"
                                        data-id="{{ $subcategory->id }}">X</button></li>
                            </ul>
                        @endforeach
                    @endif

                @endforeach
            </ul>

        </div>
    </div>

</section>


<basic-modal id="m_modal_1" title="Deleting!">
    <div slot="modal-body">
        <h6>Confirme Delete ?</h6>
    </div>
    <div slot="modal-footer">
        <form :action="basicModal.url" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger m-btn m-btn--custom m-btn--icon m-btn--air">Delete</button>
        </form>
    </div>
</basic-modal>
@endsection


@section('scripts')
<script>
    const app = new Vue({
        el: '#app',
        data: {
            basicModal: {
                url: ''
            },
            has_parent_category: false
        },
        mounted: function () {
            $(document).on('click', '.btn-delete', function () {
                app.deleteModal($(this).attr('data-id'));
            })
        },
        methods: {
            deleteModal: function ($id) {
                this.basicModal.url = '/categories/' + $id;
                $('#m_modal_1').modal('show');
            },
        }
    });

</script>
@endsection
