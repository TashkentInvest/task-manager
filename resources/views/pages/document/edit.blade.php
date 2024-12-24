@extends('layouts.admin')

@section('content')
    <h1 class="mb-4 text-center text-primary">Ҳужжатни Таҳрирлаш</h1>

    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Title Field -->
        <div class="mb-3">
            <label for="title" class="form-label">Сарлавха:</label>
            <input type="text" name="title" id="title" value="{{ old('title', $document->title) }}"
                class="form-control" required>
        </div>

        <!-- Status Type Dropdown -->
        <div class="mb-3">
            <label for="status_type" class="form-label">Ҳужжат Турини Танланг:</label>
            <select name="status_type" id="status_type" class="form-select" required>
                <option value="">-- Ҳужжат Турини Танланг --</option>
                <option value="kiruvchi"
                    {{ old('status_type', isset($document) ? $document->status_type : '') == 'kiruvchi' ? 'selected' : '' }}>
                    Кирувчи</option>
                <option value="chiquvchi"
                    {{ old('status_type', isset($document) ? $document->status_type : '') == 'chiquvchi' ? 'selected' : '' }}>
                    Чиқувчи</option>
            </select>
        </div>


        <!-- Letter Number Field -->
        <div class="mb-3">
            <label for="letter_number" class="form-label">Хат Рақами:</label>
            <input type="text" name="letter_number" id="letter_number"
                value="{{ old('letter_number', $document->letter_number) }}" class="form-control" required>
        </div>

        <!-- Received Date Field -->
        <div class="mb-3">
            <label for="received_date" class="form-label">Қабул Қилинган Санаси:</label>
            <input type="datetime-local" name="received_date" id="received_date"
                value="{{ old('received_date', \Carbon\Carbon::parse($document->received_date)->format('Y-m-d\TH:i')) }}"
                class="form-control" required>
        </div>

        <!-- Main Category Dropdown -->
        <div class="mb-3">
            <label for="main-category" class="form-label">Асосий Категория:</label>
            <select id="main-category" name="category_id" class="form-select" required>
                <option value="">-- Асосий Категорияни Танланг --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (old('main_category', $document->category->parent_id ?? $document->category_id) == $category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Subcategory Dropdown (Hidden initially) -->
        <div id="subcategory-container" class="mb-3"
            style="margin-top: 10px; {{ $document->category->parent_id ? '' : 'display: none;' }}">
            <label for="subcategory" class="form-label">Қўшимча Категория:</label>
            <select id="subcategory" name="document_category_id" class="form-select" required>
                <option value="">-- Қўшимча Категорияни Танланг --</option>
                @if ($document->category->parent_id)
                    @foreach ($categories->find($document->category->parent_id)->children as $subcategory)
                        <option value="{{ $subcategory->id }}" @if (old('document_category_id', $document->category->parent ? $document->category->id : null) == $subcategory->id) selected @endif>
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Ministry Dropdown (Hidden initially) -->
        <div id="ministry-container" class="mb-3"
            style="margin-top: 10px; {{ $selectedMinistry ? '' : 'display: none;' }}">
            <label for="ministry" class="form-label">Вазирлик:</label>
            <select id="ministry" name="ministry_id" class="form-select">
                <option value="">-- Вазирликни Танланг --</option>
                @foreach ($ministries as $ministry)
                    <option value="{{ $ministry->id }}" @if (old('ministry_id', $selectedMinistry->id ?? null) == $ministry->id) selected @endif>
                        {{ $ministry->name }}
                    </option>
                @endforeach
            </select>
        </div>



        <!-- File Upload Field -->
        <div class="mb-3">
            <label for="files" class="form-label">Қўшимча Файллар Қўшиш:</label>
            <input type="file" name="files[]" id="files" class="form-control" multiple>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100">Ҳужжатни Янгилаш</button>
    </form>

    <!-- Back to Document List Button -->
    <a href="{{ route('documents.index') }}" class="btn btn-secondary w-100 mt-3">Ҳужжатлар Рўйхатга Қайтиш</a>
    <script>
        const categories = @json($categories);
        const ministries = @json($ministries);

        document.getElementById('main-category').addEventListener('change', function() {
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

        document.getElementById('subcategory').addEventListener('change', function() {
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
