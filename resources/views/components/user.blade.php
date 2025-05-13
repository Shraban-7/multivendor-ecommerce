<div class="d-flex">
    <x-avatar :src="$user->avatar" />
    <div class="ms-2">
        {{ $user->fullname }} <br>
        @<a href="{{ route('admin.customers.profile',$user->username) }}" class="fw-bold link-primary small">{{ $user->username }}</a>
    </div>
</div>
