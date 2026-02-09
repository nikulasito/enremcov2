<option value="">Filter by Office</option>
@foreach($offices as $o)
    <option value="{{ $o }}" {{ request('office') === $o ? 'selected' : '' }}>
        {{ $o }}
    </option>
@endforeach