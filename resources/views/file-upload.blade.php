@extends('frontend.layouts.app')
@section('content')

<form method="POST" enctype="multipart/form-data" class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md space-y-4">
    @csrf

    <div>
        <label for="folder_name" class="block text-sm font-medium text-gray-700 mb-1">Folder Name</label>
        <input
            type="text"
            name="folder_name"
            id="folder_name"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <div>
        <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Upload Images</label>
        <input
            type="file"
            name="images[]"
            id="images"
            multiple
            required
            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    </div>

    <div>
        <button
            type="submit"
            class="w-full bg-blue-600 text-white font-medium py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
            Upload
        </button>
    </div>
</form>



@endsection