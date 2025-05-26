<div class="d-flex">
    <x-avatar :src="$seller->avatar" />
    <div class="ms-2">
        {{ $seller->name }} <br>
        @<a href="{{ route('admin.sellers.profile',$seller->username) }}" class="fw-bold link-primary small">{{ $seller->username }}</a>
    </div>
</div>
