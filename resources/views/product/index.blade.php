@extends('layouts.app')

@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
@endsection


@section('content')
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-3">
                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
    
                    @csrf
    
                    <fieldset>
                        <legend>Add New Product</legend>
    
                        <div
                            class="form-group {{ $errors->has('name')?'has-danger':'' }}">
                            <label for="name">Name</label>
                            <input type="text" class="form-control {{ $errors->has('name')?'is-invalid':'' }}" id="name" name="name" aria-describedby="Category Name" required placeholder="Enter category name">
                            
                            @if($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div
                            class="form-group {{ $errors->has('name')?'has-danger':'' }}">
                            <label for="name">Description</label>
                            <textarea name="description" id="description" cols="15" rows="5" class="form-control {{ $errors->has('name')?'is-invalid':'' }}" required placeholder="product decription here...."></textarea>

                            @if($errors->has('description'))
                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>

                        <div
                            class="form-group {{ $errors->has('price')?'has-danger':'' }}">
                            <label for="name">Price</label>
                            <input type="number" step="0.1" class="form-control {{ $errors->has('price')?'is-invalid':'' }}" id="price" name="price" aria-describedby="Category Name" required placeholder="Enter product price">
                           
                            @if($errors->has('price'))
                                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-3">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile02" name="image">
                                <label class="custom-file-label" for="inputGroupFile02" >Choose product image</label>
                              </div>
                            </div>
                            @if($errors->has('image'))
                                <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                          </div>
    
                        <div class="form-group">
                            <label for="parent_id">Select Category</label>
                            <select class="form-control select2" name="categories[]" multiple="multiple">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </fieldset>
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="page-header">
                <h1 id="tables">Products List</h1>
            </div>
            <br>
            <div class="col-md-6">
                <!--begin: Search Form -->
                <form autocomplete="off">
                    <div class="col-lg-4">
                        <label>Category:</label>
                        <select class="form-control search-input" data-col-index="0">
                            <option value="" disabled>Search by Category</option>
                            <option value="">ALL</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                </form>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary m-btn m-btn--icon" id="m_search">
                    <span>
                        <i class="la la-search"></i>
                        <span>Search</span>
                    </span>
                </button>
                &nbsp;&nbsp;
                <button class="btn btn-secondary m-btn m-btn--icon" id="m_reset">
                    <span>
                        <i class="la la-close"></i>
                        <span>Reset</span>
                    </span>
                </button>
            </div>
            <!--end: Search Form -->
        </div>
    </div>
    
        <div class="col-md-12">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="table_1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>ACTIONS</th>
                    </tr>
                </tfoot>
            </table>
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
<script src="{{ asset('js/datatables.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
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
            });

            let a = $("#table_1").DataTable({
                dom: "<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager Bfrltip'lp>>",
                buttons: [
                    'colvis',
                ],
                responsive: !0,
                lengthMenu: [5, 10, 25, 50, 100, 500, 1000],
                pageLength: 5,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                select: true,
                ajax: {
                    url: "{{ route('products.index') }}",
                    dataType: 'json'
                },
                columns: [{
                        data: "id", 
                    }, {
                        data: "name", 
                    }, {
                        data: "description", 
                    }, {
                        data: "price", 
                    }, {
                        data: "image", 
                    }, {
                        data: "category", 
                    }, {
                        data: "Actions" 
                }],
                columnDefs: [{
                    targets: -1,
                    title: "ACTIONS",
                    class: 'nowrap center',
                    orderable: !1,
                    render: function(a, e, t, n) {
                        return '<button data-id="'+ t.id +'" class="btn btn-danger btn-sm btn-delete" title="Delete Product"> X </button>'
                    }
                },{
                    targets: 0,
                    orderable: false,
                    render: function(a, e, t, n) {
                        return '<b>#' + a +'</b>'
                    } 
                },{
                    targets: 1,
                    orderable: true,
                    render: function(a, e, t, n) {
                        return a
                    } 
                },{
                    targets: 2,
                    orderable: false,
                    render: function(a, e, t, n) {
                        return a
                    }
                },{
                    targets: 3,
                    orderable: true,
                    render: function(a, e, t, n) {
                        return a
                    }
                },{
                    targets: 4,
                    orderable: false,
                    render: function(a, e, t, n) {
                        return '<img src="/storage/product/images/' + a +'" style="width: 70px;">'
                        
                    }
                },{
                    targets: 5,
                    orderable: false,
                    render: function(a, e, t, n) {
                        categories = t.categories.length ? '' : '<span class="badge badge-dark">Uncategorized</span>';
                        t.categories.forEach(element => categories += ' <span class="badge badge-dark">'+ element.name +'</span>');
                        return categories
                    }
                }]
            });
            $("#m_search").on("click", function(t) {
                t.preventDefault();
                var e = {};
                $(".search-input").each(function() {
                    var a = $(this).data("col-index");
                    e[a] ? e[a] += "|" + $(this).val() : e[a] = $(this).val()
                }), $.each(e, function(t, e) {
                    a.column(t).search(e || "", !1, !1)
                }), a.table().draw()
            });
            $("#m_reset").on("click", function(t) {
                t.preventDefault(), $(".search-input").each(function() {
                    $(this).val(""), a.column($(this).data("col-index")).search("", !1, !1)
                }), a.table().draw()
            });
            $('.select2').select2({maximumSelectionLength: 2, width: '100%'});
        },
        methods: {
            deleteModal: function ($id) {
                this.basicModal.url = '/products/' + $id;
                $('#m_modal_1').modal('show');
            },
        }
    });

</script>
@endsection
