@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row vh-100">
    <div class="col-2 shadow bg-light position-static start-0 bg-light">
        <h1>Category</h1>
        @include('../links')
    </div>
    <div class="col-10 overflow-scroll">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h1 class="">Home Mebels</h1>

          <div class="d-flex gap-4"  >
            <button class="btn btn-warning fw-bolder" id="addMebel">Add new mebel</button>
            <div class="d-flex align-items-center">
                <span class="pe-2">OFF</span>
                <form id="toogle-data" class="form-check form-switch d-flex align-items-center" method="GET">
                    @csrf
                    <input class="form-check-input" name="toggle" {{ $toggle->toggle=='1' ? "checked" : '' }} type="checkbox" id="toggle">
                </form>
                <span> ON </span>
            </div>
        </div>


          <div class="modal fade show" id="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form class="modal-content modal-form" method="POST"  id="modal-form" enctype="multipart/form-data">

                    <div class="modal-header">
                        <h5 class="modal-title">Soft Mebels</h5>
                        <button type="button" class="btn-close closed" ></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="name_uz">Name UZ</label>
                            <input type="text" id="name_uz" name="name_uz" class="form-control ">
                        </div>
                        <div>
                            <label for="name_ru">Name RU</label>
                            <input type="text" id="name_ru" name="name_ru" class="form-control ">

                        </div>
                        <div>
                            <label for="size_uz">Size Uz</label>
                            <input type="text" id="size_uz" name="size_uz" class="form-control">
                        </div>
                        <div>
                            <label for="size_ru">Size Ru</label>
                            <input type="text" id="size_ru" name="size_ru" class="form-control">
                        </div>
                        <div>
                            <label for="material_uz">Material Uz</label>
                            <input type="text" id="material_uz" name="material_uz" class="form-control">
                        </div>
                        <div>
                            <label for="material_ru">Material Ru</label>
                            <input type="text" id="material_ru" name="material_ru" class="form-control">
                        </div>
                        <div>
                            <label for="price">Price</label>
                            <input type="text" id="price" name="price" class="form-control">
                        </div>
                        <div>
                            <label for="image">Image</label>
                            <input type="file" id="image" name="image" class="form-control">
                            <img src=""  class="img-fluid image" alt="">
                        </div>
                        <div>
                            <label for="user_id">USER {{ Auth::user()->name }}</label>
                            <input  type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}" class="form-control">
                            <input  type="hidden" id="toggle_id" name="toggle_id" value="{{ $toggle->id }}" class="form-control">
                            <input  type="hidden" id="id" name="id" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary closed" >Close</button>
                        <button type="button" class="btn btn-primary" id="save">Save</button>
                        <button type="button" class="btn btn-primary" id="update">Update</button>
                    </div>
                </form>
            </div>
          </div>

        </div>
        <div class="card-body">
            @include('../mebel')
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        /* get mebels by category */
        const btnAddMebel = $('#addMebel')
        const close       = $('.closed')
        const modal       = $('#modal')
        const edit        = $('.edit')
        const btnUpdate   = $('#update')
        const btnDestroy  = $('.delete')
        const btnSave     = $('#save')
        const form        = $('#modal-form')
        const image       = $('.image')
        const hidden_img  = $('#hidden_img')
        var url, table;


        table = $('#mebel-table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: "{{ route('homemebel.index') }}",
            columns: [
                {data: 'id',       name: 'id'},
                {data: 'name_uz',  name: 'name_uz'},
                {data: 'name_ru',  name: 'name_ru'},
                {data: 'size_uz',     name: 'size_uz'},
                {data: 'size_ru',     name: 'size_ru'},
                {data: 'material_uz', name: 'material_uz'},
                {data: 'material_ru', name: 'material_ru'},
                {data: 'price',    name: 'price'},
                {data: 'image',    name: 'image'},
                {data: 'user_id',  name: 'user_id'},
                {data: 'edit',     name: 'edit', orderable: true, searchable: true},
                {data: 'delete',   name: 'delete', orderable: true, searchable: true},
            ],
        });

        btnAddMebel.click(function () {
            btnUpdate.hide();
            btnSave.show();
            btnFlag=true
            image.hide()
            hidden_img.hide()
            modal.modal('show')
            form.trigger('reset');
            modal.find('.modal-title').text('Add new Mebel')
        })

        $('#toggle').change(function (e) {
            e.preventDefault();
            let bool = $('#toogle-data')
            let format = new FormData(bool[0])
            let check = e.target.checked
            console.log(format);

            $.ajax({
                type: 'GET',
                headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: `{{ url('/toggle/home/mebels') }}`,
                data: format,
                contentType:true,
                processData:false,
                cache:false,
                success:function(data){
                    console.log(data);
                }
            })

        });

        btnSave.click(function (e) {
            e.preventDefault()
            var datas = new FormData(form[0])
            $.ajax({
                type:"POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('homemebel.store') }}",
                contentType:false,
                processData:false,
                cache:false,
                data:datas,
                dataType: 'json',
                success:function(data){
                    if (data.success) {
                        table.draw()
                        form.trigger('reset')
                        modal.modal('hide')
                        console.log(data);
                        image.show()
                        hidden_img.show()
                    }else{
                        alert('This operation faild')
                    }
                },
                error:function(data){
                    console.error(data)
                }
            })
        })

        $(document).on('click', '.edit', function(e){
            modal.modal('show')
            btnUpdate.show();
            btnSave.hide();
            modal.find('.modal-title').text('Update The data of Mebel')
            let id = $(this).attr('id')
            console.log(id);
            $.ajax({
                url: `{{ url('/homemebel/edit/${id}') }}`,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                success: function(data){
                    $('#id').val(data.result.id)
                    $('#name_uz').val(data.result.name_uz)
                    $('#name_ru').val(data.result.name_ru)
                    $('#size_uz').val(data.result.size_uz)
                    $('#size_ru').val(data.result.size_ru)
                    $('#material_uz').val(data.result.material_uz)
                    $('#material_ru').val(data.result.material_ru)
                    $('#price').val(data.result.price)
                    $('#user_id').val(data.result.user_id)
                    $('#hidden_img').val(data.result.image)
                    image.attr('src', `{{ asset('storage') }}/${data.result.image}`)

                },
                error: function(data){
                    console.error(data.responseJSON);
                }
            })
        })

        btnUpdate.click(function (e) {
            var datas = new FormData(form[0])
            $.ajax({
                type:'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url:"{{ route('homemebel.update') }}",
                contentType:false,
                processData:false,
                cache:false,
                data:datas,
                success:function (data) {
                    if (data) {
                        table.draw()
                        form.trigger('reset')
                        modal.modal('hide')
                        console.log(data);
                    }else{
                        alert(data)
                    }
                },
                error: function(data){
                    console.error(data.responseJSON);
                }
            })
        })

        $(document).on('click', '.deleteData', function (e) {
            var dataId = $(this).attr('data-id')
            var el = $(this)
            console.log(dataId);
            if (confirm('Are you sere to delete this data')) {
                $.ajax({
                    type:"DELETE",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: `{{ url('/homemebel/destroy/${dataId}') }}`,
                    data:{
                        '_method':'delete',
                        '_token':'{{ csrf_token() }}',
                    },
                    success:function(data){

                        table.row(el.parents('tr')).remove().draw()
                        console.log(data);
                    },
                    error: function(data){
                        console.error(data.responseJSON);
                    }
                })
            }
        })

        close.click(function () {
            modal.modal('hide')
        })
    })
  </script>
@endpush
