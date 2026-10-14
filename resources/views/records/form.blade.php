@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h1 class="h2 mb-1">{{ $record ? 'Edit '.$singular : 'Add '.$singular }}</h1>
        <p class="text-secondary mb-0">{{ $record ? 'Update the details below.' : 'Enter the details below to create a record.' }}</p>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form action="{{ $formAction }}" method="POST">
                @csrf
                @if ($httpMethod !== 'POST')
                    @method($httpMethod)
                @endif

                <div class="row g-3">
                    @foreach ($fields as $field)
                        @php
                            $name = $field['name'];
                            $value = old($name, $record ? data_get($record, $name) : '');
                            if ($value instanceof \Illuminate\Support\Carbon) {
                                $value = $value->format('Y-m-d');
                            } elseif ($field['type'] === 'time' && $value) {
                                $value = substr((string) $value, 0, 5);
                            }
                            $options = $formOptions[$name] ?? $field['options'] ?? [];
                            $required = ! ($field['nullable'] ?? false);
                        @endphp
                        <div class="col-md-6">
                            <label class="form-label" for="{{ $name }}">
                                {{ $field['label'] }}@if ($required) <span class="required-mark">*</span>@endif
                            </label>
                            @if ($field['type'] === 'textarea')
                                <textarea class="form-control @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" rows="3" @required($required)>{{ $value }}</textarea>
                            @elseif ($field['type'] === 'select')
                                <select class="form-select @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}" @required($required)>
                                    @if (! $required)
                                        <option value="">No vehicle</option>
                                    @else
                                        <option value="">Select {{ strtolower($field['label']) }}</option>
                                    @endif
                                    @foreach ($options as $optionValue => $optionLabel)
                                        <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input class="form-control @error($name) is-invalid @enderror"
                                       id="{{ $name }}" name="{{ $name }}" type="{{ $field['type'] }}"
                                       value="{{ $value }}"
                                       @if ($field['type'] === 'number') min="0" step="{{ $name === 'fee' ? '0.01' : '1' }}" @endif
                                       @required($required)>
                            @endif
                            @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary" type="submit">{{ $record ? 'Save changes' : 'Create '.$singular }}</button>
                    <a class="btn btn-outline-secondary" href="{{ route($resource.'.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
