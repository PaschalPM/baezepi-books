{{-- Structured data (schema.org JSON-LD). Pass $data as an array; empty values are dropped. --}}
@php
    $clean = function ($value) use (&$clean) {
        if (! is_array($value)) {
            return $value;
        }
        $value = array_map($clean, $value);

        return array_filter($value, fn ($v) => $v !== null && $v !== '' && $v !== []);
    };
@endphp
{{-- '@'.'context' is written in two parts because Blade treats @context as a directive. --}}
<script type="application/ld+json">{!! json_encode($clean(['@'.'context' => 'https://schema.org'] + $data), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
