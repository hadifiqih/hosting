@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Antree')
<img src="{{ asset('adminlte') }}/dist/img/antree-150x150.png" class="logo" alt="Antree Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
