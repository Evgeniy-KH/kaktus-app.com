@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-12 mb-5">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <h2 class="tm-text-primary mb-3" style="text-align: center; font-weight: bold;">{{ __('You are logged in!') }}</h2>
            </div>
        </div>
    </div>
</div>


<script type="module">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
        }
    });

    function iniTable(data = {}) {
        $.ajax({
            url: '/api/admin/customers',
            method: 'get',
            dataType: 'json',
            data: data,
            success: function (data) {
                $("#tbody-id").empty();

                $.each(data, function (i, item) {
                    if (item.role === 0) {
                        item.role = 'Admin';
                    } else {
                        item.role = 'User';
                    }

                    let row = `<tr id="customer_${item.id}"><td class="column-name">${item.name}</td><td class="column-surname">${item.surname}</td><td class="column-email">${item.email}</td><td class="column-role">${item.role}</td><td>
                          <button type="button" class="text-success edit-btn" data-id="${item.id}">Edit</button>
                          <button type="button" class="text-danger delete-btn" data-id="${item.id}">Delete</button>
                          </td></tr>`;

                    $('#myTable').append(row);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error: ' + textStatus + ' - ' + errorThrown);
            }

        });
    }

    $(document).ready(function () {

    });


</script>
@endsection
