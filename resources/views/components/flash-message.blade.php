@if ($message = Session::get('success'))
<x-alert :type="'success'">{{ $message }}</x-alert>
@endif

@if ($message = Session::get('error'))
<x-alert :type="'danger'">{{ $message }}</x-alert>
@endif

@if ($message = Session::get('warning'))
<x-alert :type="'warning'">{{ $message }}</x-alert>
@endif

@if ($message = Session::get('info'))
<x-alert :type="'info'">{{ $message }}</x-alert>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show my-3" role="alert">
  	<ul>
		@foreach($errors->getMessages() as $key => $value)
			<li>{{ $value[0] }}</li>
		@endforeach
	</ul>
  	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif