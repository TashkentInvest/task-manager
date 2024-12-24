@extends('layouts.admin')

@section('content')
<h1 class="mb-4 text-center text-primary">Янги Ҳужжат Яратиш</h1>

<form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Title Field -->
    <div class="mb-3">
        <label for="title" class="form-label">Сарлавха:</label>
        <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" required>
    </div>



    <!-- Letter Number Field -->
    <div class="mb-3">
        <label for="letter_number" class="form-label">Хат Рақами:</label>
        <input type="text" name="letter_number" id="letter_number" value="{{ old('letter_number') }}" class="form-control" required>
    </div>

    <!-- Received Date Field -->
    <div class="mb-3">
        <label for="received_date" class="form-label">Қабул Қилинган Санаси:</label>
        <input type="datetime-local" name="received_date" id="received_date" value="{{ old('received_date') }}" class="form-control" required>
    </div>

    <!-- Main Category Dropdown -->
    <div class="mb-3">
        <label for="main-category" class="form-label">Асосий Категория:</label>
        <select id="main-category" name="category_id" class="form-select" required>
            <option value="">-- Асосий Категорияни Танланг --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Subcategory Dropdown (Hidden initially) -->
    <div id="subcategory-container" class="mb-3" style="display: none;">
        <label for="subcategory" class="form-label">Қўшимча Категория:</label>
        <select id="subcategory" name="document_category_id" class="form-select" required>
            <option value="">-- Қўшимча Категорияни Танланг --</option>
        </select>
    </div>

    <!-- Ministry Dropdown (Hidden initially) -->
    <div id="ministry-container" class="mb-3" style="display: none;">
        <label for="ministry" class="form-label">Вазирлик:</label>
        <select id="ministry" name="ministry_id" class="form-select">
            <option value="">-- Вазирликни Танланг --</option>
        </select>
    </div>

    <!-- File Upload -->
    <div class="mb-3">
        <label for="files" class="form-label">Файллар Қўшиш:</label>
        <input type="file" name="files[]" id="files" class="form-control" multiple>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary w-100">Ҳужжатни Яратиш</button>
</form>

<a href="{{ route('documents.index') }}" class="btn btn-secondary w-100 mt-3">Ҳужжатлар Рўйхатга Қайтиш</a>
</div>

    <a href="{{ route('documents.index') }}" style="margin-top: 20px; display: inline-block;">Back to Documents List</a>

    <script>
        const categories = @json($categories);
        const ministries = @json($ministries);

        document.getElementById('main-category').addEventListener('change', function () {
            const selectedCategoryId = this.value;
            const subcategoryContainer = document.getElementById('subcategory-container');
            const subcategorySelect = document.getElementById('subcategory');

            subcategorySelect.innerHTML = '<option value="">-- Select Subcategory --</option>';

            const selectedCategory = categories.find(category => category.id == selectedCategoryId);

            if (selectedCategory && selectedCategory.children.length > 0) {
                selectedCategory.children.forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    subcategorySelect.appendChild(option);
                });
                subcategoryContainer.style.display = 'block';
            } else {
                subcategoryContainer.style.display = 'none';
            }
        });

        document.getElementById('subcategory').addEventListener('change', function () {
            const selectedSubcategoryId = this.value;
            const ministryContainer = document.getElementById('ministry-container');
            const ministrySelect = document.getElementById('ministry');

            ministrySelect.innerHTML = '<option value="">-- Select Ministry --</option>';

            const selectedSubcategory = categories
                .flatMap(category => category.children)
                .find(subcategory => subcategory.id == selectedSubcategoryId);

            if (selectedSubcategory && selectedSubcategory.name === "Vazirlar idoras + Vazirliklar ro’yxatii") {
                ministries.forEach(ministry => {
                    const option = document.createElement('option');
                    option.value = ministry.id;
                    option.textContent = ministry.name;
                    ministrySelect.appendChild(option);
                });
                ministryContainer.style.display = 'block';
            } else {
                ministryContainer.style.display = 'none';
            }
        });

     
    </script>
@endsection
