<div class="d-flex">
    <x-avatar :src="$seller->avatar" />
    <div class="ms-2">
        <a href="{{ route('admin.sellers.profile',$seller->username) }}" class="fw-bold link-primary small">
            {{ $seller->business_name }}
        </a>
    </div>
</div>