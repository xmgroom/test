@if (count($errors) > 0)
    <div class="alert alert-danger">
        <button  type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        @if(is_object($errors))
            @foreach($errors->all() as $error)
                <strong>{{ $error }}!</strong>
            @endforeach
        @elseif(is_array($errors))
            @foreach($errors as $error)
                <strong>{{ $error }}!</strong>
            @endforeach
        @else
            <strong>{{ $errors }}!</strong>
        @endif
    </div>
@endif